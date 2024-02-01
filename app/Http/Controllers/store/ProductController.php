<?php

namespace App\Http\Controllers\store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;

class ProductController extends Controller
{
    // 抓取所有商品資訊，回傳並導向商品列表頁
    public function index()
    {
        $product = Product::all(['id','name','description','price','image']);
        return response()->json($product);
    }

    // 導向檢視商品頁
    public function show(Product $product)
    {
        $data = $product->only(['name','description','price','image']);
        return response()->json($data);
    }
}
