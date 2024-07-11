<?php

namespace Tests\Unit;

use App\Enums\BookingRepetition;
use App\Enums\Day;
use App\Models\Booking;
use App\Services\BookingService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Config;

final class TestBookingService extends TestCase
{

    private $bookingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookingService = new BookingService();
    }

    protected function tearDown() : void
    {
        $this->bookingService = null;
    }

    public function test_no_rep_same_date_returns_true():void
    {
        $db = new Booking();
        $db->repetition = BookingRepetition::NO;
        $db->start_date = Carbon::create('2024-01-01');

        $request = new Booking();
        $request->repetition = BookingRepetition::NO;
        $request->start_date = Carbon::create('2024-01-01');

        $this->assertTrue($this->bookingService->isOccupied($db, $request));
    }


    public function test_no_rep_different_date_returns_false(): void
    {
        $db = new Booking();
        $db->repetition = BookingRepetition::NO;
        $db->start_date = Carbon::create('2024-01-01');

        $request = new Booking();
        $request->repetition = BookingRepetition::NO;
        $request->start_date = Carbon::create('2024-01-08');


        $this->assertFalse($this->bookingService->isOccupied($db, $request));
    }

    public function test_db_no_rep_request_rep_contains_returns_true(): void
    {
        $db = new Booking();
        $db->repetition = BookingRepetition::NO;
        $db->start_date = Carbon::create('2024-01-12');

        $request = new Booking();
        $request->repetition = BookingRepetition::WEEKS;
        $request->start_date = Carbon::create('2024-01-01');
        $request->end_date = Carbon::create('2024-01-20');

        $this->assertTrue($this->bookingService->isOccupied($db, $request));
    }

    public function test_db_no_rep_request_rep_not_contains_returns_false(): void
    {
        $db = new Booking();
        $db->repetition = BookingRepetition::NO;
        $db->start_date = Carbon::create('2024-01-12');

        $request = new Booking();
        $request->repetition = BookingRepetition::WEEKS;
        $request->start_date = Carbon::create('2024-01-01');
        $request->end_date = Carbon::create('2024-01-10');

        $request2 = new Booking();
        $request2->repetition = BookingRepetition::WEEKS;
        $request2->start_date = Carbon::create('2024-01-15');
        $request2->end_date = Carbon::create('2024-01-20');

        $this->assertFalse($this->bookingService->isOccupied($db, $request));
        $this->assertFalse($this->bookingService->isOccupied($db, $request2));
    }



    public function test_weeks_rep_has_overlap_returns_true(): void
    {
        $booking = new Booking();
        $booking->repetition = BookingRepetition::WEEKS;
        $booking->start_date = Carbon::create('2024-01-01');
        $booking->end_date = Carbon::create('2024-01-08');

        $request = new Booking();
        $request->repetition = BookingRepetition::WEEKS;
        $request->start_date = Carbon::create('2024-01-06');
        $request->end_date = Carbon::create('2024-01-09');

        $this->assertTrue($this->bookingService->isOccupied($booking, $request));
    }

    public function test_weeks_rep_has_no_overlap_returns_false(): void
    {
        $booking = new Booking();
        $booking->repetition = BookingRepetition::WEEKS;
        $booking->start_date = Carbon::create('2024-01-01');
        $booking->end_date = Carbon::create('2024-01-15');

        $request = new Booking();
        $request->repetition = BookingRepetition::WEEKS;
        $request->start_date = Carbon::create('2024-01-16');
        $request->end_date = Carbon::create('2024-01-20');

        $this->assertFalse($this->bookingService->isOccupied($booking, $request));
    }

    public function test_even_weeks_rep_has_overlap_returns_true(): void
    {
        $booking = new Booking();
        $booking->repetition = BookingRepetition::EVEN_WEEKS;
        $booking->start_date = Carbon::create('2024-01-01');
        $booking->end_date = Carbon::create('2024-01-08');

        $request = new Booking();
        $request->repetition = BookingRepetition::EVEN_WEEKS;
        $request->start_date = Carbon::create('2024-01-06');
        $request->end_date = Carbon::create('2024-01-09');

        $this->assertTrue($this->bookingService->isOccupied($booking, $request));
    }

    public function test_even_weeks_rep_has_no_overlap_returns_false(): void
    {
        $booking = new Booking();
        $booking->repetition = BookingRepetition::EVEN_WEEKS;
        $booking->start_date = Carbon::create('2024-01-01');
        $booking->end_date = Carbon::create('2024-01-15');

        $request = new Booking();
        $request->repetition = BookingRepetition::EVEN_WEEKS;
        $request->start_date = Carbon::create('2024-01-16');
        $request->end_date = Carbon::create('2024-01-20');

        $this->assertFalse($this->bookingService->isOccupied($booking, $request));
    }

    public function test_odd_weeks_rep_has_overlap_returns_true(): void
    {
        $booking = new Booking();
        $booking->repetition = BookingRepetition::ODD_WEEKS;
        $booking->start_date = Carbon::create('2024-01-01');
        $booking->end_date = Carbon::create('2024-01-08');

        $request = new Booking();
        $request->repetition = BookingRepetition::ODD_WEEKS;
        $request->start_date = Carbon::create('2024-01-06');
        $request->end_date = Carbon::create('2024-01-09');

        $this->assertTrue($this->bookingService->isOccupied($booking, $request));
    }

    public function test_odd_weeks_rep_has_no_overlap_returns_false(): void
    {
        $booking = new Booking();
        $booking->repetition = BookingRepetition::ODD_WEEKS;
        $booking->start_date = Carbon::create('2024-01-01');
        $booking->end_date = Carbon::create('2024-01-15');

        $request = new Booking();
        $request->repetition = BookingRepetition::ODD_WEEKS;
        $request->start_date = Carbon::create('2024-01-16');
        $request->end_date = Carbon::create('2024-01-20');

        $this->assertFalse($this->bookingService->isOccupied($booking, $request));
    }

    public function test_odd_week_and_even_week_rep_has_overlap_returns_false(): void
    {

        $booking = new Booking();
        $booking->repetition = BookingRepetition::EVEN_WEEKS;
        $booking->start_date = Carbon::create('2024-01-01');
        $booking->end_date = Carbon::create('2024-01-15');

        $request = new Booking();
        $request->repetition = BookingRepetition::ODD_WEEKS;
        $request->start_date = Carbon::create('2024-01-01');
        $request->end_date = Carbon::create('2024-01-20');

        $this->assertFalse($this->bookingService->isOccupied($booking, $request));
    }

    public function test_is_in_opening_saturday_returns_false(): void
    {
        $booking = new Booking();
        $booking->day = Day::SATURDAY;
        $booking->start_time = Carbon::createFromTime(15);
        $booking->end_time = Carbon::createFromTime(18);
        $this->assertFalse($this->bookingService->isInOpeningHours($booking));
    }

    public function test_is_in_opening_monday_before_opening_returns_false(): void
    {
        $booking = new Booking();
        $booking->day = Day::MONDAY;
        $booking->start_time = Carbon::createFromTime(7);
        $booking->end_time = Carbon::createFromTime(9);
        $this->assertFalse($this->bookingService->isInOpeningHours($booking));
    }

    public function test_is_in_opening_monday_after_closing_returns_false(): void
    {
        $booking = new Booking();
        $booking->day = Day::MONDAY;
        $booking->start_time = Carbon::createFromTime(7);
        $booking->end_time = Carbon::createFromTime(9);
        $this->assertFalse($this->bookingService->isInOpeningHours($booking));
    }

    public function test_is_in_opening_monday_before_closing_returns_true(): void
    {
        $booking = new Booking();
        $booking->day = Day::MONDAY;
        $booking->start_time = Carbon::createFromTime(15);
        $booking->end_time = Carbon::createFromTime(18);
        $this->assertTrue($this->bookingService->isInOpeningHours($booking));
    }

    public function test_is_in_opening_monday_at_opening_at_closing_returns_true(): void
    {
        $booking = new Booking();
        $booking->day = Day::MONDAY;
        $booking->start_time = Carbon::createFromTime(8);
        $booking->end_time = Carbon::createFromTime(20);
        $this->assertTrue($this->bookingService->isInOpeningHours($booking));
    }
}
