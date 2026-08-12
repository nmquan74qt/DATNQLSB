<?php

namespace App\Http\Controllers;

use App\Services\FieldService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $fieldService;

    public function __construct(FieldService $fieldService)
    {
        $this->fieldService = $fieldService;
    }

    public function home()
    {
        // For homepage, we might want featured fields, for now just get latest 6 active
        $fields = $this->fieldService->getAllFieldsPaginated(6);
        return view('home', compact('fields'));
    }

    public function fields()
    {
        // Advanced filter page
        $fields = $this->fieldService->getAllFieldsPaginated(12);
        return view('pages.fields', compact('fields'));
    }

    public function fieldDetail(string $slug)
    {
        $field = \App\Models\Field::with('fieldType')->where('slug', $slug)->firstOrFail();
        
        // Prepare data for Booking Wizard
        $timeSlots = \App\Models\TimeSlot::where('is_active', true)->orderBy('start_time')->get();
        
        $bookedDetails = \App\Models\BookingDetail::where('field_id', $field->id)
            ->whereHas('booking', function($q) {
                $q->whereIn('status', ['pending', 'confirmed', 'completed'])
                  ->where('booking_date', '>=', today())
                  ->where('booking_date', '<=', today()->addDays(14));
            })
            ->with('booking:id,booking_date')
            ->get();
            
        $bookedSlotsByDate = [];
        foreach ($bookedDetails as $detail) {
            $date = \Carbon\Carbon::parse($detail->booking->booking_date)->format('Y-m-d');
            if (!isset($bookedSlotsByDate[$date])) {
                $bookedSlotsByDate[$date] = [];
            }
            $bookedSlotsByDate[$date][] = $detail->time_slot_id;
        }

        return view('pages.field_detail', compact('field', 'timeSlots', 'bookedSlotsByDate'));
    }

    public function checkout(Request $request)
    {
        $fieldId = $request->query('field_id');
        $date = $request->query('date');
        $slotIds = array_filter(explode(',', $request->query('slots', '')));
        
        if (!$fieldId || !$date || empty($slotIds)) {
            return redirect()->route('home')->with('error', 'Thông tin đặt sân không hợp lệ.');
        }

        $field = \App\Models\Field::with('fieldType')->findOrFail($fieldId);
        $timeSlots = \App\Models\TimeSlot::whereIn('id', $slotIds)->orderBy('start_time')->get();
        
        if ($timeSlots->isEmpty()) {
            return redirect()->route('home')->with('error', 'Không tìm thấy khung giờ nào.');
        }
        
        return view('pages.checkout', compact('field', 'date', 'timeSlots'));
    }
}
