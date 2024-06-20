<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Item;

class BookingController extends Controller
{
    public function create()
    {
        $items = Item::all()->groupBy('name');
        return view('index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Controleer beschikbaarheid
        $conflictingBooking = Booking::where('item_id', $request->item_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($query) use ($request) {
                          $query->where('start_date', '<=', $request->start_date)
                                ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->exists();

        if ($conflictingBooking) {
            return redirect()->back()->withErrors(['conflict' => 'Selected date range is not available for the chosen item.']);
        }

        Booking::create([
            'item_id' => $request->item_id,
            'user_id' => $request->user_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('items.index')->with('success', 'Booking created successfully');
    }
}
