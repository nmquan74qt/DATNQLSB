@extends('layouts.public')

@section('title', 'Đăng Ký Khách Hàng - PitchManage')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border shadow-lg mt-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-user-plus text-success fs-1 mb-2"></i>
                        <h3 class="fw-bold">ĐĂNG KÝ TÀI KHOẢN</h3>
                        <p class="text-secondary small">Đăng ký tài khoản để đặt sân trực tuyến</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3" role="alert">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Họ và tên</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nguyễn Văn A" value="{{ old('name') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com" value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Ví dụ: 0987654321" value="{{ old('phone') }}" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-check-double"></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2.5 fw-semibold">
                            <i class="fa-solid fa-user-check me-1"></i> Đăng Ký Tài Khoản
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-secondary small mb-0">Đã có tài khoản? <a href="{{ route('login') }}" class="text-success text-decoration-none fw-bold">Đăng nhập</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
