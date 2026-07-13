@extends('layouts.admin')

@section('title', 'Quản Lý Nhân Viên - PitchManage')
@section('page_title', 'Tài Khoản Nhân Viên')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-user-shield text-success me-2"></i>Danh sách nhân viên</h5>
                <a href="{{ route('admin.staffs.create') }}" class="btn btn-success btn-sm"><i class="fa-solid fa-plus me-1"></i> Tạo Nhân Viên Mới</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="px-4 py-3">Họ Tên</th>
                                <th>Email</th>
                                <th>Số Điện Thoại</th>
                                <th>Vai Trò</th>
                                <th>Trạng Thái</th>
                                <th class="text-center px-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffs as $staff)
                                <tr>
                                    <td class="px-4 fw-bold">{{ $staff->name }}</td>
                                    <td>{{ $staff->email }}</td>
                                    <td>{{ $staff->phone ?: '-' }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $staff->role->description }}</span></td>
                                    <td>
                                        @if($staff->status === 'active')
                                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Đang Hoạt Động</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fa-solid fa-lock me-1"></i> Bị Khóa</span>
                                        @endif
                                    </td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Toggle status button -->
                                            <form action="{{ route('admin.staffs.toggle-status', $staff->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning" {{ auth()->id() === $staff->id ? 'disabled' : '' }}>
                                                    @if($staff->status === 'active')
                                                        <i class="fa-solid fa-lock"></i> Khóa
                                                    @else
                                                        <i class="fa-solid fa-unlock"></i> Mở
                                                    @endif
                                                </button>
                                            </form>
                                            
                                            <a href="{{ route('admin.staffs.edit', $staff->id) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-edit me-1"></i> Sửa</a>
                                            
                                            <form action="{{ route('admin.staffs.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản nhân viên này không?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" {{ auth()->id() === $staff->id ? 'disabled' : '' }}><i class="fa-solid fa-trash me-1"></i> Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-secondary">Chưa có tài khoản nhân viên nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($staffs->hasPages())
                <div class="card-footer py-3">
                    {{ $staffs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
