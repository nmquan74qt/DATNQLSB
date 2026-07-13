@extends('layouts.admin')

@section('title', 'Cập Nhật Tài Khoản Nhân Viên - PitchManage')
@section('page_title', 'Sửa Nhân Viên')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border">
            <div class="card-header py-3 bg-white">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-user-shield text-success me-2"></i>Thông Tin Nhân Viên</h5>
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

                <form action="{{ route('admin.staffs.update', $staff->id) }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Họ và tên nhân viên</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $staff->name) }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="email" class="form-label fw-semibold">Địa chỉ Email (Tên đăng nhập)</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $staff->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $staff->phone) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="role_id" class="form-label fw-semibold">Vai trò hệ thống</label>
                            <select name="role_id" id="role_id" class="form-select" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $staff->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">Trạng thái tài khoản</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="active" {{ old('status', $staff->status) == 'active' ? 'selected' : '' }}>Hoạt Động</option>
                                <option value="locked" {{ old('status', $staff->status) == 'locked' ? 'selected' : '' }}>Khóa</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">
                    <h5 class="fw-bold mb-3 text-secondary">Đổi Mật Khẩu (Để trống nếu không thay đổi)</h5>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="password" class="form-label fw-semibold">Mật khẩu mới</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu mới">
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold">Xác nhận mật khẩu mới</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Xác nhận lại">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.staffs.index') }}" class="btn btn-outline-secondary px-4 py-2"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
                        <button type="submit" class="btn btn-success px-4 py-2"><i class="fa-solid fa-save me-1"></i> Cập Nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
