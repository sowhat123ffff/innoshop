<?php
namespace Plugin\PriceRangeFilter;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route; // Ensure this import is present
use Illuminate\Support\Facades\View; // Import the View facade

class Boot {
    public function init(): void {
        // Inject the plugin JS and noUiSlider assets globally using a footer hook
        listen_blade_insert('layout.footer.bottom', function ($data) {
            return '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css">'
                . '<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>'
                . '<script src="' . plugin_asset('price_range_filter', 'price_range_filter.js') . '"></script>';
        });

        // Register view namespace for plugin views
        View::addNamespace('Plugin/PriceRangeFilter', base_path('plugins/PriceRangeFilter/Views'));

        // Use Eloquent Sku model for price range (best practice)
        // This uses InnoShop\Common\Models\Product\Sku
        // Only consider SKUs with a non-null, positive price and quantity > 0
        $skuModel = \InnoShop\Common\Models\Product\Sku::query()->whereNotNull('price')->where('price', '>', 0)->where('quantity', '>', 0);

        // Inject the price slider into the sidebar using Blade hook
        listen_blade_insert('shared.filter_sidebar', function () use ($skuModel) {
            Log::info('PriceRangeFilter: sidebar hook triggered');
            $min = (clone $skuModel)->min('price') ?? 0;
            $max = (clone $skuModel)->max('price') ?? 0;
            Log::info('PriceRangeFilter: min=' . $min . ', max=' . $max);
            return view('Plugin/PriceRangeFilter::price_slider', [
                'minPrice' => $min,
                'maxPrice' => $max,
                'selectedMin' => request('min_price', $min),
                'selectedMax' => request('max_price', $max),
            ]);
        });

        // Register AJAX route for sidebar slider injection
        Route::get('/plugin/pricerangefilter/sidebar', function () use ($skuModel) {
            $min = (clone $skuModel)->min('price') ?? 0;
            $max = (clone $skuModel)->max('price') ?? 0;
            return view('Plugin/PriceRangeFilter::price_slider', [
                'minPrice' => $min,
                'maxPrice' => $max,
                'selectedMin' => request('min_price', $min),
                'selectedMax' => request('max_price', $max),
            ]);
        });

        // Filter product query by price range
        listen_hook_filter('product.query.filter', function ($query) {
            $min = request('min_price');
            $max = request('max_price');
            if ($min !== null) {
                $query->where('price', '>=', $min);
            }
            if ($max !== null) {
                $query->where('price', '<=', $max);
            }
            return $query;
        });
    }
}
