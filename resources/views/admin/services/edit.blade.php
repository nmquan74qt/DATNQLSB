@extends('layouts.admin')

@section('title', 'Cập Nhật Dịch Vụ - PitchManage')
@section('page_title', 'Sửa Dịch Vụ')

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

                <form action="{{ route('admin.services.update', $service->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Tên Dịch Vụ</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $service->name) }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="unit" class="form-label fw-semibold">Đơn Vị Tính</label>
                            <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $service->unit) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold">Đơn Giá (VND)</label>
                            <input type="number" name="price" id="price" class="form-control" value="{{ old('price', $service->price) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label fw-semibold">Số Lượng Tồn Kho</label>
                        <input type="number" name="stock" id="stock" class="form-control" value="{{ old('stock', $service->stock) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Mô Tả Dịch Vụ</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $service->description) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-secondary px-4 py-2"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
                        <button type="submit" class="btn btn-success px-4 py-2"><i class="fa-solid fa-save me-1"></i> Cập Nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
