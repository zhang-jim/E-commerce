<x-app-layout>
    <div class="container">
        <h2>訂單管理</h2>
        <a href="{{ url('order/create') }}" class="text-blue-500 ml-2">新增商品</a>
        <table class="table">
            <thead>
                <tr>
                    <th>訂單編號</th>
                    <th>訂單日期</th>
                    <th>顧客</th>
                    <th>付款狀態</th>
                    <th>訂單狀態</th>
                    <th>總金額</th>
                    <th>備註</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td> 
                        <td>{{ $order->created_at }}</td> 
                        <td>{{ $order->member->name }}</td>
                        <td>{{ $order->payment_status }}</td>
                        <td>{{ $order->order_status }}</td>
                        <td>{{ $order->order_amount }}</td>
                        <td>{{ $order->notes }}</td>
                        <td>
                            <!-- 添加編輯和刪除按鈕的連結 -->
                            <a href="{{ url('order/'. $order->id) }}" class="btn btn-primary">檢視</a>
                            <a href="{{ url('order/'. $order->id .'/edit') }}" class="btn btn-primary">編輯</a>
                            {{-- <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('確定要刪除嗎？')">刪除</button>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
