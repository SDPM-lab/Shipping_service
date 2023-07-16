<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ShippingSeeder extends Seeder
{
    public function run()
    {
        for ($i=0; $i < 5; $i++) {
            $this->db->table("shipping")
                     ->insert([
                         "s_key" => $i + 1,
                         "o_key" => 'a' . $i + 1,
                         "status" => "shipped",
                         "created_at" => date("Y-m-d H:i:s"),
                         "updated_at" => date("Y-m-d H:i:s")
                     ]);

            $this->db->table("history")
                     ->insert([
                         "h_key" => $i + 1,
                         "s_key" => $i + 1,
                         "o_key" => 'a' . $i + 1,
                         "status" => "shipped",
                         "created_at" => date("Y-m-d H:i:s"),
                         "updated_at" => date("Y-m-d H:i:s")
                     ]);
        }
    }
}
