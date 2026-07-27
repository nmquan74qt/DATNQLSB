<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Field;
use App\Http\Resources\FieldResource;

class FieldController extends Controller
{
    public function index()
    {
        $fields = Field::with('fieldType')->where('status', 'available')->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách sân thành công',
            'data' => FieldResource::collection($fields)
        ]);
    }
}
