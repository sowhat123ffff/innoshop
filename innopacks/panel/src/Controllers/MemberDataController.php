<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Panel\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use InnoShop\Common\Models\MemberData;
use InnoShop\Common\Repositories\MemberDataRepo;
use Throwable;

class MemberDataController extends Controller
{
    /**
     * Store a newly created member data
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $data = $this->validateMemberData($request);
            
            // Create a new member data record
            $memberData = MemberDataRepo::getInstance()->create([
                'customer_id' => $data['customer_id'],
                'member_data' => $data['member_data'],
            ]);

            // Log success for debugging
            \Log::info('Admin panel: Member data saved successfully', [
                'member_data_id' => $memberData->id,
                'member_data' => $memberData->member_data
            ]);

            return response()->json([
                'success' => true,
                'message' => panel_trans('common.saved_success'),
                'data' => $memberData
            ]);

        } catch (Throwable $e) {
            // Log error for debugging
            \Log::error('Admin panel: Failed to save member data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update the specified member data
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $memberData = MemberData::findOrFail($id);
            $data = $this->validateMemberData($request);

            MemberDataRepo::getInstance()->update($memberData, [
                'member_data' => $data['member_data']
            ]);

            // Log success for debugging
            \Log::info('Admin panel: Member data updated successfully', [
                'member_data_id' => $memberData->id,
                'member_data' => $memberData->member_data
            ]);

            return response()->json([
                'success' => true,
                'message' => panel_trans('common.updated_success'),
                'data' => $memberData
            ]);

        } catch (Throwable $e) {
            // Log error for debugging
            \Log::error('Admin panel: Failed to update member data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove the specified member data
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $memberData = MemberData::findOrFail($id);
            MemberDataRepo::getInstance()->destroy($memberData);

            return response()->json([
                'success' => true,
                'message' => panel_trans('common.deleted_success')
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Validate member data from request
     *
     * @param  Request  $request
     * @return array
     */
    private function validateMemberData(Request $request): array
    {
        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'name' => 'required|string|max:255',
            'member_data.gender' => 'required|in:Male,Female,男 Male,女 Female',
            'member_data.zodiac' => 'required|string',
            'member_data.birth_date' => 'required|date',
            'member_data.lunar_date' => 'required|string',
            'member_data.birth_time' => 'required|string',
            'member_data.whatsapp' => 'required|string',
        ]);

        // Add the name to the member_data array
        $data = $request->all();
        if (!isset($data['member_data'])) {
            $data['member_data'] = [];
        }
        $data['member_data']['name'] = $request->input('name');

        return $data;
    }
}
