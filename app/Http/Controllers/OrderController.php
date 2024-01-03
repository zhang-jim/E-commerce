<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Member;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $products = $order->products;
        return view('orders.show', compact('order', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
