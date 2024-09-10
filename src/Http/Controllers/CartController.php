<?php

namespace Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers;

use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Exception;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Exceptions\VendorNotSameException;
use Rahat1994\SparkcommerceRestRoutes\Http\Controllers\CartController as SCCartController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rahat1994\SparkCommerce\Models\SCAnonymousCart;
use Rahat1994\SparkCommerce\Models\SCCoupon;
use Rahat1994\SparkCommerce\Models\SCOrder;
use Rahat1994\SparkCommerce\Models\SCProduct;
use Rahat1994\SparkcommerceMultivendor\Models\SCMVVendor;
use Rahat1994\SparkcommerceRestRoutes\Http\Resources\SCProductResource;

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
