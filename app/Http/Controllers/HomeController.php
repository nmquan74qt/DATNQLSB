<?php

namespace App\Http\Controllers;

use App\Models\FootballField;
use App\Models\FieldType;
use App\Models\TimeSlot;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $fields = FootballField::with('fieldType')->where('status', 'available')->take(6)->get();
        $fieldTypes = FieldType::all();
        $timeSlots = TimeSlot::orderBy('start_time', 'asc')->get();

        return view('home', compact('fields', 'fieldTypes', 'timeSlots'));
    }
}
