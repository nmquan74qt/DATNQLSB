@extends('layouts.admin')

@section('title', 'Sửa Khung Giờ - PitchManage')
@section('page_title', 'Cập Nhật Khung Giờ')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border">
            <div class="card-header py-3 bg-white">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-clock text-success me-2"></i>Thông Tin Khung Giờ</h5>
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

                <form action="{{ route('admin.time-slots.update', $timeSlot->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Tên Khung Giờ</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $timeSlot->name) }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="start_time" class="form-label fw-semibold">Giờ Bắt Đầu</label>
                            <input type="time" name="start_time" id="start_time" class="form-control" value="{{ old('start_time', $timeSlot->start_time) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_time" class="form-label fw-semibold">Giờ Kết Thúc</label>
                            <input type="time" name="end_time" id="end_time" class="form-control" value="{{ old('end_time', $timeSlot->end_time) }}" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="price_multiplier" class="form-label fw-semibold">Hệ Số Phụ Thu Giá (Multiplier)</label>
                        <input type="number" step="0.01" name="price_multiplier" id="price_multiplier" class="form-control" value="{{ old('price_multiplier', $timeSlot->price_multiplier) }}" required>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.time-slots.index') }}" class="btn btn-outline-secondary px-4 py-2"><i class="fa-solid fa-arrow-left me-1"></i> Quay lại</a>
                        <button type="submit" class="btn btn-success px-4 py-2"><i class="fa-solid fa-save me-1"></i> Cập Nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
