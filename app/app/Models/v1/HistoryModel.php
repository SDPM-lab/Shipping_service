<?php

namespace App\Models\v1;

use CodeIgniter\Model;
use App\Entities\v1\HistoryEntity;

class HistoryModel extends Model
{
    protected $DBGroup          = USE_DB_GROUP;
    protected $table            = 'history';
    protected $primaryKey       = 'h_key';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = HistoryEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'h_key', 's_key', 'o_key', 'status', 'created_at', 'updated_at', 'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
