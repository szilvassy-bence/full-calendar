<?php

namespace App\Services;

use App\Enums\BookingRepetition;
use App\Enums\Day;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class BookingService
{

    const OPENING_HOUR = 8;
    CONST CLOSING_HOUR = 20;

    public function isOccupied(Booking $dbBooking, Booking $request): bool
    {
        $dbBookingEndDate = $dbBooking->end_date ?? Carbon::create(9000);
        $requestEndDate = $request->end_date ?? Carbon::create(9000);

        if ($dbBooking->repetition === BookingRepetition::NO && $request->repetition === BookingRepetition::NO) {
            return $dbBooking->start_date->isSameDay($request->start_date);
        } elseif ($dbBooking->repetition === BookingRepetition::NO && $request->repetition !== BookingRepetition::NO) {
            return $dbBooking->start_date->greaterThanOrEqualTo($request->start_date)
                && $dbBooking->start_date->lessThanOrEqualTo($requestEndDate);
        } elseif ($dbBooking->repetition !== BookingRepetition::NO && $request->repetition === BookingRepetition::NO) {
            return $dbBooking->start_date->lessThanOrEqualTo($request->start_date)
                && $dbBookingEndDate->greaterThanOrEqualTo($request->start_date);
        } elseif ($dbBooking->repetition === $request->repetition
                || $dbBooking->repetition === BookingRepetition::WEEKS
                || $request->repetition === BookingRepetition::WEEKS
        ) {
            return $dbBooking->start_date->lessThanOrEqualTo($requestEndDate)
                    && $dbBookingEndDate->greaterThanOrEqualTo($request->start_date);
        }
        return false;
    }

    public function isInOpeningHours(Booking $booking)
    {

//        $openingHour = Config::get('office.opening_hour');
//        $closingHour = Config::get('office.closing_hour');

        if ($booking->day === Day::SATURDAY || $booking->day === Day::SUNDAY) {
            return false;
        }
        if ($booking->start_time < Carbon::createFromTime(self::OPENING_HOUR)) {
            return false;
        }
        if ($booking->end_time > Carbon::createFromTime(self::CLOSING_HOUR)) {
            return false;
        }
        return true;
    }

}
