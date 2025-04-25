<?php
use Illuminate\Support\Facades\Route;

Route::get('/plugin/pricerangefilter/sidebar', [\Plugin\PriceRangeFilter\SidebarController::class, 'sidebar'])->name('plugin.pricerangefilter.sidebar');

// AJAX route for price filtering
Route::get('/plugin/pricerangefilter/filter', [\Plugin\PriceRangeFilter\Controllers\ProductFilterController::class, 'filter'])->name('plugin.pricerangefilter.filter');
