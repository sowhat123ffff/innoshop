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
use InnoShop\Common\Models\Product;
use InnoShop\Common\Repositories\CategoryRepo;
use InnoShop\Common\Repositories\ProductRepo;
use InnoShop\Common\Repositories\ReviewRepo;
use InnoShop\Common\Resources\ProductVariable;
use InnoShop\Common\Resources\ReviewListItem;
use InnoShop\Common\Resources\SkuListItem;

class ProductController extends Controller
{
    /**
     * Get the maximum price of all products
     *
     * @return float
     */
    private function getMaxPrice(): float
    {
        $maxPrice = DB::table('products')
            ->join('product_skus', function ($join) {
                $join->on('products.id', '=', 'product_skus.product_id')
                    ->where('product_skus.is_default', '=', 1);
            })
            ->where('products.active', '=', 1)
            ->max('product_skus.price');

        return round($maxPrice ?? 0, 2);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        // Get price filter parameters
        $priceStart = $request->get('price_start', 0);
        $priceEnd = $request->get('price_end');
        $inStockOnly = $request->has('in_stock');

        $filters = $request->all();

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
        $maxPrice = $this->getMaxPrice();

        $products = ProductRepo::getInstance()->withActive()->list($filters);

        $data = [
            'products'       => $products,
            'categories'     => CategoryRepo::getInstance()->getTwoLevelCategories(),
            'per_page_items' => CategoryRepo::getInstance()->getPerPageItems(),
            'max_price'      => $maxPrice,
            'price_start'    => $priceStart,
            'price_end'      => $priceEnd ?: $maxPrice,
            'in_stock'       => $inStockOnly,
        ];

        return inno_view('products.index', $data);
    }

    /**
     * @param  Request  $request
     * @param  Product  $product
     * @return mixed
     */
    public function show(Request $request, Product $product): mixed
    {
        if (! $product->active) {
            abort(404);
        }

        $skuId = $request->get('sku_id');

        return $this->renderShow($product, $skuId);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function slugShow(Request $request): mixed
    {
        $slug    = $request->slug;
        $product = ProductRepo::getInstance()->withActive()->builder(['slug' => $slug])->firstOrFail();

        $skuId = $request->get('sku_id');

        return $this->renderShow($product, $skuId);
    }

    /**
     * @param  Product  $product
     * @param  $skuId
     * @return mixed
     */
    private function renderShow(Product $product, $skuId): mixed
    {
        if ($skuId) {
            $sku = Product\Sku::query()->find($skuId);
        }

        if (empty($sku)) {
            $sku = $product->masterSku;
        }

        $product->increment('viewed');
        $reviews    = ReviewRepo::getInstance()->getListByProduct($product);
        $customerID = current_customer_id();
        $variables  = ProductVariable::collection($product->variables)->jsonSerialize();

        // Get member data for the current customer
        $memberData = [];

        // Check if the member_data table exists
        $memberDataTableExists = \Schema::hasTable('member_data');
        \Log::debug('ProductController: Member data table exists?', [
            'exists' => $memberDataTableExists
        ]);

        if (!$memberDataTableExists) {
            \Log::error('ProductController: Member data table does not exist!');
            // Don't add any dummy data when the table doesn't exist
        } else if ($customerID) {
            $filters = [
                'customer_id' => $customerID,
            ];

            // Log for debugging
            \Log::debug('ProductController: Fetching member data', [
                'customer_id' => $customerID,
                'filters' => $filters
            ]);

            try {
                $memberDataItems = \InnoShop\Common\Repositories\MemberDataRepo::getInstance()->builder($filters)->get();

                \Log::debug('ProductController: Found member data items', [
                    'count' => count($memberDataItems)
                ]);

                foreach ($memberDataItems as $item) {
                    \Log::debug('ProductController: Processing member data item', [
                        'id' => $item->id,
                        'member_data' => $item->member_data
                    ]);

                    $memberData[] = [
                        'id' => $item->id,
                        'name' => $item->member_data['name'] ?? '',
                        'gender' => $item->member_data['gender'] ?? '',
                        'zodiac' => $item->member_data['zodiac'] ?? '',
                        'birth_date' => $item->member_data['birth_date'] ?? '',
                        'lunar_date' => $item->member_data['lunar_date'] ?? '',
                        'birth_time' => $item->member_data['birth_time'] ?? '',
                        'whatsapp' => $item->member_data['whatsapp'] ?? '',
                    ];
                }

                // If no member data found, don't add any dummy data
                if (empty($memberData)) {
                    \Log::debug('ProductController: No member data found for this customer');
                    // The frontend will handle showing the "No member data found" message
                }
            } catch (\Exception $e) {
                \Log::error('ProductController: Error fetching member data', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                // Don't add any dummy data when there's an error
                // The frontend will handle showing an appropriate message
            }
        } else {
            \Log::debug('ProductController: No customer ID, not adding any member data');
            // Don't add any dummy data when user is not logged in
            // The frontend will handle showing the login message
        }

        $data = [
            'product'    => $product,
            'sku'        => (new SkuListItem($sku))->jsonSerialize(),
            'skus'       => SkuListItem::collection($product->skus)->jsonSerialize(),
            'variants'   => $variables,
            'attributes' => $product->groupedAttributes(),
            'reviews'    => ReviewListItem::collection($reviews)->jsonSerialize(),
            'reviewed'   => ReviewRepo::productReviewed($customerID, $product->id),
            'related'    => $product->relationProducts,
            'member_data' => $memberData, // Add member data to the view
        ];

        return inno_view('products.show', $data);
    }
}
