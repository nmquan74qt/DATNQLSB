@extends('layouts.admin')

@section('header')
<div class="flex justify-between items-center bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 mb-6 mt-16 lg:mt-0">
    <div>
        <h1 class="text-2xl font-bold font-heading text-slate-900 dark:text-white">Cài Đặt Hệ Thống</h1>
        <p class="text-sm text-slate-500">Super Admin Panel - Security & SEO</p>
    </div>
</div>
@endsection

@section('content')

@if(session('error'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof showNotification === 'function') {
            showNotification("{{ session('error') }}", "warning");
        } else {
            Swal.fire('Lỗi', "{{ session('error') }}", 'error');
        }
    });
</script>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Cấu hình hệ thống -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative">
        <h3 class="font-bold font-heading text-lg mb-6"><i class="fa-solid fa-sliders text-primary"></i> Cấu hình chung (SEO & UI)</h3>
        
        <form action="{{ route('admin.system.settings') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <div>
                        <h4 class="font-bold text-sm">Chế độ bảo trì (Maintenance Mode)</h4>
                        <p class="text-xs text-slate-500">Tạm ngưng toàn bộ website để nâng cấp hệ thống.</p>
                    </div>
                    <!-- Toggle switch -->
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="maintenance_mode" value="0">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ (isset($settings['maintenance_mode']) && $settings['maintenance_mode'] == '1') ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <div>
                        <h4 class="font-bold text-sm">Lazy Load Hình Ảnh (Tối ưu điểm Lighthouse)</h4>
                        <p class="text-xs text-slate-500">Tự động trì hoãn tải hình ảnh ngoài màn hình.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="lazy_load" value="0">
                        <input type="checkbox" name="lazy_load" value="1" {{ (isset($settings['lazy_load']) && $settings['lazy_load'] == '1') ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl">
                    <div>
                        <h4 class="font-bold text-sm">Tên Website</h4>
                        <p class="text-xs text-slate-500">Tên hiển thị trên thanh tiêu đề và SEO.</p>
                    </div>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'PitchManage' }}" class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-3 py-1.5 text-sm w-48 focus:ring-2 focus:ring-primary focus:outline-none">
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-primary hover:bg-blue-600 text-white font-bold px-6 py-2.5 rounded-xl shadow-md transition-all">Lưu Cấu Hình</button>
            </div>
        </form>
    </div>

    <!-- Data & Security -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 shadow-sm relative">
        <h3 class="font-bold font-heading text-lg mb-6"><i class="fa-solid fa-database text-amber-500"></i> Dữ Liệu & Bảo Mật (Backup)</h3>
        
        <div class="p-6 bg-amber-50 dark:bg-amber-500/10 rounded-xl border border-amber-200 dark:border-amber-500/30 text-center relative overflow-hidden group">
            <!-- Alert glow background -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-amber-200/50 dark:via-amber-500/20 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]"></div>
            
            <i class="fa-solid fa-shield-halved text-4xl text-amber-500 mb-4 drop-shadow-md"></i>
            <h4 class="font-bold text-amber-900 dark:text-amber-500 mb-2">Sao lưu Toàn bộ Database (MySQL)</h4>
            <p class="text-sm text-amber-700/80 dark:text-amber-500/80 mb-6">Chức năng này sẽ gọi lệnh hệ thống (mysqldump) để xuất toàn bộ dữ liệu ra tệp .sql và tải xuống ngay lập tức.</p>
            
            <form action="{{ route('admin.system.backup') }}" method="POST">
                @csrf
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition-transform hover:scale-105 inline-flex items-center gap-2 relative z-10">
                    <i class="fa-solid fa-cloud-arrow-down"></i> Download Bản Sao Lưu (.sql)
                </button>
            </form>
        </div>
    </div>

</div>

<style>
@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(200%); }
}
</style>
@endsection
