<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Common\Repositories;

use InnoShop\Common\Models\MemberData;

class MemberDataRepo extends BaseRepo
{
    /**
     * @return string
     */
    public function model(): string
    {
        return MemberData::class;
    }

    /**
     * @param  array  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function builder(array $filters = []): \Illuminate\Database\Eloquent\Builder
    {
        $builder = $this->model::query();

        if (isset($filters['customer_id'])) {
            $builder->where('customer_id', $filters['customer_id']);
        }

        return $builder;
    }
}
