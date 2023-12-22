<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="col-md-12">

            @if (session('status'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif

            <div class="card">
                <div class="card-header bg-gray-200 p-4 flex justify-between items-center">
                    <h1 class="text-2xl font-bold">編輯商品</h1>
                    <a href="{{ url('product') }}" class="text-blue-500 ml-4">Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ url('product', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">商品名稱</label>
                            <input value="{{ $product->name }}" type="text" name="name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <!-- Toggleable Checkbox -->
                        <div class="mb-4">
                            <label for="is_published" class="block text-gray-700 text-sm font-bold mb-2">上下架狀態</label>
                            <div
                                class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="is_published" id="is_published"
                                    class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                    @if ($product->is_published) checked @endif>
                                <label for="is_published"
                                    class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                            </div>
                        </div>
                        <!-- End Toggleable Checkbox -->

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">商品描述</label>
                            <input value="{{ $product->description }}" type="text" name="description"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">售價</label>
                            <input value="{{ $product->price }}" type="number" name="price"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="inventory" class="block text-gray-700 text-sm font-bold mb-2">庫存</label>
                            <input value="{{ $product->inventory }}" type="number" name="inventory"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">商品圖</label>
                            <input type="file" name="image"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <img src="{{ asset('storage/product_images/' . $product->image) }}" alt=""
                                class="mt-2 w-4/6 mx-auto">
                        </div>
                        <div>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
    <script>
        const toggleCheckbox = document.getElementById('is_online');
        toggleCheckbox.addEventListener('change', function() {
            if (this.checked) {
                this.nextElementSibling.style.backgroundColor = '#48BB78';
            } else {
                this.nextElementSibling.style.backgroundColor = '#718096';
            }
        });
    </script>
@endpush
