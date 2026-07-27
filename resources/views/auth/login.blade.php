<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - PitchManage</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .floating-input:focus ~ label,
        .floating-input:not(:placeholder-shown) ~ label {
            transform: translateY(-130%) scale(0.85);
            color: #10b981; /* emerald-500 */
        }
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden" x-data>

    <!-- Decorative Background -->
    <div class="absolute top-0 w-full h-96 bg-gradient-to-b from-emerald-500/10 to-transparent -z-10"></div>
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-emerald-400/20 rounded-full blur-3xl -z-10"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-400/10 rounded-full blur-3xl -z-10"></div>

    <div class="w-full max-w-5xl flex rounded-3xl overflow-hidden shadow-2xl bg-white animate-fade-up border border-slate-100">
        
        <!-- Left Side: Image / Branding -->
        <div class="hidden lg:block lg:w-1/2 relative bg-emerald-900">
            <img src="https://images.unsplash.com/photo-1518605363189-9854359db5a3?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay" alt="Soccer Field">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-900/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 p-12 text-white">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                    <i class="fa-solid fa-futbol text-3xl text-emerald-600"></i>
                </div>
                <h1 class="text-4xl font-heading font-bold mb-4">PitchManage</h1>
                <p class="text-emerald-50 text-lg leading-relaxed">Hệ thống quản lý và đặt sân bóng đá nhân tạo hàng đầu. Nhanh chóng, tiện lợi và chuyên nghiệp.</p>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="w-full lg:w-1/2 p-8 sm:p-12 md:p-16 flex flex-col justify-center">
            
            <div class="text-center lg:text-left mb-10">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm lg:hidden mx-auto">
                    <i class="fa-solid fa-futbol text-3xl text-emerald-600"></i>
                </div>
                <h2 class="text-3xl font-heading font-bold text-slate-800 mb-2">Chào mừng trở lại! 👋</h2>
                <p class="text-slate-500 font-medium">Vui lòng đăng nhập vào tài khoản của bạn</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center">
                        <i class="fa-solid fa-triangle-exclamation mr-3 text-red-500"></i>
                        <span class="text-sm font-medium">{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Email -->
                <div class="relative">
                    <input type="email" name="email" id="email" class="floating-input block w-full px-4 py-4 rounded-xl text-slate-800 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm transition-all duration-300 peer" placeholder=" " value="{{ old('email') }}" required autofocus>
                    <label for="email" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-1/2 top-1/2 left-4 z-10 origin-[0] peer-focus:left-4 cursor-text bg-white px-1">Địa chỉ Email</label>
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" name="password" id="password" class="floating-input block w-full px-4 py-4 rounded-xl text-slate-800 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm transition-all duration-300 peer" placeholder=" " required>
                    <label for="password" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-1/2 top-1/2 left-4 z-10 origin-[0] peer-focus:left-4 cursor-text bg-white px-1">Mật khẩu</label>
                    <button type="button" @click="show = !show" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-emerald-500 transition-colors">
                        <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between text-sm py-2">
                    <label class="flex items-center space-x-2 cursor-pointer group">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-500 focus:ring-emerald-500/50 transition-all cursor-pointer">
                        <span class="text-slate-600 group-hover:text-slate-800 transition-colors font-medium">Ghi nhớ tôi</span>
                    </label>
                    <a href="javascript:void(0);" class="text-emerald-600 hover:text-emerald-700 font-semibold transition-colors">Quên mật khẩu?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-4 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 hover:-translate-y-0.5 active:translate-y-0">
                    <span class="flex items-center justify-center gap-2">
                        Đăng Nhập Vào Hệ Thống <i class="fa-solid fa-arrow-right"></i>
                    </span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 text-center relative">
                <span class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white px-4 text-xs font-semibold text-slate-400 uppercase tracking-widest">Hoặc</span>
                
                <button class="mt-4 w-full bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold py-3.5 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-3">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                    Đăng nhập bằng Google
                </button>
            </div>
            
            <div class="mt-8 text-center text-sm">
                <span class="text-slate-500 font-medium">Chưa có tài khoản?</span>
                <a href="{{ route('register') }}" class="text-emerald-600 hover:text-emerald-700 font-bold ml-1 transition-colors">Đăng ký thành viên mới</a>
            </div>
        </div>
    </div>

</body>
</html>
