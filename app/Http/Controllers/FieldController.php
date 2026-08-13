<?php

namespace App\Http\Controllers;

use App\Models\FieldType;
use App\Services\FieldService;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    protected $fieldService;

    public function __construct(FieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    public function index()
    {
        $fields = $this->fieldService->getAllFieldsPaginated(10);
        return view('admin.fields.index', compact('fields'));
    }

    public function create()
    {
        $fieldTypes = FieldType::all();
        return view('admin.fields.create', compact('fieldTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'field_type_id' => 'required|exists:field_types,id',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:available,booked,in_use,maintenance',
            'description' => 'nullable|string',
            'is_active' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $storeData = $validated;
        unset($storeData['images']);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imagePath = $images[0]->store('fields', 'public');
            $storeData['image'] = $imagePath;
        }

        $field = $this->fieldService->createField($storeData);

        if (isset($images)) {
            foreach ($images as $index => $image) {
                $path = $image->store('fields', 'public');
                $field->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0
                ]);
            }
        }

        return redirect()->route('admin.fields.index')->with('success', 'Thêm sân bóng thành công!');
    }

    public function edit(int $id)
    {
        $field = $this->fieldService->getFieldById($id);
        $fieldTypes = FieldType::all();
        return view('admin.fields.edit', compact('field', 'fieldTypes'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'field_type_id' => 'required|exists:field_types,id',
            'base_price' => 'required|numeric|min:0',
            'status' => 'required|in:available,booked,in_use,maintenance',
            'description' => 'nullable|string',
            'is_active' => 'nullable',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $updateData = $validated;
        unset($updateData['images']);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imagePath = $images[0]->store('fields', 'public');
            $updateData['image'] = $imagePath;
        }

        $this->fieldService->updateField($id, $updateData);

        if (isset($images)) {
            $field = $this->fieldService->getFieldById($id);
            // Có thể xóa ảnh cũ nếu muốn, hoặc cứ thêm ảnh mới
            foreach ($images as $index => $image) {
                $path = $image->store('fields', 'public');
                $field->images()->create([
                    'image_path' => $path,
                    'is_primary' => false
                ]);
            }
        }

        return redirect()->route('admin.fields.index')->with('success', 'Cập nhật sân bóng thành công!');
    }

    public function destroy(int $id)
    {
        $this->fieldService->deleteField($id);
        return redirect()->route('admin.fields.index')->with('success', 'Đã xóa sân bóng!');
    }
}
