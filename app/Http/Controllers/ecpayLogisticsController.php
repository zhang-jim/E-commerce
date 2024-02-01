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
            'hashKey' => $request['hashKey'],
            'hashIv' => $request['hashIv'],
            'hashMethod' => 'md5',
        ]);
        $postService = $factory->create('PostWithCmvEncodedStrResponseService');

        $input = [
            'MerchantID' => $request['MerchantID'],
            'MerchantTradeNo' => 'Test' . time(),
            'MerchantTradeDate' => date('Y/m/d H:i:s'),
            'LogisticsType' => $request['logisticsType'],
            'LogisticsSubType' => $request['logisticsSubType'],
            'GoodsAmount' => 20000,
            'GoodsName' => '商品',
            'SenderName' => '陳大明',
            'SenderCellPhone' => '0911222333',
            'ReceiverName' => $request['receiverName'],
            'ReceiverCellPhone' => $request['receiverCellPhone'],
            'ServerReplyURL' => 'http://localhost/api/logistics-response',
        ];
        switch ($input['LogisticsType']) {
            case 'CVS':
                $input['ReceiverStoreID'] = $request['receiverStoreID'];
                if ($request['isCollection'] == "Y") {
                    $input['IsCollection'] = $request['isCollection'];
                }
                break;

            case 'HOME':
                // DB User 管理員資料
                $input['SenderZipCode'] = '11560';
                $input['SenderAddress'] = '台北市南港區三重路19-2號6樓';

                // request {
                $input['ReceiverZipCode'] = $request['receiverZipCode'];
                $input['ReceiverAddress'] = $request['receiverAddress'];
                // }

                switch ($input['LogisticsSubType']) {
                    case 'TCAT':
                        $input['Temperature'] = '0001'; // 溫層 商品自動帶入
                        $input['Specification'] = '0001'; // 規格 商品自動帶入
                        $input['ScheduledPickupTime'] = '4';
                        $input['ScheduledDeliveryTime'] = '2';
                        break;

                    case 'POST':
                        $input['GoodsWeight'] = '19'; // 商品公斤重
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
        return response()->json($response);
    }
    //異動物流訂單 
    public function updateShipmentInfo()
    {
        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
            'hashMethod' => 'md5',
        ]);
        $postService = $factory->create('PostWithCmvStrResponseService');

        $input = [
            'MerchantID' => '2000132',
            'AllPayLogisticsID' => '2412722',
            'ShipmentDate' => '2024-01-17 17:51:02',
            'ReceiverStoreID' => '1',
        ];
        $url = 'https://logistics-stage.ecpay.com.tw/Helper/UpdateShipmentInfo';

        $response = $postService->post($input, $url);
        var_dump($response);
    }
    // 回傳建立物流單資訊
    public function response()
    {
        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
            'hashMethod' => 'md5',
        ]);
        $checkoutResponse = $factory->create(VerifiedArrayResponse::class)->get($_POST);

        var_dump($checkoutResponse['MerchantTradeNo'], $checkoutResponse['RtnCode']);
        // 查找該筆訂單，編輯物流狀態
    }
    //B2C 逆物流訂單
    public function B2CCancel(Request $request) 
    {
        // ReturnUniMartCVS 7-11
        // ReturnHilifeCVS 萊爾富
        // ReturnCVS 全家

        // 使用者在登入後查看訂單紀錄，
        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
            'hashMethod' => 'md5',
        ]);
        $postService = $factory->create('PostWithCmvStrResponseService');

        $input = [
            'MerchantID' => '2000132',
            'AllPayLogisticsID' => '2413741',
            'GoodsAmount' => 20000,
            'ServiceType' => '4',
            'SenderName' => '陳大明',

            // 請參考 example/Logistics/Domestic/GetReturnResponse.php 範例開發
            'ServerReplyURL' => 'https://www.ecpay.com.tw/example/server-reply',
        ];

        $input["SenderPhone"] = '0955666444';
        $url = 'https://logistics-stage.ecpay.com.tw/express/ReturnCVS';

        $response = $postService->post($input, $url);
        var_dump($response); 
    }

    // public function C2CCancel(Request $request){
    //     $factory = new Factory([
    //         'hashKey' => 'XBERn1YOvpM9nfZc',
    //         'hashIv' => 'h1ONHk4P4yqbl5LK',
    //         'hashMethod' => 'md5',
    //     ]);
    //     $postService = $factory->create('PostWithCmvStrResponseService');
        
    //     $input = [
    //         'MerchantID' => '2000933',
    //         'AllPayLogisticsID' => $request['AllPayLogisticsID'],
    //         'CVSPaymentNo' => $request['CVSPaymentNo'],
    //         'CVSValidationNo' => $request['CVSValidationNo'],
    //     ];
    //     $url = 'https://logistics-stage.ecpay.com.tw/Express/CancelC2COrder';
        
    //     $response = $postService->post($input, $url);
    //     var_dump($response);     
    // }
    // 查詢訂單
    public function queryLogisticsTradeInfo()
    {
        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
            'hashMethod' => 'md5',
        ]);
        $postService = $factory->create('PostWithCmvVerifiedEncodedStrResponseService');

        $input = [
            'MerchantID' => '2000132',
            // 'MerchantTradeNo' => 'Test1705551111',
            'AllPayLogisticsID' => '2412791',
            'TimeStamp' => time(),
        ];
        $url = 'https://logistics-stage.ecpay.com.tw/Helper/QueryLogisticsTradeInfo/V4';

        $response = $postService->post($input, $url);
        var_dump($response);
    }
}
