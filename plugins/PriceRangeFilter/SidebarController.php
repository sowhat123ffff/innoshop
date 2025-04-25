<?php
namespace Plugin\PriceRangeFilter;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class SidebarController extends Controller
{
    public function sidebar()
    {
        $min = DB::table('products')->min('price');
        $max = DB::table('products')->max('price');
        return view('Plugin/PriceRangeFilter::price_slider', [
            'minPrice' => $min,
            'maxPrice' => $max,
            'selectedMin' => request('min_price', $min),
            'selectedMax' => request('max_price', $max),
        ]);
    }
}
