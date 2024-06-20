<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Item;
use Carbon\Carbon;

class AvailabilityController extends Controller
{
    public function show($itemId)
    {
        $bookings = Booking::where('item_id', $itemId)->get();
        $availableDates = [];
        $bookedDates = [];

        foreach ($bookings as $booking) {
            $period = Carbon::parse($booking->start_date)->daysUntil($booking->end_date);
            foreach ($period as $date) {
                $bookedDates[] = $date->toDateString();
            }
        }

        // Generate a range of dates for availability check
        $currentDate = Carbon::now();
        $endDate = Carbon::now()->addMonths(1);
        $period = $currentDate->daysUntil($endDate);

        foreach ($period as $date) {
            if (!in_array($date->toDateString(), $bookedDates)) {
                $availableDates[] = $date->toDateString();
            }
        }

        return response()->json([
            'available' => $availableDates,
            'booked' => $bookedDates
        ]);
    }
}
