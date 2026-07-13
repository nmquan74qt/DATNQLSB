<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class TimeSlotController extends Controller
{
    public function index()
    {
        $timeSlots = TimeSlot::orderBy('start_time', 'asc')->paginate(10);
        return view('admin.time_slots.index', compact('timeSlots'));
    }

    public function create()
    {
        return view('admin.time_slots.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'price_multiplier' => 'required|numeric|min:0.1|max:10.0',
        ], [
            'name.required' => 'Tên khung giờ không được trống.',
            'start_time.required' => 'Giờ bắt đầu không được trống.',
            'end_time.required' => 'Giờ kết thúc không được trống.',
            'price_multiplier.required' => 'Hệ số giá không được trống.',
        ]);

        TimeSlot::create($data);

        return redirect()->route('admin.time-slots.index')->with('success', 'Khung giờ mới đã được tạo.');
    }

    public function edit(TimeSlot $timeSlot)
    {
        return view('admin.time_slots.edit', compact('timeSlot'));
    }

    public function update(Request $request, TimeSlot $timeSlot)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'price_multiplier' => 'required|numeric|min:0.1|max:10.0',
        ], [
            'name.required' => 'Tên khung giờ không được trống.',
            'price_multiplier.required' => 'Hệ số giá không được trống.',
        ]);

        $timeSlot->update($data);

        return redirect()->route('admin.time-slots.index')->with('success', 'Thông tin khung giờ đã được cập nhật.');
    }

    public function destroy(TimeSlot $timeSlot)
    {
        $timeSlot->delete();
        return redirect()->route('admin.time-slots.index')->with('success', 'Khung giờ đã được xóa.');
    }
}
