<?php

use Illuminate\Support\Facades\Route;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\AdvertisementController;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\CartController;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\OrderController;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\ProductsController;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\ShopCategoryController;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\VendorController;

Route::group(['prefix' => 'scmv/v1'], function () {
    Route::get('/shop_categories', [ShopCategoryController::class, 'index']);
    Route::get('/top_vendors', [VendorController::class, 'topVendors']);    
    Route::get('/advertisements', [AdvertisementController::class, 'advertisements']);
    Route::get('/product_recomedation/{product_count}', [ProductsController::class, 'productRecomendation']);

    Route::get('/search/{search_term}', [ProductsController::class, 'gloalSearch']);

    Route::group(['prefix' => 'vendor'], function () {
        Route::get('/', [VendorController::class, 'index']);
        Route::get('/search/{category?}', [VendorController::class, 'search']);
        Route::get('/{vendor_slug}', [VendorController::class, 'show']);
        Route::get('/{vendor_slug}/products', [ProductsController::class, 'products']);
        Route::get('/{vendor_slug}/products/{product_slug}', [ProductsController::class, 'product']);
        Route::get('/{vendor_slug}/categories', [VendorController::class, 'categories']);
        Route::get('/{vendor_slug}/categories/{category_id}', [VendorController::class, 'category']);
        Route::get('/{vendor_slug}/search/{search_term}', [ProductsController::class, 'searchProdcuts']);
    });

    Route::get('/cart/{reference?}', [CartController::class, 'getCart']);
    Route::post('/cart/{reference?}', [CartController::class, 'addToCart']);

    Route::delete('/cart/clear_all', [CartController::class, 'clearUserCart']);
    Route::delete('/cart/{slug}/{reference?}', [CartController::class, 'removeFromCart']);
    Route::group(['prefix' => 'orders', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{trackingNumber}', [OrderController::class, 'show']);
    });
});
