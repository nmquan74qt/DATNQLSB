<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldType;
use Illuminate\Http\Request;

class FieldTypeController extends Controller
{
    public function index()
    {
        $fieldTypes = FieldType::paginate(10);
        return view('admin.field_types.index', compact('fieldTypes'));
    }

    public function create()
    {
        return view('admin.field_types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên loại sân không được trống.',
            'price_per_hour.required' => 'Giá mỗi giờ không được trống.',
            'price_per_hour.numeric' => 'Giá trị phải là số.',
        ]);

        FieldType::create($data);

        return redirect()->route('admin.field-types.index')->with('success', 'Loại sân mới đã được tạo.');
    }

    public function edit(FieldType $fieldType)
    {
        return view('admin.field_types.edit', compact('fieldType'));
    }

    public function update(Request $request, FieldType $fieldType)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Tên loại sân không được trống.',
            'price_per_hour.required' => 'Giá mỗi giờ không được trống.',
        ]);

        $fieldType->update($data);

        return redirect()->route('admin.field-types.index')->with('success', 'Thông tin loại sân đã được cập nhật.');
    }

    public function destroy(FieldType $fieldType)
    {
        // Check if there are fields referencing this type
        if ($fieldType->footballFields()->count() > 0) {
            return redirect()->route('admin.field-types.index')->with('error', 'Không thể xóa loại sân này vì đang có sân thuộc loại này.');
        }

        $fieldType->delete();
        return redirect()->route('admin.field-types.index')->with('success', 'Loại sân đã được xóa.');
    }
}
