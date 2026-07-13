@extends('layouts.admin')

@section('title', 'Cập Nhật Loại Sân - PitchManage')
@section('page_title', 'Sửa Loại Sân')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border">
            <div class="card-header py-3 bg-white">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-layer-group text-success me-2"></i>Thông Tin Loại Sân</h5>
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

                <form action="{{ route('admin.field-types.update', $fieldType->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Tên Loại Sân</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $fieldType->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="price_per_hour" class="form-label fw-semibold">Giá Thuê Mỗi Giờ (VND)</label>
                        <input type="number" name="price_per_hour" id="price_per_hour" class="form-control" value="{{ old('price_per_hour', $fieldType->price_per_hour) }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold">Mô Tả</label>
                        <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $fieldType->description) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.field-types.index') }}" class="btn btn-outline-secondary px-4 py-2"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
                        <button type="submit" class="btn btn-success px-4 py-2"><i class="fa-solid fa-save me-1"></i> Cập Nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
