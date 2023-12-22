<script>
    function deleteProduct(url) {
        if (confirm('您確定要刪除該產品嗎？')) {
            // 使用 XMLHttpRequest 或者其他適當的方式發送 DELETE 請求
            var xhr = new XMLHttpRequest();
            xhr.open('DELETE', url, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}'); // 如果你的應用使用 CSRF，請確保將 CSRF token 放在請求頭中
            xhr.onload = function() {
                // 處理請求完成後的邏輯
                if (xhr.status === 200) {
                    // 成功刪除
                } else {
                    // 處理錯誤情況
                }
            };
            xhr.send();
        }
    }
</script>
<x-app-layout>
    <div class="w-4/5 mx-auto p-4 sm:p-6 lg:p-8">
        <div class="card">
            <div class="card-header">
                <h1 class="text-3xl font-bold mb-4">商品列表
                    <a href="{{ url('product/create') }}" class="text-blue-500 ml-2">新增商品</a>
                </h1>
            </div>
            <div class="card-body">
                <table class="text-center w-full bg-white border border-gray-300">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">商品圖片</th>
                            <th class="py-2 px-4 border-b">商品名稱</th>
                            <th class="py-2 px-4 border-b">售價</th>
                            <th class="py-2 px-4 border-b">庫存</th>
                            <th class="py-2 px-4 border-b">上下架狀態</th>
                            <th class="py-2 px-4 border-b">功能</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product as $item)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $item->id }}</td>
                                <td class="py-2 px-4 border-b">
                                    <img class="rounded w-20 mx-auto"
                                        src="{{ asset('storage/product_images/' . $item->image) }}" alt="">
                                </td>
                                <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $item->price }}</td>
                                <td class="py-2 px-4 border-b">{{ $item->inventory }}</td>
                                <td class="py-2 px-4 border-b">
                                    @if($item->is_published)
                                        <span class="text-green-500">上架中</span>
                                    @else
                                        <span class="text-red-500">已下架</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b">
                                    <a href="{{ url('product/' . $item->id) }}" class="text-blue-500 mr-2">檢視</a>
                                    <a href="{{ url('product/' . $item->id . '/edit') }}"
                                        class="text-green-500 mr-2">編輯</a>
                                    <a href="" onclick="deleteProduct('{{ url('product/' . $item->id) }}')"
                                        class="text-red-500">刪除</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

