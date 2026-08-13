<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FieldType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FieldTypeController extends Controller
{
    public function index()
    {
        $fieldTypes = FieldType::latest()->get();
        return view('admin.field_types.index', compact('fieldTypes'));
    }

    public function create()
    {
        return view('admin.field_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        FieldType::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'capacity' => $request->capacity,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.field-types.index')->with('success', 'Thêm loại sân thành công!');
    }

    public function edit(FieldType $fieldType)
    {
        return view('admin.field_types.edit', compact('fieldType'));
    }

    public function update(Request $request, FieldType $fieldType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $fieldType->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'capacity' => $request->capacity,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.field-types.index')->with('success', 'Cập nhật loại sân thành công!');
    }

    public function destroy(FieldType $fieldType)
    {
        // Kiểm tra xem có sân nào đang dùng loại này không
        if ($fieldType->fields()->count() > 0) {
            return back()->with('error', 'Không thể xóa loại sân đang được sử dụng!');
        }

        $fieldType->delete();
        return redirect()->route('admin.field-types.index')->with('success', 'Xóa loại sân thành công!');
    }
}
