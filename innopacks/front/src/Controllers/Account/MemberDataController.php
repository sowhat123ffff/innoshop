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
use Illuminate\Http\Request;
use InnoShop\Common\Models\MemberData;
use InnoShop\Common\Repositories\MemberDataRepo;
use Throwable;

class MemberDataController extends Controller
{
    /**
     * Display a listing of member data
     *
     * @return mixed
     */
    public function index(): mixed
    {
        $filters = [
            'customer_id' => current_customer_id(),
        ];

        $items = MemberDataRepo::getInstance()->builder($filters)->get();

        $data = [
            'members' => $items,
        ];

        return inno_view('account.member_data.index', $data);
    }

    /**
     * Show the form for creating a new member data
     *
     * @return mixed
     */
    public function create(): mixed
    {
        return inno_view('account.member_data.form');
    }

    /**
     * Store a newly created member data
     *
     * @param  Request  $request
     * @return mixed
     * @throws Throwable
     */
    public function store(Request $request): mixed
    {
        try {
            $data = $this->validateMemberData($request);
            $data['customer_id'] = current_customer_id();

            // Ensure member_data is properly formatted as an array
            if (isset($data['member_data']) && is_array($data['member_data'])) {
                // Make sure all required fields are present
                $requiredFields = ['gender', 'zodiac', 'birth_date', 'lunar_date', 'birth_time', 'whatsapp'];
                foreach ($requiredFields as $field) {
                    if (!isset($data['member_data'][$field]) || empty($data['member_data'][$field])) {
                        throw new \Exception("The {$field} field is required in member data.");
                    }
                }
            } else {
                throw new \Exception("Member data is missing or not properly formatted.");
            }

            // Create a new member data record
            $memberData = MemberDataRepo::getInstance()->create([
                'customer_id' => current_customer_id(),
                'member_data' => $data['member_data'],
            ]);

            // Log success for debugging
            \Log::info('Member data saved successfully', [
                'member_data_id' => $memberData->id,
                'member_data' => $memberData->member_data
            ]);

            return redirect(account_route('member_data.index'))
                ->with('success', front_trans('common.created_success'));

        } catch (Throwable $e) {
            // Log error for debugging
            \Log::error('Failed to save member data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing member data
     *
     * @param  MemberData  $memberData
     * @return mixed
     */
    public function edit(MemberData $memberData): mixed
    {
        if ($memberData->customer_id != current_customer_id()) {
            abort(403);
        }

        $data = [
            'member' => $memberData,
        ];

        return inno_view('account.member_data.form', $data);
    }

    /**
     * Update the specified member data
     *
     * @param  Request  $request
     * @param  MemberData  $memberData
     * @return mixed
     */
    public function update(Request $request, MemberData $memberData): mixed
    {
        try {
            if ($memberData->customer_id != current_customer_id()) {
                abort(403);
            }

            $data = $this->validateMemberData($request);

            // Ensure member_data is properly formatted as an array
            if (isset($data['member_data']) && is_array($data['member_data'])) {
                // Make sure all required fields are present
                $requiredFields = ['gender', 'zodiac', 'birth_date', 'lunar_date', 'birth_time', 'whatsapp'];
                foreach ($requiredFields as $field) {
                    if (!isset($data['member_data'][$field]) || empty($data['member_data'][$field])) {
                        throw new \Exception("The {$field} field is required in member data.");
                    }
                }
            } else {
                throw new \Exception("Member data is missing or not properly formatted.");
            }

            MemberDataRepo::getInstance()->update($memberData, [
                'member_data' => $data['member_data']
            ]);

            // Log success for debugging
            \Log::info('Member data updated successfully', [
                'member_data_id' => $memberData->id,
                'member_data' => $memberData->member_data
            ]);

            return redirect(account_route('member_data.index'))
                ->with('success', front_trans('common.updated_success'));

        } catch (Throwable $e) {
            // Log error for debugging
            \Log::error('Failed to update member data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified member data
     *
     * @param  MemberData  $memberData
     * @return mixed
     */
    public function destroy(MemberData $memberData): mixed
    {
        try {
            if ($memberData->customer_id != current_customer_id()) {
                abort(403);
            }

            MemberDataRepo::getInstance()->destroy($memberData);

            return redirect(account_route('member_data.index'))
                ->with('success', front_trans('common.deleted_success'));

        } catch (Throwable $e) {
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
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
