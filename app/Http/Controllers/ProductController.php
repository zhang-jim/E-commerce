<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    // 抓取所有商品資訊，回傳並導向商品列表頁
    public function index()
    {
        $product = Product::all();
        // dd($product);
        return view('product.index', compact('product'));
    }

    // 導向新增商品頁
    public function create()
    {
        return view('product.create');
    }

    // 新增商品功能
    public function store(Request $request, Product $product)
    {
        // 數據驗證
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric',
            'inventory' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // 填充模型屬性
        $product->fill($validatedData);
        // 處理圖片上傳
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('product_images', $filename, 'public');
            $product->image = $filename;
        }
        // 儲存商品
        $product->save();
        // 回傳新增狀態(成功/失敗)
        return redirect()->back()->with('status', '商品新增成功');
    }

    // 導向檢視商品頁
    public function show(Product $product)
    {
        return view('product.show', compact('product'));
    }

    // 導向商品編輯頁
    public function edit(Product $product)
    {
        return view('product.edit', compact('product'));
    }

    // 更新商品功能，並且檢查圖片是否有更新
    public function update(Request $request, Product $product)
    {
        // 數據驗證
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric',
            'inventory' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        // 填充模型屬性
        $product->fill($validatedData);

        // 是否上架
        $product->is_published = $request->has('is_published');

        // 處理新圖片上傳
        if ($request->hasfile('image')) {
            $oldImagePath = 'storage/product_images/' . $product->image;

            // 刪除舊圖片
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('product_images', $filename, 'public');
            $product->image = $filename;
        }
        $product->update();
        return redirect()->back()->with('status', '商品資訊更新成功');
    }

    // 刪除資料，並且刪除storage儲存庫圖片
    public function destroy(Product $product)
    {
        // 取得圖片的路徑
        $destination = 'storage/product_images/' . $product->image;
        // 刪除圖片檔案
        File::delete($destination);
        // 刪除商品
        $product->delete();
        // 重定向到產品管理頁
        return redirect('product');
    }
}
