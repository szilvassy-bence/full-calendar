<?php

namespace App\Services;

use App\Enums\BookingRepetition;
use App\Enums\Day;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class BookingService
{

    private int $openingHour;
    private int $closingHour;
    private array $closedDays;

    public function __construct(int $openingHour, int $closingHour, array $closedDays)
    {
        $this->openingHour = $openingHour;
        $this->closingHour = $closingHour;
        $this->closedDays = $closedDays;
    }

    public function isOccupied(Booking $dbBooking, Booking $request): bool
    {
        // end_date may not be initialized, if so we create a close to relative infinity end date
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

    public function isInOpeningHours(Booking $booking): bool
    {
        if (in_array($booking->day, $this->closedDays)) {
            return false;
        }
        if ($booking->start_time < Carbon::createFromTime($this->openingHour)) {
            return false;
        }
        if ($booking->end_time > Carbon::createFromTime($this->closingHour)) {
            return false;
        }
        return true;
    }

}
