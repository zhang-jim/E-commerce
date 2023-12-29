<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">會員資料檢視</h2>
        @php
            $statusLabels = [
                'active' => '使用中',
                'inactive' => '停用中',
                'deleted' => '已刪除',
            ];
        @endphp
        <!-- 會員詳細資訊卡片 -->
        <div class="max-w-md bg-white p-6 rounded-md shadow-md">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600">會員編號:</label>
                <span class="text-lg font-medium">{{ $member->id }}</span>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600">頭像:</label>
                <img src="{{ asset('storage/cutecat.jpg') }}" alt="{{ $member->avatar }}">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600">姓名:</label>
                <span class="text-lg font-medium">{{ $member->name }}</span>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600">帳號:</label>
                <span class="text-lg font-medium">{{ $member->username }}</span>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600">電話:</label>
                <span class="text-lg font-medium">{{ $member->phone }}</span>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600">電子郵件:</label>
                <span class="text-lg font-medium">{{ $member->email }}</span>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600">地址:</label>
                <span class="text-lg font-medium">{{ $member->address }}</span>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600">帳號狀態:</label>
                <span class="text-lg font-medium">{{ $statusLabels[$member->status] ?? $member->status }}</span>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600">註冊日期:</label>
                <span class="text-lg font-medium">{{ $member->created_at->format('Y-m-d') }}</span>
            </div>
        </div>

        <!-- 返回按鈕 -->
        <div class="mt-4">
            <a href="{{ route('member.index') }}" class="text-blue-500 hover:underline">返回會員列表</a>
        </div>
    </div>
</x-app-layout>