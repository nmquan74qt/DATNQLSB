@extends('layouts.admin')

@section('title', 'Thêm Sân Bóng Mới - PitchManage')
@section('page_title', 'Tạo Sân Bóng Mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border">
            <div class="card-header py-3 bg-white">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-circle-play text-success me-2"></i>Thông Tin Sân Bóng</h5>
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

                <form action="{{ route('admin.fields.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Tên Sân Bóng</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Ví dụ: Sân Mini A1" value="{{ old('name') }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="field_type_id" class="form-label fw-semibold">Loại Sân</label>
                            <select name="field_type_id" id="field_type_id" class="form-select" required>
                                <option value="" disabled selected>-- Chọn loại sân --</option>
                                @foreach($fieldTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('field_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ number_format($type->price_per_hour) }}đ/h)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">Trạng Thái Sân</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Đang Trống (Sẵn sàng)</option>
                                <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Đang Sử Dụng</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Bảo Trì</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label fw-semibold">Hình Ảnh Sân Bóng (Tùy chọn)</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Mô Tả Chi Tiết</label>
                        <textarea name="description" id="description" class="form-control" rows="4" placeholder="Nhập thông tin mô tả chi tiết vị trí hoặc chất lượng cỏ sân bóng...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.fields.index') }}" class="btn btn-outline-secondary px-4 py-2"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
                        <button type="submit" class="btn btn-success px-4 py-2"><i class="fa-solid fa-save me-1"></i> Tạo Sân Bóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
