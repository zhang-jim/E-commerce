<script>
    function deleteCategory(url) {
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
    <div class="w-4/5 mx-auto mt-5 flex justify-between">
        <div class="w-4/12">
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif
            <div class="">
                <div class="w-full bg-gray-200 p-4 mb-3">
                    <h1 class="text-2xl font-bold">新增分類</h1>
                </div>
                <div class="">
                    <form action="{{ url('category') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">分類名稱</label>
                            <input type="text" name="name" class="w-4/5 form-input rounded-md shadow-sm">
                        </div>
                        <div class="mb-4">
                            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">Slug</label>
                            <input type="text" name="slug" class="w-4/5 form-input rounded-md shadow-sm">
                        </div>
                        <div class="mb-4">
                            <label for="parent_id" class="block text-gray-700 text-sm font-bold mb-2">上層分類</label>
                            <select name="parent_id" class="w-4/5 form-select rounded-md shadow-sm">
                                <option value="" selected>無</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">描述</label>
                            <textarea name="description" rows="3" class="w-4/5 form-input rounded-md shadow-sm"></textarea>
                        </div>
                        <div class="mt-4">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                儲存
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- 分類列表 --}}
        <div class="w-3/5">
            <div class="">
                <div class="bg-gray-200 p-4">
                    <h1 class="text-2xl font-bold">所有分類</h1>
                </div>
                <div class="">
                    <table class="w-full text-center border-collapse border border-gray-300">
                        <thead>
                            <tr>
                                <th class="p-3 border-b">分類名稱</th>
                                <th class="p-3 border-b">內容說明</th>
                                <th class="p-3 border-b">上層分類</th>
                                <th class="p-3 border-b">功能</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="p-3 border-b font-semibold">{{ $category->name }}</td>
                                    <td class="p-3 border-b text-gray-500">{{ $category->description }}</td>
                                    <td class="p-3 border-b text-gray-500">{{ $category->parent_id }}</td>
                                    <td class="p-3 border-b">
                                        <a href="{{ url('category/' . $category->id .'/edit') }}" class="text-green-500 mr-2">編輯</a>
                                        <a href="" onclick="deleteCategory('{{ url('category/' . $category->id) }}')" class="text-red-500">刪除</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
