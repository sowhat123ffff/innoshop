<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Common\Repositories;

use Illuminate\Database\Eloquent\Builder;
use InnoShop\Common\Models\CartItem;
use InnoShop\Common\Models\Product\Sku;
use Throwable;

class CartItemRepo extends BaseRepo
{
    /**
     * Get filter builder.
     *
     * @param  array  $filters
     * @return Builder
     */
    public function builder(array $filters = []): Builder
    {
        $builder = CartItem::query()->with([
            'product.translation',
        ]);

        $skuCode = $filters['sku_code'] ?? '';
        if ($skuCode) {
            $builder->where('sku_code', $skuCode);
        }

        $customerID = $filters['customer_id'] ?? 0;
        if ($customerID) {
            $builder->where('customer_id', $customerID);
        }

        $guestID = $filters['guest_id'] ?? 0;
        if ($guestID) {
            $builder->where('guest_id', $guestID);
        }

        $selected = $filters['selected'] ?? false;
        if ($selected) {
            $builder->where('selected', true);
        }

        return fire_hook_filter('repo.cart_item.builder', $builder);
    }

    /**
     * @param  $data
     * @return mixed
     * @throws Throwable
     */
    public function create($data): mixed
    {
        $data    = $this->handleData($data);
        $filters = [
            'sku_code'    => $data['sku_code'],
            'customer_id' => $data['customer_id'],
            'guest_id'    => $data['guest_id'],
        ];

        // Check if the product has custom_enabled and custom_data
        $hasCustomData = isset($data['custom_data']) && !empty($data['custom_data']);
        $isCustomEnabled = false;

        // Get the product to check if custom_enabled is true
        if ($hasCustomData) {
            $sku = Sku::query()->where('code', $data['sku_code'])->first();
            if ($sku && $sku->product) {
                $isCustomEnabled = (bool)$sku->product->custom_enabled;
            }
        }

        // If product has custom_enabled=true and custom_data is provided, always create a new cart item
        if ($isCustomEnabled && $hasCustomData) {
            $cart = new CartItem($data);
            $cart->saveOrFail();
        } else {
            // Standard behavior for non-custom products
            $cart = $this->builder($filters)->first();
            if (empty($cart)) {
                $cart = new CartItem($data);
                $cart->saveOrFail();
            } else {
                // Update custom_data if provided
                if ($hasCustomData) {
                    $cart->custom_data = $data['custom_data'];
                    $cart->save();
                }
                $cart->increment('quantity', $data['quantity']);
            }
        }

        return $cart;
    }

    /**
     * @param  $requestData
     * @return array
     */
    private function handleData($requestData): array
    {
        $skuId = $requestData['skuId'] ?? ($requestData['sku_id'] ?? 0);
        if ($skuId) {
            $sku = Sku::query()->findOrFail($skuId);
        } else {
            $sku = Sku::query()->where('code', $requestData['sku_code'] ?? '')->firstOrFail();
        }

        $customerID = $requestData['customer_id'] ?? 0;
        $guestID    = $requestData['guest_id']    ?? 0;

        // Extract custom form data if available
        $customData = null;

        // Check if custom_data is directly provided
        if (isset($requestData['custom_data']) && is_array($requestData['custom_data'])) {
            $customData = $requestData['custom_data'];
        } else {
            // Fallback to individual fields
            $customFields = [
                'customerName', 'customerGender', 'customerDOB', 'customerLunarDOB',
                'customerZodiac', 'customerTimeOfBirth', 'customerTimeOfBirthValue', 'customerWhatsApp'
            ];

            foreach ($customFields as $field) {
                if (isset($requestData[$field])) {
                    if ($customData === null) {
                        $customData = [];
                    }
                    $customData[$field] = $requestData[$field];
                }
            }
        }

        return [
            'product_id'  => $sku->product_id,
            'sku_code'    => $sku->code,
            'customer_id' => $customerID,
            'guest_id'    => $customerID ? '' : $guestID,
            'selected'    => true,
            'quantity'    => (int) ($requestData['quantity'] ?? 1),
            'custom_data' => $customData,
        ];
    }
}
