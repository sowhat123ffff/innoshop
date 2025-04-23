<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Common\Repositories\Order;

use Exception;
use InnoShop\Common\Models\Order;
use InnoShop\Common\Models\Product\Sku;
use InnoShop\Common\Repositories\BaseRepo;

class ItemRepo extends BaseRepo
{
    /**
     * @param  $order
     * @return array
     */
    public function getOptions($order): array
    {
        $options = [];
        foreach ($order->items as $item) {
            $options[] = [
                'key'   => $item->id,
                'label' => $item->name,
            ];
        }

        return $options;
    }

    /**
     * @param  Order  $order
     * @param  $items
     * @return void
     * @throws Exception
     */
    public function createItems(Order $order, $items): void
    {
        if (empty($items)) {
            throw new Exception('Empty cart list when create order items.');
        }

        $orderItems = [];
        foreach ($items as $item) {
            // If the item is a CartItem model, get the custom_data from it
            if (is_object($item) && method_exists($item, 'getAttributes')) {
                $itemData = $item->getAttributes();
                // Add the cart item itself for reference
                $itemData['cart'] = $item;
            } else {
                $itemData = $item;
            }

            $orderItems[] = $this->handleItem($order, $itemData);
        }
        $order->items()->createMany($orderItems);
    }

    /**
     * @param  Order  $order
     * @param  $requestData
     * @return array
     */
    private function handleItem(Order $order, $requestData): array
    {
        $sku = Sku::query()->where('code', $requestData['sku_code'])->firstOrFail();

        // Get custom data from cart item if available
        $customData = null;
        if (isset($requestData['custom_data'])) {
            $customData = $requestData['custom_data'];
        } elseif (isset($requestData['cart']) && isset($requestData['cart']->custom_data)) {
            $customData = $requestData['cart']->custom_data;
        }

        return [
            'order_id'      => $requestData['order_id'] ?? 0,
            'product_id'    => $sku->product_id,
            'order_number'  => $order->number,
            'product_sku'   => $sku->code,
            'variant_label' => $requestData['variant_label'] ?? $sku->variant_label,
            'name'          => $requestData['product_name'],
            'image'         => $requestData['image'],
            'quantity'      => $requestData['quantity'],
            'price'         => $requestData['price'],
            'custom_data'   => $customData,
        ];
    }
}
