<?php

namespace App\Entities\v1;

use CodeIgniter\Entity\Entity;

class ShippingEntity extends Entity
{
    /**
     * 運送主鍵
     *
     * @var int
     */
    protected $s_key;

    /**
     * 訂單外來鍵
     *
     * @var int
     */
    protected $o_key;

    /**
     * 運送狀態
     *
     * @var string
     */
    protected $status;

    /**
     * 建立時間
     *
     * @var string
     */
    protected $createdAt;

    /**
     * 最後更新時間
     *
     * @var string
     */
    protected $updatedAt;

    /**
     * 刪除時間
     *
     * @var string
     */
    protected $deletedAt;

    protected $datamap = [
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        'deletedAt' => 'deleted_at'
    ];

    protected $casts = [
        'o_key' => 'string'
    ];

    protected $dates = []; 
}
