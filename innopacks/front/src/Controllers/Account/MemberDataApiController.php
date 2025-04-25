<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Front\Controllers\Account;

use App\Http\Controllers\Controller;
use InnoShop\Common\Repositories\MemberDataRepo;

class MemberDataApiController extends Controller
{
    /**
     * Get member data for the current customer as JSON
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMemberData()
    {
        // Check if user is logged in
        $customerId = current_customer_id();

        // Log for debugging
        \Log::debug('MemberDataApi: getMemberData called', [
            'customer_id' => $customerId,
            'is_logged_in' => auth('customer')->check(),
            'request_url' => request()->fullUrl(),
            'request_method' => request()->method(),
        ]);

        // If not logged in, return empty data with success status
        if (!$customerId) {
            \Log::debug('MemberDataApi: User not logged in');
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'User not logged in',
                'debug' => [
                    'customer_id' => $customerId,
                    'item_count' => 0,
                    'data_count' => 0
                ]
            ]);
        }

        $filters = [
            'customer_id' => $customerId,
        ];

        \Log::debug('MemberDataApi: Fetching data with filters', $filters);
        $items = MemberDataRepo::getInstance()->builder($filters)->get();
        \Log::debug('MemberDataApi: Found items', ['count' => count($items)]);

        $memberData = [];

        foreach ($items as $item) {
            \Log::debug('MemberDataApi: Processing item', [
                'id' => $item->id,
                'customer_id' => $item->customer_id,
                'member_data' => $item->member_data,
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

        \Log::debug('MemberDataApi: Returning response', [
            'success' => true,
            'data_count' => count($memberData),
            'customer_id' => $customerId,
        ]);

        return response()->json([
            'success' => true,
            'data' => $memberData,
            'debug' => [
                'customer_id' => $customerId,
                'item_count' => count($items),
                'data_count' => count($memberData)
            ]
        ]);
    }
}
