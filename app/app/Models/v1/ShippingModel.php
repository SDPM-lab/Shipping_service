<?php

namespace App\Models\v1;

use CodeIgniter\Model;

use App\Entities\v1\ShippingEntity;

class ShippingModel extends Model
{
    protected $DBGroup          = USE_DB_GROUP;
    protected $table            = 'shipping';
    protected $primaryKey       = 's_key';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = ShippingEntity::class;
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        's_key', 'o_key', 'status', 'created_at', 'updated_at', 'deleted_at'
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

    /**
     * Create shipping transaction.
     *
     * @param string $o_key
     * @param string $status
     * @return boolean
     */
    public function createShippingTransaction(string $o_key, string $status): bool
    {
        try {
            $this->db->transStart();

            $shipping = [
                "o_key"   => $o_key,
                "status"  => $status,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ];

            $this->db->table("shipping")
                    ->insert($shipping);
            
            $s_key = $this->db->insertID();

            $history = [
                "s_key"   => $s_key,
                "o_key"   => $o_key,
                "status"  => $status,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ];

            $this->db->table("history")
                     ->insert($history);

            if ($this->db->transStatus() === false || $this->db->affectedRows() == 0) {
                $this->db->transRollback();
                return false;
            } else {
                $this->db->transCommit();
                return true;
            }
        } catch (\Exception $e) {
            log_message('error', '[ERROR] {exception}', ['exception' => $e]);
            return false;
        }
        return true;
    }

    /**
     * Update shipping transaction.
     *
     * @param integer $s_key
     * @param string $o_key
     * @param string $status
     * @return boolean
     */
    public function updateShippingTransaction(int $s_key, string $o_key, string $status): bool
    {
        try {
            $this->db->transStart();

            $history = [
                "s_key"   => $s_key,
                "o_key"   => $o_key,
                "status"  => $status,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ];

            $this->db->table("history")
                     ->insert($history);

            $shipping = [
                "status"  => $status,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ];

            $this->db->table("shipping")
                    ->where("s_key", $s_key)
                    ->update($shipping);

            if ($this->db->transStatus() === false || $this->db->affectedRows() == 0) {
                $this->db->transRollback();
                return false;
            } else {
                $this->db->transCommit();
                return true;
            }
        } catch (\Exception $e) {
            log_message('error', '[ERROR] {exception}', ['exception' => $e]);
            return false;
        }
        return true;
    }

    /**
     * Delete shipping transaction.
     *
     * @param integer $s_key
     * @param string $o_key
     * @return boolean
     */
    public function deleteShippingTransaction(int $s_key, string $o_key): bool
    {
        try {
            $this->db->transStart();

            $history = [
                "s_key"   => $s_key,
                "o_key"   => $o_key,
                "status"  => "deleted",
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s")
            ];

            $this->db->table("history")
                     ->insert($history);

            $time = [
                "deleted_at" => date("Y-m-d H:i:s")
            ];

            $this->db->table("shipping")
                     ->where("s_key", $s_key)
                     ->update($time);

            if ($this->db->transStatus() === false || $this->db->affectedRows() == 0) {
                $this->db->transRollback();
                return false;
            } else {
                $this->db->transCommit();
                return true;
            }
        } catch (\Exception $e) {
            log_message('error', '[ERROR] {exception}', ['exception' => $e]);
            return false;
        }
        return true;
    }
}
