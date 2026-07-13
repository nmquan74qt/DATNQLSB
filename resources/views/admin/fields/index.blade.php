@extends('layouts.admin')

@section('title', 'Quản Lý Sân Bóng - PitchManage')
@section('page_title', 'Danh Sách Sân Bóng')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-circle-play text-success me-2"></i>Danh sách sân bóng</h5>
                <a href="{{ route('admin.fields.create') }}" class="btn btn-success btn-sm"><i class="fa-solid fa-plus me-1"></i> Thêm Sân Bóng Mới</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="px-4 py-3">Hình Ảnh</th>
                                <th>Tên Sân</th>
                                <th>Loại Sân</th>
                                <th>Giá Cơ Bản / Giờ</th>
                                <th>Trạng Thái</th>
                                <th class="text-center px-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fields as $field)
                                <tr>
                                    <td class="px-4">
                                        @if($field->image)
                                            <img src="{{ asset($field->image) }}" class="rounded shadow-sm" style="width: 80px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="rounded bg-light d-flex align-items-center justify-content-center border" style="width: 80px; height: 50px;">
                                                <i class="fa-solid fa-image text-secondary"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $field->name }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $field->fieldType->name }}</span></td>
                                    <td class="fw-bold text-success">{{ number_format($field->fieldType->price_per_hour) }}đ</td>
                                    <td>
                                        @if($field->status === 'available')
                                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Đang Trống</span>
                                        @elseif($field->status === 'occupied')
                                            <span class="badge bg-danger"><i class="fa-solid fa-circle text-white me-1"></i> Đang Sử Dụng</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-wrench me-1"></i> Bảo Trì</span>
                                        @endif
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.fields.edit', $field->id) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-edit me-1"></i> Sửa</a>
                                            <form action="{{ route('admin.fields.destroy', $field->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sân bóng này không?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash me-1"></i> Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-secondary">Chưa có sân bóng nào được tạo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($fields->hasPages())
                <div class="card-footer py-3">
                    {{ $fields->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
