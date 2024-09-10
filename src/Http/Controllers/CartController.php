<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Exceptions\VendorNotSameException;
use Rahat1994\SparkcommerceRestRoutes\Http\Controllers\CartController as SCCartController;

class CartController extends SCCartController
{
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
}
