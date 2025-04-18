<?php

use Illuminate\Support\Facades\Route;
use Plugin\CustomPlugin\Controllers\ProductSectionController;

// 产品区块选择相关路由
Route::prefix('plugins/CustomPlugin')->group(function () {
    Route::get('check-product', [ProductSectionController::class, 'checkProduct']);
    Route::post('save-product-sections', [ProductSectionController::class, 'saveProductSections']);
}); 