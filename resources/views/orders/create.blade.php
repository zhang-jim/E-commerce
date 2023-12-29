<x-app-layout>
    <div class="container mx-auto p-4 flex">
        <!-- 左側部分 -->
        <div class="w-1/2 p-4">
            <form method="POST" action="{{ url('orders') }}" class="max-w-md">
                @csrf
                <div class="mb-4">
                    <label for="customer_option" class="block text-sm font-semibold text-gray-600 mb-1">顧客選項:</label>
                    <div class="flex">
                        <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                            onclick="showOptions('customerOptions')">選擇會員</button>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="product_option" class="block text-sm font-semibold text-gray-600 mb-1">商品選項:</label>
                    <div class="flex">
                        <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                            onclick="showOptions('productOptions')">選擇商品</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 右側部分 -->
        <div class="w-1/2 p-4">
            <h2 class="text-2xl font-bold mb-4">訂單資訊</h2>

            <!-- 這裡添加小計、運費、合計等資訊 -->
            <div class="mb-4">
                <label for="subtotal" class="block text-sm font-semibold text-gray-600 mb-1">小計:</label>
                <span id="subtotal">$0</span>
            </div>

            <div class="mb-4">
                <label for="shipping" class="block text-sm font-semibold text-gray-600 mb-1">運費:</label>
                <span id="shipping">$0</span>
            </div>

            <div class="mb-4">
                <label for="total" class="block text-sm font-semibold text-gray-600 mb-1">合計:</label>
                <span id="total">$0</span>
            </div>

            <!-- 建立訂單按鈕 -->
            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">建立訂單</button>
        </div>
    </div>

    <!-- 會員選擇的彈跳視窗 -->
    <div id="customerOptions"
        class="fixed top-0 left-0 w-full h-full items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-4 w-6/12 mx-auto">
            <!-- 右上角關閉按鈕 -->
            <button class="float-right text-gray-600 hover:text-black"
                onclick="closeOptions('customerOptions')">❌</button>
            <p class="mb-4 text-lg font-semibold">選擇會員：</p>

            <!-- 搜尋框 -->
            <input type="text" class="form-input w-full mb-4" placeholder="搜尋會員..." id="memberSearch"
                oninput="searchMembers()">

            <!-- 會員選擇框 -->
            <table class="w-full text-center mx-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">選擇</th>
                        <th class="px-4 py-2">名稱</th>
                        <th class="px-4 py-2">電話</th>
                        <th class="px-4 py-2">Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                        <tr>
                            <td class="px-4 py-2">
                                <input type="radio" name="memberSelect" value="{{ $member->username }}">
                            </td>
                            <td class="px-4 py-2">{{ $member->name }}</td>
                            <td class="px-4 py-2">{{ $member->phone }}</td>
                            <td class="px-4 py-2">{{ $member->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- 儲存按鈕 -->
            <button class="bg-blue-500 text-white px-4 py-2 rounded mt-4 hover:bg-blue-600"
                onclick="saveMember()">儲存</button>
        </div>
    </div>

    <div id="productOptions"
        class="fixed top-0 left-0 w-full h-full items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-4 w-6/12 mx-auto">
            <!-- 新增右上角關閉按鈕 -->
            <button class="float-right text-gray-600 hover:text-black"
                onclick="closeOptions('productOptions')">❌</button>
            <p class="mb-4 text-lg font-semibold">選擇商品：</p>

            <!-- 搜尋框 -->
            <input type="text" class="form-input w-full mb-4" placeholder="搜尋商品..." id="productSearch"
                oninput="searchProducts()">

            <!-- 商品選擇框，改為使用 div 呈現每個產品 -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($products as $product)
                    <div class="bg-white p-2 border rounded">
                        <!-- 顯示產品圖片 -->
                        <img src="{{ asset('/storage/product_images/' . $product->image) }}"
                            alt="{{ $product->description }}" class="w-full object-cover mb-2">
                        <!-- 顯示產品名稱 -->
                        <p class="text-sm font-semibold">{{ $product->name }}</p>
                        <p class="text-sm font-semibold">售價：{{ $product->price }}</p>
                        <!-- 選擇按鈕 -->
                    </div>
                @endforeach
            </div>

            <!-- 關閉按鈕 -->
            <button class="bg-blue-500 text-white px-4 py-2 rounded mt-4 hover:bg-blue-600"
                onclick="saveProduct()">確定</button>
        </div>
    </div>
</x-app-layout>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // 顯示彈跳視窗
    function showOptions(value) {
        document.getElementById(value).style.display = 'flex';
    }

    // 關閉彈跳視窗
    function closeOptions(value) {
        document.getElementById(value).style.display = 'none';
    }

    // JavaScript 函式處理會員搜尋邏輯
    function searchMembers() {
        var input, filter, select, option, i;
        input = document.getElementById("memberSearch");
        filter = input.value.toUpperCase();
        select = document.getElementById("memberSelect");
        option = select.getElementsByTagName("option");

        for (i = 0; i < option.length; i++) {
            txtValue = option[i].textContent || option[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                option[i].style.display = "";
            } else {
                option[i].style.display = "none";
            }
        }
    }

    // JavaScript 函式處理商品搜尋邏輯
    function searchProducts() {
        var input, filter, select, option, i;
        input = document.getElementById("productSearch");
        filter = input.value.toUpperCase();
        select = document.getElementById("productSelect");
        option = select.getElementsByTagName("option");

        for (i = 0; i < option.length; i++) {
            txtValue = option[i].textContent || option[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                option[i].style.display = "";
            } else {
                option[i].style.display = "none";
            }
        }
    }
</script>
