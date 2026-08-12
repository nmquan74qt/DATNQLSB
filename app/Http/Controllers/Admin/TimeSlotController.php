<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index()
    {
        $timeSlots = TimeSlot::with('field')->orderBy('field_id')->orderBy('start_time')->get();
        $fields = \App\Models\Field::where('is_active', true)->get();
        return view('admin.time_slots.index', compact('timeSlots', 'fields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'field_id' => 'nullable|exists:fields,id',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price_modifier' => 'required|numeric',
            'weekend_price_modifier' => 'nullable|numeric',
            'is_active' => 'boolean'
        ]);

        TimeSlot::create([
            'field_id' => $request->field_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'price_modifier' => $request->price_modifier,
            'weekend_price_modifier' => $request->weekend_price_modifier ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Đã thêm khung giờ mới!');
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        $request->validate([
            'field_id' => 'nullable|exists:fields,id',
            'start_time' => 'required|date_format:H:i:s,H:i',
            'end_time' => 'required|date_format:H:i:s,H:i',
            'price_modifier' => 'required|numeric',
            'weekend_price_modifier' => 'nullable|numeric',
        ]);
        
        $startTime = strlen($request->start_time) == 5 ? $request->start_time . ':00' : $request->start_time;
        $endTime = strlen($request->end_time) == 5 ? $request->end_time . ':00' : $request->end_time;

        $timeSlot->update([
            'field_id' => $request->field_id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price_modifier' => $request->price_modifier,
            'weekend_price_modifier' => $request->weekend_price_modifier ?? 0,
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Đã cập nhật khung giờ!');
    }

    public function destroy(TimeSlot $timeSlot)
    {
        // Kiểm tra xem khung giờ này đã có ai đặt chưa trước khi xóa
        if ($timeSlot->bookingDetails()->count() > 0) {
            return back()->with('error', 'Không thể xóa khung giờ đã có lịch đặt! Vui lòng chỉ Tắt (Inactive) khung giờ này.');
        }

        $timeSlot->delete();
        return back()->with('success', 'Đã xóa khung giờ!');
    }
}
