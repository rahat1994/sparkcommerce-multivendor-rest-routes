<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Binafy\LaravelCart\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Exceptions\VendorNotSameException;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVProductResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Controllers\CartController as SCCartController;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVVendor;
use Rahat1994\SparkcommerceRestRoutes\Exceptions\MinimumOrderAmountException;
use Illuminate\Support\Arr;

class CartController extends SCCartController
{
    protected $vendorId;

    protected $vendorModel;

    public function productHasDifferentVendor($product, $cart)
    {
        $cartItems = $cart->items()->get();
        foreach ($cartItems as $item) {
            if ($item->itemable->vendor_id != $product->vendor_id) {
                return true;
            }
        }

        return false;
    }

    protected function beforeAddingItemToCart($cart, $product, $request)
    {
        if ($this->productHasDifferentVendor($product, $cart)) {
            throw new VendorNotSameException('Product has different vendor');
        }

        $data = [
            'itemable_id' => $product->id,
            'itemable_type' => SCProduct::class,
            'quantity' => $request->quantity,
        ];

        return $data;
    }

    protected function beforeProcessingCheckoutCartItems($items)
    {
        try {
            $cart = Cart::query()->firstOrCreate(['user_id' => Auth::id()]);
            $cartItems = $cart->items()->get();
            $vendorId = null;
            foreach ($cartItems as $item) {
                $vendorId = $item->itemable->vendor_id;
            }
            $this->vendorId = $vendorId;

            return $items;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected function beforeOrderIsSaved(array $orderData): array
    {
        $orderData['vendor_id'] = $this->vendorId;

        return $orderData;
    }

    protected function getResourceClassMapping(): array
    {
        return [
            SCProduct::class => SCMVProductResource::class,
        ];
    }

    protected function couponValidationShouldContinue($cart, $couponCode)
    {
        $cartItems = $cart->items()->get();
        $vendorId = null;
        foreach ($cartItems as $item) {
            $vendorId = $item->itemable->vendor_id;
        }

        if (! $vendorId) {
            throw new \Exception('Cart is empty or does not have items with vendor information');
        }
        $couponData = $this->couponData($couponCode);

        if ($couponData->vendor_id != $vendorId) {
            return [false, 'Invalid Coupon'];
        }

        return [true, ''];
    }

    public function afterOrderIsSaved($order)
    {
        // Custom logic after order is saved
        // For example, you might want to send a confirmation email or update inventory
        return $order;
    }

    protected function afterProcessingCheckoutCartItems($items, $totalAmount)
    {
        // dd($items, $totalAmount, $this->vendorId);

        $vendor = SCMVVendor::find($this->vendorId);
        if(! $vendor) {
            throw new \Exception('Vendor not found for the cart items');
        }

        $meta = $vendor->meta;

        $minimumOrderAmount = Arr::get($meta, 'minimum_order_amount', 0);
        $minimumShippingFee = Arr::get($meta, 'minimum_shipping_fee', 0);
        $freeShippingMinimumOrderAmount = Arr::get($meta, 'free_shipping_minimum_order_amount', 0);

        if ($totalAmount < $minimumOrderAmount) {
            throw new MinimumOrderAmountException("Minimum order amount is {$minimumOrderAmount}");
        }

        if ($totalAmount < $freeShippingMinimumOrderAmount) {
            $totalAmount += $minimumShippingFee;

            $orderAmountMeta = [
                'amount' => $totalAmount,
                'meta' => [
                    'message' => 'Shipping fee applied',
                    'minimum_shipping_fee' => $minimumShippingFee,
                    'free_shipping_minimum_order_amount' => $freeShippingMinimumOrderAmount,
                ]
            ];

        }

        if(!isset($orderAmountMeta)){
            $orderAmountMeta = [
                'amount' => $totalAmount,
                'meta' => []
            ];
        }

        return $orderAmountMeta;
    }
}
