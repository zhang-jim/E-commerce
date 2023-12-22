<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-gray-200 p-4">
                    <h1 class="text-2xl font-bold">詳細內容
                        <a href="{{ url('product') }}" class="text-blue-500 ml-4">Back</a>
                    </h1>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="text-gray-600 font-semibold">商品名稱:</label>
                            <p class="text-gray-800">{{ $product->name }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="text-gray-600 font-semibold">商品描述:</label>
                            <p class="text-gray-800">{{ $product->description }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="text-gray-600 font-semibold">售價:</label>
                            <p class="text-gray-800">{{ $product->price }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="text-gray-600 font-semibold">庫存:</label>
                            <p class="text-gray-800">{{ $product->inventory }}</p>
                        </div>
                        <div class="mb-4">
                            <label class="text-gray-600 font-semibold">上下架狀態:</label>
                            <p class="text-gray-800">
                                @if ($product->is_published)
                                    <span class="text-green-600">上架</span>
                                @else
                                    <span class="text-red-600">下架</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="mt-8">
                        <label class="text-gray-600 font-semibold">商品圖:</label>
                        <img src="{{ asset('storage/product_images/' . $product->image) }}" alt=""
                            class="mt-2" width="200px" height="200px">
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
