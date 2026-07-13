@extends('layouts.public')

@section('title', 'Quên Mật Khẩu - PitchManage')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border shadow-lg mt-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fa-solid fa-key text-success fs-1 mb-2"></i>
                        <h3 class="fw-bold">QUÊN MẬT KHẨU?</h3>
                        <p class="text-secondary small">Nhập email đăng ký để nhận liên kết khôi phục mật khẩu</p>
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

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="example@gmail.com" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2.5 fw-semibold">
                            <i class="fa-solid fa-paper-plane me-1"></i> Gửi Liên Kết
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-secondary small mb-0"><a href="{{ route('login') }}" class="text-success text-decoration-none fw-bold"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại đăng nhập</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
