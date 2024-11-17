<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Binafy\LaravelCart\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Exceptions\VendorNotSameException;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Resources\SCMVProductResource;
use Rahat1994\SparkcommerceRestRoutes\Http\Controllers\CartController as SCCartController;

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

        $couponData = $this->couponData($couponCode);


        if ($couponData->vendor_id != $vendorId) {
            return [false, 'Invalid Coupon'];
        }

        return [true, ''];
    }
}
