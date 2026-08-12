@extends('layouts.admin')

@section('title', 'Quản Lý Thông Báo')

@section('header')
    <div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 mb-6 mt-16 lg:mt-0">
        <div>
            <h1 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">Quản Lý Thông Báo</h1>
            <p class="text-sm text-slate-500">Gửi thông báo đến tất cả người dùng hệ thống</p>
        </div>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl mb-6 font-medium border border-emerald-100 flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-xl"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 lg:p-8 border border-slate-100 dark:border-slate-700 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-3 mb-8 border-b border-slate-100 dark:border-slate-700 pb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-lg">
                        <i class="fa-regular fa-bell"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white font-heading">Gửi Thông Báo Mới</h2>
                </div>

                <form action="{{ route('admin.notifications.send') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6" x-data="{ type: 'promo' }">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3">Loại Thông Báo <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label class="flex-1 cursor-pointer" @click="type = 'promo'">
                                <input type="radio" name="type" value="promo" class="hidden" :checked="type === 'promo'">
                                <div :class="type === 'promo' ? 'border-emerald-500 bg-emerald-50 text-emerald-600' : 'border-slate-100 text-slate-500'" class="p-4 rounded-xl border-2 flex items-center justify-center gap-2 transition-all font-bold">
                                    <i class="fa-solid fa-gift"></i> Khuyến Mãi
                                </div>
                            </label>
                            
                            <label class="flex-1 cursor-pointer" @click="type = 'info'">
                                <input type="radio" name="type" value="info" class="hidden" :checked="type === 'info'">
                                <div :class="type === 'info' ? 'border-blue-500 bg-blue-50 text-blue-600' : 'border-slate-100 text-slate-500'" class="p-4 rounded-xl border-2 flex items-center justify-center gap-2 transition-all font-bold">
                                    <i class="fa-solid fa-circle-info"></i> Hệ Thống
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tiêu Đề <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="VD: Thông báo bảo trì hệ thống..." class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200">
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nội Dung <span class="text-red-500">*</span></label>
                        <textarea name="content" required rows="5" placeholder="Nhập nội dung thông báo muốn gửi đến mọi người..." class="w-full bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary/50 outline-none transition-all text-slate-800 dark:text-slate-200"></textarea>
                    </div>

                    <button type="submit" class="w-full sm:w-auto bg-primary text-white font-bold px-8 py-3.5 rounded-xl shadow-lg shadow-primary/30 hover:bg-secondary transform hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                        <i class="fa-regular fa-paper-plane"></i> Gửi Thông Báo Đến Tất Cả
                    </button>
                </form>
            </div>
        </div>

        <!-- Sidebar Guide -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-100 dark:border-slate-700 shadow-sm">
                <h3 class="font-bold font-heading text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-3">Hướng Dẫn</h3>
                
                <div class="space-y-4">
                    <!-- Promo Guide -->
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 p-4 rounded-xl border border-emerald-100 dark:border-emerald-800/50">
                        <h4 class="font-bold text-emerald-600 dark:text-emerald-400 mb-1 flex items-center gap-2"><i class="fa-solid fa-gift"></i> Thông Báo Khuyến Mãi</h4>
                        <p class="text-sm text-emerald-700 dark:text-emerald-500/80">Dùng để gửi các chương trình ưu đãi, giảm giá, mã voucher hoặc các sự kiện đặc biệt thu hút khách hàng.</p>
                    </div>

                    <!-- Info Guide -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/50">
                        <h4 class="font-bold text-blue-600 dark:text-blue-400 mb-1 flex items-center gap-2"><i class="fa-solid fa-circle-info"></i> Thông Báo Hệ Thống</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-500/80">Dùng để thông báo bảo trì định kỳ, cập nhật tính năng mới, hoặc thông tin quan trọng về sân bóng.</p>
                    </div>

                    <!-- Note Guide -->
                    <div class="bg-slate-50 dark:bg-slate-700/50 p-4 rounded-xl border border-slate-200 dark:border-slate-600 mt-6">
                        <h4 class="font-bold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2"><i class="fa-solid fa-thumbtack text-red-500"></i> Lưu Ý Quan Trọng</h4>
                        <ul class="text-sm text-slate-600 dark:text-slate-400 space-y-2 list-disc list-inside">
                            <li>Thông báo sẽ được gửi đồng loạt đến <strong>TẤT CẢ</strong> người dùng trong hệ thống.</li>
                            <li>Người dùng đang truy cập sẽ nhận được thông báo nhảy lên góc phải màn hình ngay lập tức (Real-time).</li>
                            <li>Người dùng offline sẽ thấy thông báo ở biểu tượng chuông khi họ đăng nhập trở lại.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
