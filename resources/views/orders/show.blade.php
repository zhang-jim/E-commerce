<x-app-layout>
    <div class="container">
        <h2>訂單詳細資訊</h2>

        <div class="card">
            <div class="card-header">
                訂單編號: {{ $order->order_number }}
            </div>
            <div class="card-body">
                <p>顧客姓名: {{ $order->member->name }}</p>
                <ul>
                    購買商品：
                    @foreach ($products as $product)
                        <li>-{{ $product->name }}</li>
                    @endforeach
                </ul>
                <p>付款狀態: {{ $order->payment_status }}</p>
                <p>訂單狀態: {{ $order->order_status }}</p>
                <p>總價: {{ $order->order_amount }}</p>
                <p>備註: {{ $order->notes }}</p>
            </div>
        </div>

        <a href="{{ url('order') }}" class="btn btn-primary mt-3">返回列表</a>
    </div>
</x-app-layout>
