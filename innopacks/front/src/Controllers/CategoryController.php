<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Front\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InnoShop\Common\Models\Category;
use InnoShop\Common\Repositories\CategoryRepo;
use InnoShop\Common\Repositories\ProductRepo;

class CategoryController extends Controller
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $filters  = $request->all();
        $products = ProductRepo::getInstance()->withActive()->list($filters);

        $data = [
            'products'   => $products,
            'categories' => CategoryRepo::getInstance()->getTwoLevelCategories(),
        ];

        return inno_view('products.index', $data);
    }

    /**
     * Display the product list under the current category
     *
     * @param  Request  $request
     * @param  Category  $category
     * @return mixed
     * @throws Exception
     */
    public function show(Request $request, Category $category): mixed
    {
        $keyword = $request->get('keyword');

        return $this->renderShow($category, $keyword, $request);
    }

    /**
     * Display the product list under the current category
     *
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function slugShow(Request $request): mixed
    {
        $category = CategoryRepo::getInstance()->withActive()->builder(['slug' => $request->slug])->firstOrFail();
        $keyword  = $request->get('keyword');

        return $this->renderShow($category, $keyword, $request);
    }

    /**
     * Get the maximum price of products in a category
     *
     * @param int $categoryId
     * @return float
     */
    private function getMaxPrice(int $categoryId): float
    {
        $maxPrice = DB::table('products')
            ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
            ->join('product_skus', function ($join) {
                $join->on('products.id', '=', 'product_skus.product_id')
                    ->where('product_skus.is_default', '=', 1);
            })
            ->where('product_categories.category_id', '=', $categoryId)
            ->where('products.active', '=', 1)
            ->max('product_skus.price');

        return round($maxPrice ?? 0, 2);
    }

    /**
     * @param  Category  $category
     * @param  $keyword
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    private function renderShow(Category $category, $keyword, Request $request): mixed
    {
        $categories = CategoryRepo::getInstance()->getTwoLevelCategories();

        // Get price filter parameters
        $priceStart = $request->get('price_start', 0);
        $priceEnd = $request->get('price_end');
        $inStockOnly = $request->has('in_stock');

        $filters = [
            'category_id' => $category->id,
            'keyword'     => $keyword,
            'sort'        => $request->get('sort'),
            'order'       => $request->get('order'),
            'per_page'    => $request->get('per_page'),
        ];

        // Add price filter if provided
        if ($priceStart) {
            $filters['price_start'] = $priceStart;
        }

        if ($priceEnd) {
            $filters['price_end'] = $priceEnd;
        }

        // Add in-stock filter if provided
        if ($inStockOnly) {
            $filters['in_stock'] = true;
        }

        // Get max price for the slider
        $maxPrice = $this->getMaxPrice($category->id);

        $products = ProductRepo::getInstance()->getFrontList($filters);

        $data = [
            'slug'           => $category->slug ?? '',
            'category'       => $category,
            'categories'     => $categories,
            'products'       => $products,
            'per_page_items' => CategoryRepo::getInstance()->getPerPageItems(),
            'max_price'      => $maxPrice,
            'price_start'    => $priceStart,
            'price_end'      => $priceEnd ?: $maxPrice,
            'in_stock'       => $inStockOnly,
        ];

        return inno_view('categories.show', $data)->render();
    }
}
