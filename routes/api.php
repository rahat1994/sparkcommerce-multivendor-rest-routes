<?php

use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\ProductsController;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\ShopCategoryController;
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\VendorController;

Route::group(['prefix' => 'scmv/v1'], function () {
    Route::get('/shop_categories', [ShopCategoryController::class, 'index']);
    Route::get('/top_vendors', [VendorController::class, 'topVendors']);
    Route::get('/product_recomedation/{product_count}', [ProductsController::class, 'productRecomendation']);
});
