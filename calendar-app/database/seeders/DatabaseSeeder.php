<?php

namespace Database\Seeders;

use App\Enums\BookingRepetition;
use App\Enums\Day;
use App\Models\Booking;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DateInterval;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Booking::factory()->create([
            'start_date' => carbon::create(2024,9,8),
            'end_date' => carbon::create(2024,9,8),
            'start_time' => carbon::createFromTime(8),
            'end_time' => carbon::createFromTime(10),
            'day' => Day::SUNDAY,
            'repetition' => BookingRepetition::NO
        ]);

        Booking::factory()->create([
            'start_date' => carbon::create(2024,1,1),
            'start_time' => carbon::createFromTime(10),
            'end_time' => carbon::createFromTime(12),
            'day' => Day::MONDAY,
            'repetition' => BookingRepetition::EVEN_WEEKS
        ]);

        Booking::factory()->create([
            'start_date' => carbon::create(2024,1,1),
            'start_time' => carbon::createFromTime(12),
            'end_time' => carbon::createFromTime(16),
            'day' => Day::WEDNESDAY,
            'repetition' => BookingRepetition::ODD_WEEKS
        ]);

        Booking::factory()->create([
            'start_date' => carbon::create(2024,1,1),
            'start_time' => carbon::createFromTime(10),
            'end_time' => carbon::createFromTime(16),
            'day' => Day::FRIDAY,
            'repetition' => BookingRepetition::WEEKS
        ]);

        Booking::factory()->create([
            'start_date' => carbon::create(2024,6,1),
            'end_date' => carbon::create(2024,11,30),
            'start_time' => carbon::createFromTime(16),
            'end_time' => carbon::createFromTime(20),
            'day' => Day::THURSDAY,
            'repetition' => BookingRepetition::WEEKS
        ]);
    }

}
