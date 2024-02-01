<?php

namespace App\Http\Controllers;

use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Response\ArrayResponse;

class ecpayMapController extends Controller
{
    public function index() //綠界地圖
    {
        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
            'hashMethod' => 'md5',
        ]);
        $autoSubmitFormService = $factory->create('AutoSubmitFormWithCmvService');

        $input = [
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'Test' . time(),
            'LogisticsType' => 'CVS',
            'LogisticsSubType' => 'UNIMART', //可變動值

            // 請參考 example/Logistics/Domestic/GetMapResponse.php 範例開發
            'ServerReplyURL' => 'http://localhost/api/map-response',
        ];
        $action = 'https://logistics-stage.ecpay.com.tw/Express/map';

        echo $autoSubmitFormService->generate($input, $action);
    }
    public function response() // 回傳選擇店面資訊
    {
        $factory = new Factory();
        $response = $factory->create(ArrayResponse::class);

        return redirect()->route('checkout')->with(['response' => $response->get($_POST)]);
    }
}
