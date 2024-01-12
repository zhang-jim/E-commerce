<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Ecpay\Sdk\Factories\Factory;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        // return view('orders.index', compact('orders'));
        printf($orders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::select('username', 'name', 'phone', 'email')->get();
        $products = Product::select('id', 'name', 'price', 'image', 'description')->get();
        // dd($members);
        return view('orders.create', compact('members', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Order $order)
    {
        // 開始事務
        DB::beginTransaction();

        try {
            // 數據驗證
            $validatedData = $request->validate([
                'order_number' => 'required|string|max:255|unique:orders',
                'member_id' => 'required|numeric',
                'order_amount' => 'required|numeric',
                'notes' => 'nullable|string',
                'productIds' => 'required|array',
                'productIds.*' => 'required|numeric',
            ]);

            // 建立訂單
            $order->fill($validatedData);
            $saved = $order->save();

            if (!$saved) {
                // 回滾事務，並返回錯誤響應
                DB::rollBack();
                return response()->json(['status' => 'error', 'message' => '訂單建立失敗']);
            }

            // 取得訂單編號
            $orderNumber = $order->id;

            // 取得商品ID陣列
            $productIds = $validatedData['productIds'];

            // 新增到中介表
            $order->products()->attach($productIds, ['order_id' => $orderNumber]);

            // 提交事務
            DB::commit();

            // 返回成功響應
            return response()->json(['status' => 'success', 'message' => '訂單建立成功']);

        } catch (\Exception $e) {
            // 發生異常，回滾事務，並返回錯誤響應
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => '發生異常：' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $factory = new Factory([
            'hashKey' => '5294y06JbISpM5x9',
            'hashIv' => 'v77hoKGq4kWxNNIS',
        ]);
        $postService = $factory->create('PostWithCmvVerifiedEncodedStrResponseService');

        $input = [
            'MerchantID' => '2000132',
            'MerchantTradeNo' => 'Test1704694620',
            'TimeStamp' => time(),
        ];
        $url = 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V5';

        $response = $postService->post($input, $url);
        return response()->json($response);
        // $products = $order->products;
        // return view('orders.show', compact('order', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // 數據驗證
        $validatedData = $request->validate([
            'member_id' => 'required|numeric',
            'order_amount' => 'required|numeric',
            'notes' => 'nullable|string',
            'productIds' => 'required|array',
            'productIds.*' => 'required|numeric',
        ]);

        // 使用 fill 方法填充模型屬性
        $order->fill($validatedData);

        // 判斷模型是否有未保存的變更
        if ($order->isDirty()) {
            // 有未保存的變更，執行更新
            $order->update();
        }

        // 獲取現有訂單的商品ID
        $existingProductIds = $order->products->pluck('id')->toArray();

        // 找出要新增的商品ID
        $newProductIds = array_diff($validatedData['productIds'], $existingProductIds);

        // 找出要刪除的商品ID
        $deletedProductIds = array_diff($existingProductIds, $validatedData['productIds']);

        // 新增商品到 order_product 表
        $order->products()->attach($newProductIds);

        // 移除不再屬於指定商品ID集的項目
        $order->products()->detach($deletedProductIds);

        return response()->json(['status' => 'success', 'message' => '更新成功']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
