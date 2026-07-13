@extends('layouts.admin')

@section('title', 'Thêm Dịch Vụ Mới - PitchManage')
@section('page_title', 'Tạo Dịch Vụ Mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border">
            <div class="card-header py-3 bg-white">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-cubes text-success me-2"></i>Thông Tin Dịch Vụ</h5>
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

                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Tên Dịch Vụ (Đồ uống, thuê bib, thuê bóng...)</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Ví dụ: Nước suối Aquafina" value="{{ old('name') }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="unit" class="form-label fw-semibold">Đơn Vị Tính</label>
                            <input type="text" name="unit" id="unit" class="form-control" placeholder="Ví dụ: Chai, Quả, Đôi, Bộ" value="{{ old('unit') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold">Đơn Giá (VND)</label>
                            <input type="number" name="price" id="price" class="form-control" placeholder="Ví dụ: 15000" value="{{ old('price') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label fw-semibold">Số Lượng Tồn Kho Ban Đầu</label>
                        <input type="number" name="stock" id="stock" class="form-control" placeholder="Ví dụ: 100" value="{{ old('stock', '100') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Mô Tả Dịch Vụ (Không bắt buộc)</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Nhập thông tin mô tả chi tiết...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary px-4 py-2"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
                        <button type="submit" class="btn btn-success px-4 py-2"><i class="fa-solid fa-save me-1"></i> Tạo Dịch Vụ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
