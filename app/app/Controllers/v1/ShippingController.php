<?php

namespace App\Controllers\v1;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\v1\BaseController;
use App\Models\v1\BusinessLogic\ShippingBusinessLogic;
use App\Models\v1\ShippingModel;
use App\Entities\v1\ShippingEntity;

class ShippingController extends BaseController
{
    use ResponseTrait;

    /**
     * [GET] /api/v1/shipping
     * 取得所有運送資訊
     *
     * @return void
     */
    public function index()
    {
        $limit  = $this->request->getGet("limit")  ?? 10;
        $offset = $this->request->getGet("offset") ?? 0;
        $isDesc = $this->request->getGet("isDesc") ?? "desc";

        $shippingModel  = new ShippingModel();
        $shippingEntity = new ShippingEntity();

        $query    = $shippingModel->orderBy("created_at", $isDesc ? "DESC" : "ASC");
        $amount   = $query->countAllResults(false);
        $shipping = $query->findAll($limit, $offset);

        $data = [
            "list"   => [],
            "amount" => $amount
        ];

        if ($shipping) {
            foreach ($shipping as $shippingEntity) {
                $shippingData = [
                    "s_key"     => $shippingEntity->s_key,
                    "o_key"     => $shippingEntity->o_key,
                    "status"    => $shippingEntity->status,
                    "createdAt" => $shippingEntity->createdAt,
                    "updatedAt" => $shippingEntity->updatedAt
                ];
                $data["list"][] = $shippingData;
            }
        } else {
            return $this->fail("無運送資料", 404);
        }

        return $this->respond([
            "msg"  => "OK",
            "data" => $data
        ]);
    }

    /**
     * [GET] /api/v1/shipping/{shippingKey}
     * 取得單一運送資訊
     *
     * @return void
     */
    public function show(string $orderKey = null)
    {
        if (is_null($orderKey)) {
            return $this->fail("無傳入運送 key", 404);
        }

        $shippingModel  = new ShippingModel();
        $shippingEntity = new ShippingEntity();

        $shippingEntity = $shippingModel->where(["o_key" => $orderKey])->first();

        if (is_null($shippingEntity)) {
            return $this->fail("查無此運送資訊", 404);
        }

        $data = [
            "s_key"     => $shippingEntity->s_key,
            "o_key"     => $shippingEntity->o_key,
            "status"    => $shippingEntity->status,
            "createdAt" => $shippingEntity->createdAt,
            "updatedAt" => $shippingEntity->updatedAt
        ];

        return $this->respond([
            "msg"  => "OK",
            "data" => $data
        ]);
    }

    /**
     * [POST] /api/v1/shipping
     * 產生一筆新的運送
     *
     * @return void
     */
    public function create()
    {
        $data = $this->request->getJSON(true);

        $o_key  = $data["o_key"] ?? null;
        $status = "shipping";

        if (is_null($o_key)) {
            return $this->fail("請確認輸入資料", 404);
        }

        $shippingEntity = ShippingBusinessLogic::getShipping($o_key);

        if (is_null($shippingEntity) === false) {
            return $this->failForbidden("此訂單已新增運送資訊。");
        }

        $shippingModel = new shippingModel();

        $result = $shippingModel->createShippingTransaction($o_key, $status);

        if ($result) {
            return $this->respond([
                        "msg"   => "OK"
                    ]);
        } else {
            return $this->fail("運送資訊新增失敗", 400);
        }
    }

    /**
     * [PUT] /api/v1/shipping
     * 更新運送資訊
     *
     * @return void
     */
    public function update()
    {
        $data = $this->request->getJSON(true);

        $o_key   = $data["o_key"] ?? null;
        $status  = $data["status"] ?? null;

        if (is_null($o_key) || is_null($status)) {
            return $this->fail("請確認輸入資料", 404);
        }

        $shippingEntity = ShippingBusinessLogic::getShipping($o_key);

        if (is_null($shippingEntity) === true) {
            return $this->failForbidden("此運送尚未新增運送資訊。");
        }

        $shippingModel = new shippingModel();

        $result = $shippingModel->updateShippingTransaction($shippingEntity->s_key, $o_key, $status);

        if ($result) {
            return $this->respond([
                "msg" => "OK"
            ]);
        } else {
            return $this->fail("更新失敗", 400);
        }
    }

    /**
     * [DELETE] /api/v1/shipping/{orderKey}
     * 刪除運送資訊
     *
     * @param  string $orderKey
     * @return void
     */
    public function delete(string $orderKey = null)
    {
        if (is_null($orderKey)) {
            return $this->fail("請輸入訂單 key", 400);
        }

        $shippingEntity = ShippingBusinessLogic::getShipping($orderKey);

        if (is_null($shippingEntity) === true) {
            return $this->failForbidden("此運送尚未新增運送資訊。");
        }

        $shippingModel = new shippingModel();

        $result = $shippingModel->deleteShippingTransaction($shippingEntity->s_key, $orderKey);

        if ($result) {
            return $this->respond([
                "msg" => "OK",
                "res" => $result
            ]);
        } else {
            return $this->fail("刪除運送資訊失敗", 400);
        }
    }
}
