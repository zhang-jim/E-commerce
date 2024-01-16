<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Response\VerifiedArrayResponse;

class ecpayLogisticsController extends Controller
{
    public function store(Request $request)
    {
        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
            'hashMethod' => 'md5',
        ]);
        $postService = $factory->create('PostWithCmvEncodedStrResponseService');

        $input = [
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'Test' . time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'LogisticsType' => $request['logisticsType'],
            'LogisticsSubType' => $request['logisticsSubType'],
            'GoodsAmount' => 10000,
            'GoodsName' => '綠界 SDK 範例商品',
            'SenderName' => '陳大明',
            'SenderCellPhone' => '0911222333',
            'ReceiverName' => '王小美',
            'ReceiverCellPhone' => '0933222111',
            // 請參考 example/Logistics/Domestic/GetLogisticStatueResponse.php 範例開發
            'ServerReplyURL' => 'http://localhost/api/logistics-response',
        ];
        switch ($input['LogisticsType']) {
            case 'CVS':
                $input['ReceiverStoreID'] = $request['receiverStoreID'];
                if($request['isCollection'] == "Y"){
                    $input['IsCollection'] = $request['isCollection'];
                }
                break;

            case 'HOME':
                // DB User 管理員資料
                $input['SenderZipCode'] = '11560';
                $input['SenderAddress'] = '台北市南港區三重路19-2號6樓';

                // request {
                $input['ReceiverName'] = '王小美';
                $input['ReceiverCellPhone'] = '0933222111';
                $input['ReceiverZipCode'] = '11560';
                $input['ReceiverAddress'] = '台北市南港區三重路19-2號6樓';
                // }

                switch ($input['LogisticsSubType']) {
                    case 'TCAT':
                        $input['Temperature'] = '0001'; // 溫層 商品自動帶入
                        $input['Specification'] = '0001'; // 規格 商品自動帶入
                        $input['ScheduledPickupTime'] = '4';
                        $input['ScheduledDeliveryTime'] = '2';
                        break;

                    case 'POST':
                        $input['GoodsWeight'] = '19'; // 商品
                        break;

                    default:
                        return response()->json(['error' => '參數錯誤或參數未填寫'], 422);
                }
                break;

            default:
                return response()->json(['error' => '參數錯誤或參數未填寫'], 422);
        }
        $url = 'https://logistics-stage.ecpay.com.tw/Express/Create';
        $response = $postService->post($input, $url);
        var_dump($response);
    }
    public function response() // 回傳建立訂單資訊
    {
        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
            'hashMethod' => 'md5',
        ]);
        $checkoutResponse = $factory->create(VerifiedArrayResponse::class);

        var_dump($checkoutResponse->get($_POST));
    }
}
