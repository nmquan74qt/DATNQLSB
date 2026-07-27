@extends('layouts.admin')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-slate-800 dark:text-white">Thêm Sân Bóng Mới</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Khởi tạo thông tin cho sân bóng mới vào hệ thống</p>
        </div>
        <a href="{{ route('admin.fields.index') }}" class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-4 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="p-6">
            <form action="{{ route('admin.fields.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-2">Thông Tin Cơ Bản</h3>
                        
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Tên Sân Bóng <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 shadow-inner" placeholder="VD: Sân Cỏ Nhân Tạo A1">
                            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Field Type -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Loại Sân <span class="text-red-500">*</span></label>
                                <select name="field_type_id" required class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 shadow-inner">
                                    <option value="">-- Chọn Loại Sân --</option>
                                    @foreach($fieldTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('field_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }} (Sức chứa: {{ $type->capacity }})</option>
                                    @endforeach
                                </select>
                                @error('field_type_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Base Price -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Giá Cơ Bản/Giờ <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" name="base_price" value="{{ old('base_price') }}" required min="0" step="1000" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 pr-12 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 shadow-inner" placeholder="300000">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">VNĐ</span>
                                </div>
                                @error('base_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Status & Active -->
                        <div class="grid grid-cols-2 gap-4 border-t border-slate-100 dark:border-slate-700 pt-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Trạng Thái Ban Đầu</label>
                                <select name="status" required class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 shadow-inner">
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Trống (Sẵn sàng)</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Đang Bảo Trì</option>
                                </select>
                                @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="flex items-center mt-8">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                                    <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-500"></div>
                                    <span class="ml-3 text-sm font-bold text-slate-700 dark:text-slate-300">Hoạt Động</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white border-b border-slate-100 dark:border-slate-700 pb-2">Hình Ảnh & Chi Tiết</h3>
                        
                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Mô Tả Sân Bóng</label>
                            <textarea name="description" rows="5" class="w-full bg-slate-50 dark:bg-slate-900 border-none rounded-xl px-4 py-3 text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-primary/50 shadow-inner" placeholder="Mô tả về mặt cỏ, hệ thống đèn, vị trí...">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Image Upload (UI only for now) -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Hình Ảnh Sân (Tùy chọn)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 dark:border-slate-600 border-dashed rounded-xl hover:bg-slate-50 dark:hover:bg-slate-900/50 transition-colors group cursor-pointer">
                                <div class="space-y-1 text-center">
                                    <div class="mx-auto w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <div class="flex text-sm text-slate-600 dark:text-slate-400 justify-center">
                                        <label for="file-upload" class="relative cursor-pointer bg-transparent rounded-md font-medium text-primary hover:text-blue-500 focus-within:outline-none">
                                            <span>Tải ảnh lên</span>
                                            <input id="file-upload" name="images[]" type="file" multiple class="sr-only">
                                        </label>
                                        <p class="pl-1">hoặc kéo thả vào đây</p>
                                    </div>
                                    <p class="text-xs text-slate-500">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-4">
                    <a href="{{ route('admin.fields.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-bold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">Hủy Bỏ</a>
                    <button type="submit" class="bg-primary hover:bg-blue-600 text-white px-8 py-3 rounded-xl font-bold shadow-md shadow-primary/30 transition-transform transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Lưu Dữ Liệu
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
