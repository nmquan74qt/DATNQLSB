@extends('layouts.admin')

@section('title', 'Tạo Khách Hàng Mới - PitchManage')
@section('page_title', 'Tạo Tài Khoản Khách Hàng')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border">
            <div class="card-header py-3 bg-white">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-users text-success me-2"></i>Thông Tin Khách Hàng</h5>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.customers.store') }}" method="POST">
                    @csrf
                    
                    <!-- Setting customer role hiddenly -->
                    <input type="hidden" name="role_id" value="{{ $roles->first()->id }}">

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Họ và tên khách hàng</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" value="{{ old('name') }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="email" class="form-label fw-semibold">Địa chỉ Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="customername@gmail.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Ví dụ: 0987654321" value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="status" class="form-label fw-semibold">Trạng thái ban đầu</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Kích Hoạt (Hoạt động)</option>
                                <option value="locked" {{ old('status') == 'locked' ? 'selected' : '' }}>Khóa (Tạm ngừng)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold">Xác nhận mật khẩu</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary px-4 py-2"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
                        <button type="submit" class="btn btn-success px-4 py-2"><i class="fa-solid fa-save me-1"></i> Tạo Tài Khoản</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
