@extends('layouts.admin')

@section('title', 'Quản Lý Loại Sân - PitchManage')
@section('page_title', 'Danh Sách Loại Sân')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h5 class="m-0 fw-bold"><i class="fa-solid fa-layer-group text-success me-2"></i>Danh sách loại sân</h5>
                <a href="{{ route('admin.field-types.create') }}" class="btn btn-success btn-sm"><i class="fa-solid fa-plus me-1"></i> Thêm Loại Sân Mới</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="px-4 py-3">ID</th>
                                <th>Tên Loại Sân</th>
                                <th>Mô Tả</th>
                                <th>Giá Thuê / Giờ</th>
                                <th class="text-center px-4">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fieldTypes as $type)
                                <tr>
                                    <td class="px-4">{{ $type->id }}</td>
                                    <td class="fw-bold">{{ $type->name }}</td>
                                    <td>{{ Str::limit($type->description, 100) }}</td>
                                    <td class="fw-bold text-success">{{ number_format($type->price_per_hour) }}đ</td>
                                    <td class="text-center px-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.field-types.edit', $type->id) }}" class="btn btn-sm btn-outline-success"><i class="fa-solid fa-edit me-1"></i> Sửa</a>
                                            <form action="{{ route('admin.field-types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa loại sân này không?');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash me-1"></i> Xóa</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">Chưa có loại sân nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($fieldTypes->hasPages())
                <div class="card-footer py-3">
                    {{ $fieldTypes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
