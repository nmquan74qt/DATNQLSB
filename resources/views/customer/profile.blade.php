@extends('layouts.customer')

@section('title', 'Cập Nhật Hồ Sơ - Khách Hàng')
@section('page_title', 'Hồ Sơ Cá Nhân')

@section('content_customer')
<div class="professional-table-card">
    <div class="d-flex align-items-center mb-4">
        <i class="fa-solid fa-user-edit text-success fs-4 me-2"></i>
        <h3 class="professional-table-title mb-0">Thay Đổi Thông Tin Cá Nhân</h3>
    </div>
    
    @if ($errors->any())
        <div class="alert alert-danger mb-4 rounded-3 border-0 shadow-sm">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <label for="avatar" class="form-label fw-semibold text-secondary small text-uppercase">Hình Ảnh Hồ Sơ</label>
                <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
                <div class="form-text">Định dạng hỗ trợ: JPEG, PNG, JPG, GIF. Dung lượng tối đa: 2MB.</div>
            </div>
            
            <div class="col-md-12 mb-3">
                <label for="name" class="form-label fw-semibold text-secondary small text-uppercase">Họ và tên</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="col-md-6 mb-3 mb-md-0">
                <label for="email" class="form-label fw-semibold text-secondary small text-uppercase">Địa chỉ Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label fw-semibold text-secondary small text-uppercase">Số điện thoại</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>
        </div>

        <hr class="my-4 opacity-10">
        
        <h5 class="fw-bold mb-3 text-dark fs-6" id="password">Đổi Mật Khẩu <span class="text-secondary fw-normal">(Để trống nếu không thay đổi)</span></h5>

        <div class="row mb-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <label for="password" class="form-label fw-semibold text-secondary small text-uppercase">Mật khẩu mới</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu mới">
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label fw-semibold text-secondary small text-uppercase">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới">
            </div>
        </div>

        <button type="submit" class="btn btn-success w-100 py-2 rounded-3" style="background-color: #219653; border: none; font-weight: 500;">
            <i class="fa-solid fa-save me-1"></i> Lưu Thay Đổi
        </button>
    </form>
</div>
@endsection
