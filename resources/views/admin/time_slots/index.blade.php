@extends('layouts.admin')

@section('title', 'Quản Lý Khung Giờ - PitchManage')
@section('page_title', 'Khung Giờ Thi Đấu')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-clock text-success me-2"></i>Danh sách khung giờ</h5>
                <a href="{{ route('admin.time-slots.create') }}" class="btn btn-success btn-sm"><i class="fa-solid fa-plus me-1"></i> Thêm Khung Giờ Mới</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0 text-center">
                        <thead class="table-dark">
                            <tr>
                                <th class="px-4 py-3 text-start">Tên Khung Giờ</th>
                                <th>Giờ Bắt Đầu</th>
                                <th>Giờ Kết Thúc</th>
                                <th>Hệ Số Giá</th>
                                <th class="text-center px-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($timeSlots as $slot)
                                <tr>
                                    <td class="px-4 fw-bold text-start">{{ $slot->name }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $slot->start_time }}</span></td>
                                    <td><span class="badge bg-light text-dark border">{{ $slot->end_time }}</span></td>
                                    <td class="fw-bold text-success">x{{ number_format($slot->price_multiplier, 2) }}</td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.time-slots.edit', $slot->id) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-edit me-1"></i> Sửa</a>
                                            <form action="{{ route('admin.time-slots.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khung giờ này không?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash me-1"></i> Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">Chưa có khung giờ nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($timeSlots->hasPages())
                <div class="card-footer py-3">
                    {{ $timeSlots->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
