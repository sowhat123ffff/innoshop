<?php
namespace Plugin\PriceRangeFilter\Controllers;

use Illuminate\Http\Request;
use InnoShop\Common\Models\Product;
use InnoShop\Common\Models\Product\Sku;

class ProductFilterController
{
    // AJAX endpoint for price filtering
    public function filter(Request $request)
    {
        $min = $request->input('price_start');
        $max = $request->input('price_end');

        // Get product IDs with SKUs in price range
        $productIds = Sku::query()
            ->where('price', '>=', $min)
            ->where('price', '<=', $max)
            ->pluck('product_id')
            ->unique();

        // Get products with those IDs
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->with(['translation', 'masterSku', 'image'])
            ->get();

        // Render a partial view (you may need to adjust the view path)
        $html = view('products.partials.list', ['products' => $products])->render();
        return response()->json(['html' => $html]);
    }
}
