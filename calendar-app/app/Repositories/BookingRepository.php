<?php

namespace App\Repositories;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingRepository
{
    protected BookingService $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function store(BookingRequest $request): int
    {
        $booking = new Booking();
        $booking->fill($request->validated());

        if (!$this->bookingService->isInOpeningHours($booking)) {
            $opening_hour = Config::get('office.opening_hour');
            $closing_hour = Config::get('office.closing_hour');
            throw new HttpException(400, 'The booking is not in opening hours: between ' . $opening_hour . ':00 and ' . $closing_hour . ':00, monday to friday!' );
        }

        // to check those existing bookings that are at the same day
        // and have overlap in time
        $DbBookings = $this->bookingsWithSameDayAndOverlappingTime(
            $booking->start_time,
            $booking->end_time,
            $booking->day);

        if (count($DbBookings) > 0) {
            foreach ($DbBookings as $DbBooking) {
                if ($this->bookingService->isOccupied($DbBooking, $booking))
                {
                    throw new HttpException(400, 'The time is occupied!');
                }
            }
        }

        $booking->save();

        return $booking->id;
    }

    private function bookingsWithSameDayAndOverlappingTime(Carbon $start_time, Carbon $end_time, mixed $day)
    {
        $start_time_str = $start_time->format('H:i:s');
        $end_time_str = $end_time->format('H:i:s');

        return Booking::where(function ($query) use ($start_time_str, $end_time_str, $day) {
            $query->where('start_time', '<', $end_time_str)
                ->where('end_time', '>', $start_time_str)
                ->whereRaw('day LIKE ?', [strtolower($day->value)]);
        })
            ->get();
    }
}
