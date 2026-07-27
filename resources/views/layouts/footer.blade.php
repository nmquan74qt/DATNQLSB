<footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 pt-16 pb-8 relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full filter blur-3xl translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary/5 rounded-full filter blur-3xl -translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-6 group">
                    <div class="w-10 h-10 bg-gradient-to-tr from-primary to-blue-400 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary/30 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-futbol text-xl"></i>
                    </div>
                    <span class="font-heading font-extrabold text-2xl tracking-tight text-slate-900 dark:text-white">
                        Pitch<span class="text-primary">Manage</span>
                    </span>
                </a>
                <p class="text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
                    Nền tảng đặt sân bóng đá hàng đầu, cung cấp trải nghiệm chuyên nghiệp, nhanh chóng và minh bạch nhất cho cộng đồng đam mê thể thao.
                </p>
                <div class="flex space-x-4">
                    <a href="javascript:void(0);" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="javascript:void(0);" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="javascript:void(0);" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 hover:bg-primary hover:text-white transition-all transform hover:-translate-y-1">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                </div>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-6 uppercase tracking-wider">Khám Phá</h4>
                <ul class="space-y-4">
                    <li><a href="{{ route('fields.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Đặt Sân Ngay</a></li>
                    <li><a href="{{ route('fields.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Bảng Giá Dịch Vụ</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Tin Tức & Khuyến Mãi</a></li>
                    <li><a href="javascript:void(0);" class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Đối Tác Của Chúng Tôi</a></li>
                </ul>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-6 uppercase tracking-wider">Hỗ Trợ</h4>
                <ul class="space-y-4">
                    <li><a href="javascript:void(0);" class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Trung Tâm Trợ Giúp</a></li>
                    <li><a href="javascript:void(0);" class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Điều Khoản Dịch Vụ</a></li>
                    <li><a href="javascript:void(0);" class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Chính Sách Bảo Mật</a></li>
                    <li><a href="javascript:void(0);" class="text-slate-500 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors flex items-center"><i class="fa-solid fa-chevron-right text-xs mr-2 text-primary"></i> Quy Định Hủy Sân</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="font-heading font-bold text-lg text-slate-900 dark:text-white mb-6 uppercase tracking-wider">Liên Hệ</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-primary flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <span class="text-slate-500 dark:text-slate-400 text-sm">123 Đường X, Quận Y, TP. Hồ Chí Minh</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-500 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <span class="text-slate-500 dark:text-slate-400 text-sm font-bold text-slate-700 dark:text-slate-300">0901.234.567</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-500 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <span class="text-slate-500 dark:text-slate-400 text-sm">support@pitchmanage.com</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-slate-500 dark:text-slate-500 text-sm">
                &copy; {{ date('Y') }} PitchManage. Bản quyền thuộc về Senior Full Stack Dev.
            </p>
            <div class="flex items-center gap-2">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/VNPay_Logo.svg/512px-VNPay_Logo.svg.png" class="h-6 grayscale hover:grayscale-0 transition-all cursor-pointer bg-white rounded px-1" alt="VNPay">
                <img src="https://upload.wikimedia.org/wikipedia/vi/f/fe/MoMo_Logo.png" class="h-6 grayscale hover:grayscale-0 transition-all cursor-pointer" alt="MoMo">
            </div>
        </div>
    </div>
</footer>
