<?php
use Rahat1994\SparkcommerceMultivendorRestRoutes\Http\Controllers\ShopCategoryController;


Route::group(['prefix' => 'scmv/v1'], function () {
    Route::get('/shop_categories', [ShopCategoryController::class, 'index']);
});