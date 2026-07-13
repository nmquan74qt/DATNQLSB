<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballField;
use App\Models\FieldType;
use App\Http\Requests\StoreFieldRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FieldController extends Controller
{
    public function index()
    {
        $fields = FootballField::with('fieldType')->paginate(10);
        return view('admin.fields.index', compact('fields'));
    }

    public function create()
    {
        $fieldTypes = FieldType::all();
        return view('admin.fields.create', compact('fieldTypes'));
    }

    public function store(StoreFieldRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Store image inside public folder to make it accessible without storage:link if needed, 
            // or store in standard 'public/fields' folder. Let's save in public/uploads/fields.
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/fields'), $filename);
            $data['image'] = 'uploads/fields/' . $filename;
        }

        FootballField::create($data);

        return redirect()->route('admin.fields.index')->with('success', 'Sân bóng đã được tạo thành công.');
    }

    public function edit(FootballField $field)
    {
        $fieldTypes = FieldType::all();
        return view('admin.fields.edit', compact('field', 'fieldTypes'));
    }

    public function update(StoreFieldRequest $request, FootballField $field)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($field->image && file_exists(public_path($field->image))) {
                @unlink(public_path($field->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/fields'), $filename);
            $data['image'] = 'uploads/fields/' . $filename;
        }

        $field->update($data);

        return redirect()->route('admin.fields.index')->with('success', 'Thông tin sân bóng đã được cập nhật.');
    }

    public function destroy(FootballField $field)
    {
        if ($field->image && file_exists(public_path($field->image))) {
            @unlink(public_path($field->image));
        }

        $field->delete();

        return redirect()->route('admin.fields.index')->with('success', 'Sân bóng đã được xóa thành công.');
    }
}
