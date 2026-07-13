@extends('layouts.public')

@section('title', 'Đăng Nhập - PitchManage')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border shadow-lg mt-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-soccer-ball text-success fs-1 mb-2"></i>
                        <h3 class="fw-bold">ĐĂNG NHẬP</h3>
                        <p class="text-secondary small">Vui lòng đăng nhập hệ thống để tiếp tục</p>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-3" role="alert">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <label for="password" class="form-label fw-semibold mb-0">Mật khẩu</label>
                                <a href="{{ route('password.request') }}" class="text-success text-decoration-none small">Quên mật khẩu?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-secondary small" for="remember">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2.5 fw-semibold">
                            <i class="fa-solid fa-sign-in me-1"></i> Đăng Nhập
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-secondary small mb-0">Chưa có tài khoản? <a href="{{ route('register') }}" class="text-success text-decoration-none fw-bold">Đăng ký ngay</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
