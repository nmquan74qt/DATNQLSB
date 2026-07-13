@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng - PitchManage')
@section('page_title', 'Danh Sách Khách Hàng')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-users text-success me-2"></i>Danh sách khách hàng</h5>
                
                <!-- Search Box -->
                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('admin.customers.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Tìm theo tên, email, sđt..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa-solid fa-search"></i></button>
                        @if(request()->filled('search'))
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm ms-2"><i class="fa-solid fa-arrows-rotate"></i></a>
                        @endif
                    </form>
                    <a href="{{ route('admin.customers.create') }}" class="btn btn-success btn-sm ms-2 text-nowrap"><i class="fa-solid fa-plus me-1"></i> Thêm Khách Hàng</a>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="px-4 py-3">Họ Tên</th>
                                <th>Email</th>
                                <th>Số Điện Thoại</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Tham Gia</th>
                                <th class="text-center px-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td class="px-4 fw-bold">{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone ?: '-' }}</td>
                                    <td>
                                        @if($customer->status === 'active')
                                            <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i> Hoạt Động</span>
                                        @else
                                            <span class="badge bg-danger"><i class="fa-solid fa-lock me-1"></i> Bị Khóa</span>
                                        @endif
                                    </td>
                                    <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <!-- Toggle status button -->
                                            <form action="{{ route('admin.customers.toggle-status', $customer->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                                    @if($customer->status === 'active')
                                                        <i class="fa-solid fa-lock me-1"></i> Khóa
                                                    @else
                                                        <i class="fa-solid fa-unlock me-1"></i> Mở
                                                    @endif
                                                </button>
                                            </form>
                                            
                                            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-edit me-1"></i> Sửa</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-secondary">Không tìm thấy khách hàng nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($customers->hasPages())
                <div class="card-footer py-3">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
