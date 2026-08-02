@extends('layouts.app')

@section('title', 'Đăng Ký - PitchManage')

@push('scripts')
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
@endpush

@section('content')
<div class="min-h-[85vh] flex items-center justify-center p-4 pt-32 pb-20 relative overflow-hidden bg-slate-50" x-data>
    <!-- Decorative Background -->
    <div class="absolute top-0 w-full h-96 bg-gradient-to-b from-emerald-500/10 to-transparent z-0"></div>
    <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-emerald-400/20 rounded-full blur-3xl z-0 pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-400/10 rounded-full blur-3xl z-0 pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-5xl flex rounded-3xl overflow-hidden shadow-2xl bg-white animate-fade-up border border-slate-100 mx-auto">
        
        <!-- Left Side: Image / Branding -->
        <div class="hidden lg:block lg:w-1/2 relative bg-emerald-900">
            <img src="https://images.unsplash.com/photo-1574629810360-7efbbcb27a61?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay" alt="Soccer Field">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-900/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 p-12 text-white">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                    <i class="fa-solid fa-futbol text-3xl text-emerald-600"></i>
                </div>
                <h1 class="text-4xl font-heading font-bold mb-4">Tham Gia Ngay</h1>
                <p class="text-emerald-50 text-lg leading-relaxed">Đăng ký tài khoản để trải nghiệm hệ thống quản lý và đặt sân bóng đá nhân tạo chuyên nghiệp nhất.</p>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="w-full lg:w-1/2 p-8 sm:p-12 md:p-12 flex flex-col justify-center">
            
            <div class="text-center lg:text-left mb-8">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm lg:hidden mx-auto">
                    <i class="fa-solid fa-futbol text-3xl text-emerald-600"></i>
                </div>
                <h2 class="text-3xl font-heading font-bold text-slate-800 mb-2">Tạo Tài Khoản 🚀</h2>
                <p class="text-slate-500 font-medium">Tham gia hệ thống đặt sân chuyên nghiệp nhất</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl flex items-center mb-4">
                        <i class="fa-solid fa-triangle-exclamation mr-3 text-red-500"></i>
                        <span class="text-sm font-medium">{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- Name -->
                <div class="relative">
                    <input type="text" name="name" id="name" class="floating-input block w-full px-4 py-3.5 rounded-xl text-slate-800 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm transition-all duration-300 peer" placeholder=" " value="{{ old('name') }}" required autofocus>
                    <label for="name" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-1/2 top-1/2 left-4 z-10 origin-[0] peer-focus:left-4 cursor-text bg-white px-1">Họ và Tên</label>
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                        <i class="fa-regular fa-user"></i>
                    </div>
                </div>

                <!-- Email -->
                <div class="relative">
                    <input type="email" name="email" id="email" class="floating-input block w-full px-4 py-3.5 rounded-xl text-slate-800 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm transition-all duration-300 peer" placeholder=" " value="{{ old('email') }}" required>
                    <label for="email" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-1/2 top-1/2 left-4 z-10 origin-[0] peer-focus:left-4 cursor-text bg-white px-1">Địa chỉ Email</label>
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                </div>

                <!-- Phone -->
                <div class="relative">
                    <input type="text" name="phone" id="phone" class="floating-input block w-full px-4 py-3.5 rounded-xl text-slate-800 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm transition-all duration-300 peer" placeholder=" " value="{{ old('phone') }}">
                    <label for="phone" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-1/2 top-1/2 left-4 z-10 origin-[0] peer-focus:left-4 cursor-text bg-white px-1">Số Điện Thoại</label>
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Password -->
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" id="password" class="floating-input block w-full px-4 py-3.5 rounded-xl text-slate-800 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm transition-all duration-300 peer" placeholder=" " required>
                        <label for="password" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-1/2 top-1/2 left-4 z-10 origin-[0] peer-focus:left-4 cursor-text bg-white px-1">Mật khẩu</label>
                        <button type="button" @click="show = !show" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-emerald-500 transition-colors">
                            <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>

                    <!-- Confirm Password -->
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" class="floating-input block w-full px-4 py-3.5 rounded-xl text-slate-800 bg-white border border-slate-200 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 text-sm transition-all duration-300 peer" placeholder=" " required>
                        <label for="password_confirmation" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-1/2 top-1/2 left-4 z-10 origin-[0] peer-focus:left-4 cursor-text bg-white px-1">Nhập lại</label>
                    </div>
                </div>

                <div class="text-sm text-slate-500 mt-2 py-1">
                    Bằng việc đăng ký, bạn đồng ý với <a href="javascript:void(0);" class="text-emerald-600 hover:text-emerald-700 hover:underline font-medium">Điều khoản</a> và <a href="javascript:void(0);" class="text-emerald-600 hover:text-emerald-700 hover:underline font-medium">Chính sách bảo mật</a> của chúng tôi.
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-4 rounded-xl transition-all duration-300 shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/50 hover:-translate-y-0.5 active:translate-y-0 mt-2">
                    <span class="flex items-center justify-center gap-2">
                        Tạo Tài Khoản Mới <i class="fa-solid fa-user-plus"></i>
                    </span>
                </button>
            </form>
            
            <div class="mt-6 text-center text-sm">
                <span class="text-slate-500 font-medium">Đã có tài khoản?</span>
                <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-bold ml-1 transition-colors">Đăng nhập ngay</a>
            </div>
        </div>
    </div>
</div>
@endsection
