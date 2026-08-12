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
        $fields = \App\Models\Field::with('fieldType')
            ->where('is_active', true)
            ->where('status', '!=', 'maintenance')
            ->latest()
            ->take(6)
            ->get();
        return view('home', compact('fields'));
    }

    public function fields(Request $request)
    {
        // Advanced filter page
        $query = \App\Models\Field::with('fieldType')
            ->where('is_active', true)
            ->where('status', '!=', 'maintenance');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('type')) {
            $query->whereHas('fieldType', function ($q) use ($request) {
                $q->where('capacity', $request->type);
            });
        }

        if ($request->filled('price')) {
            $query->where('base_price', '<=', $request->price);
        }

        if ($request->filled('sort')) {
            if ($request->sort === 'price_asc') {
                $query->orderBy('base_price', 'asc');
            } elseif ($request->sort === 'price_desc') {
                $query->orderBy('base_price', 'desc');
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $fields = $query->paginate(12)->withQueryString();
        
        return view('pages.fields', compact('fields'));
    }

    public function fieldDetail(string $slug)
    {
        $field = \App\Models\Field::with('fieldType')->where('slug', $slug)->firstOrFail();
        
        if (!$field->is_active || $field->status === 'maintenance') {
            return redirect()->route('home')->with('error', 'Sân này đang bảo trì hoặc ngừng hoạt động.');
        }
        
        // Prepare data for Booking Wizard
        $timeSlots = \App\Models\TimeSlot::where('is_active', true)
            ->where(function($q) use ($field) {
                $q->whereNull('field_id')->orWhere('field_id', $field->id);
            })
            ->orderBy('start_time')->get();
        
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
        
        if (!$field->is_active || $field->status === 'maintenance') {
            return redirect()->route('home')->with('error', 'Sân này đang bảo trì hoặc ngừng hoạt động.');
        }

        $timeSlots = \App\Models\TimeSlot::whereIn('id', $slotIds)->orderBy('start_time')->get();
        
        if ($timeSlots->isEmpty()) {
            return redirect()->route('home')->with('error', 'Không tìm thấy khung giờ nào.');
        }
        
        return view('pages.checkout', compact('field', 'date', 'timeSlots'));
    }
}
