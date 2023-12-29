<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">會員列表</h2>

        <!-- 會員表格 -->
        <table class="min-w-full bg-white border border-gray-300 text-center">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">會員編號</th>
                    <th class="py-2 px-4 border-b">姓名</th>
                    <th class="py-2 px-4 border-b">帳號</th>
                    <th class="py-2 px-4 border-b">電話</th>
                    <th class="py-2 px-4 border-b">電子郵件</th>
                    <th class="py-2 px-4 border-b">帳號狀態</th>
                    <th class="py-2 px-4 border-b">註冊日期</th>
                    <th class="py-2 px-4 border-b">功能</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusLabels = [
                        'active' => '使用中',
                        'inactive' => '停用中',
                        'deleted' => '已刪除',
                    ];
                @endphp
                @foreach ($members as $member)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $member->id }}</td>
                        <td class="py-2 px-4 border-b">{{ $member->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $member->username }}</td>
                        <td class="py-2 px-4 border-b">{{ $member->phone }}</td>
                        <td class="py-2 px-4 border-b">{{ $member->email }}</td>
                        <td class="py-2 px-4 border-b">{{ $statusLabels[$member->status] ?? $member->status }}</td>
                        <td class="py-2 px-4 border-b">{{ $member->created_at->format('Y-m-d') }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ url('member/'. $member->id) }}" class="btn btn-primary">檢視</a>
                            <a href="{{ url('member/'. $member->id .'/edit') }}" class="btn btn-primary">編輯</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
