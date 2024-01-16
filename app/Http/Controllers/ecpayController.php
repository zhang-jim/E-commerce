<?php

namespace App\Http\Controllers;

use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Ecpay\Sdk\Services\UrlService;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Response\VerifiedArrayResponse;

// $input['CreditInstallment'] = '3,6,12,18,24'; //分期期數
// $input['UnionPay'] = 2; //關閉銀聯卡

class EcpayController extends Controller
{
    public function store(Request $request, Order $order)
    {
        // 開始事務
        DB::beginTransaction();
        try {
            // 驗證請求數據
            $validatedData = $request->validate([
                'member_id' => 'required|numeric',
                'order_amount' => 'required|numeric',
                'notes' => 'nullable|string',
                'productIds' => 'required|array',
                'productIds.*' => 'required|numeric',
            ]);

            // 生成訂單編號
            $merchantTradeNo = 'Test' . time();
            $validatedData['order_number'] = $merchantTradeNo;

            // 填充訂單數據
            $order->fill($validatedData);

            // 儲存訂單
            $saved = $order->save();

            if (!$saved) {
                // 回滾事務，並返回錯誤響應
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => '訂單建立失敗']);
            }

            // 取得訂單編號
            $orderNumber = $order->id;

            // 新增到中介表
            $order->products()->attach($request['productIds'], ['order_id' => $orderNumber]);

            // 提交事務
            DB::commit();

            // 獲得商品名稱及計算數量
            $itemNames = $this->getItemNames($request['productIds']);

            // 生成 ECPay 表單
            $this->generateEcpayForm($merchantTradeNo, $request['order_amount'], $itemNames);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => '發生異常：' . $e->getMessage()]);
        }
    }

    // 獲取商品名稱及計算數量
    private function getItemNames($productIds)
    {
        $itemName = '';
        $productName = DB::table('products')->whereIn('id', $productIds)->pluck('name', 'id');
        $itemNames = [];

        // 迴圈取得商品ID對應的名稱，存進itemNames Array
        foreach ($productIds as $productId) {
            $itemNames[] = $productName[$productId];
        }
        // 計數itemNames Array 每個值出現的次數
        $counts = array_count_values($itemNames);

        // 轉換為ecpay要求的商品格式
        foreach ($counts as $key => $value) {
            $itemName .= $key . ' * ' . $value . '#';
        }
        return $itemName;
    }

    // 生成 ECPay 表單
    private function generateEcpayForm($merchantTradeNo, $totalAmount, $itemNames)
    {
        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
        ]);

        $choosePayment = 'ALL'; //ALL, Credit, ApplePay, ATM

        $input = [
            'MerchantID' => '2000132', //商店編號
            'MerchantTradeNo' => $merchantTradeNo,
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => $totalAmount,
            'TradeDesc' => UrlService::ecpayUrlEncode('購買商品'),
            'ItemName' => $itemNames,
            'ChoosePayment' => $choosePayment,
            'EncryptType' => 1,
            'ClientBackURL' => 'http://localhost',
            'ReturnURL' => 'http://localhost/receive',
        ];

        $autoSubmitFormService = $factory->create('AutoSubmitFormWithCmvService');
        $action = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';

        echo $autoSubmitFormService->generate($input, $action);
    }

    public function receive(Request $request)
    {
        try {
            $factory = new Factory([
                'hashKey' => '5294y06JbISpM5x9',
                'hashIv' => 'v77hoKGq4kWxNNIS',
            ]);
            $checkoutResponse = $factory->create(VerifiedArrayResponse::class);

            // 接收 POST 參數
            $postData = $request->all();

            // 驗證回傳參數
            $verificationResult = $checkoutResponse->get($postData);

            // 檢查驗證結果
            if ($verificationResult) {

                $orderNumber = $postData['MerchantTradeNo'];
                $order = Order::where('order_number', $orderNumber)->first();

                if ($order) {
                    // 更新訂單狀態為已支付
                    $order->update(['payment_status' => 'paid']);
                }

                // 回應綠界，告知已經成功接收通知
                return '1|OK';
            } else {
                // 驗證失敗，可能是不合法的請求

                // 在這裡你可以記錄日誌或其他處理方式
                Log::error('Invalid ECPay notification request.');
                return '0|Fail';
            }
        } catch (\Exception $e) {
            // 在這裡你可以記錄日誌或其他處理方式
            Log::error('Exception occurred during ECPay notification processing: ' . $e->getMessage());
            return '0|Fail';
        }
    }
}
