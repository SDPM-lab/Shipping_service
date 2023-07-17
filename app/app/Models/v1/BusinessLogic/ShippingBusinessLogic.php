<?php

namespace App\Models\v1\BusinessLogic;

use App\Models\v1\ShippingModel;
use App\Entities\v1\ShippingEntity;

class ShippingBusinessLogic
{
    /**
     * 取得運送資訊
     *
     * @param string $shippingKey
     * @return ShippingEntity|null
     */
    static function getShipping(string $o_key): ?ShippingEntity
    {
        $shippingModel = new ShippingModel();

        $shippingEntity = $shippingModel->where('o_key', $o_key)->first();

        return $shippingEntity;
    }

}
