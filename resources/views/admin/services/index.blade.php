@extends('layouts.admin')

@section('title', 'Quản Lý Dịch Vụ - PitchManage')
@section('page_title', 'Danh Sách Dịch Vụ Đi Kèm')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-cubes text-success me-2"></i>Danh sách dịch vụ</h5>
                <a href="{{ route('admin.services.create') }}" class="btn btn-success btn-sm"><i class="fa-solid fa-plus me-1"></i> Thêm Dịch Vụ Mới</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="px-4 py-3 text-start">Tên Dịch Vụ</th>
                                <th>Đơn Vị Tính</th>
                                <th>Đơn Giá</th>
                                <th>Số Lượng Tồn</th>
                                <th class="text-center px-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                                <tr>
                                    <td class="px-4 fw-bold text-start">
                                        <div>{{ $service->name }}</div>
                                        <small class="text-secondary fw-normal">{{ Str::limit($service->description, 60) }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark border">{{ $service->unit }}</span></td>
                                    <td class="fw-bold text-success">{{ number_format($service->price) }}đ</td>
                                    <td>
                                        @if($service->stock > 10)
                                            <span class="badge bg-success-subtle text-success">{{ $service->stock }} {{ $service->unit }}</span>
                                        @elseif($service->stock > 0)
                                            <span class="badge bg-warning-subtle text-warning">{{ $service->stock }} {{ $service->unit }} (Sắp hết)</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-edit me-1"></i> Sửa</a>
                                            <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa dịch vụ này không?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash me-1"></i> Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">Chưa có dịch vụ nào được cấu hình.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($services->hasPages())
                <div class="card-footer py-3">
                    {{ $services->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
