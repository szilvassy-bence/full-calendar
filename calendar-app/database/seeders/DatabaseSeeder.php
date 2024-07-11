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
            'start_time' => carbon::create(2025,9,8,8,0,0)->toTimeString(),
            'end_time' => carbon::create(2025,9,8,10,0,0)->toTimeString(),
            'day' => Day::SUNDAY->value,
            'repetition' => BookingRepetition::NO->value
        ]);

        Booking::factory()->create([
            'start_date' => carbon::create(2024,1,1),
            'start_time' => carbon::create(2024,1,8,10)->toTimeString(),
            'end_time' => carbon::create(2025,1,8,12)->toTimeString(),
            'day' => Day::MONDAY->value,
            'repetition' => BookingRepetition::EVEN_WEEKS->value
        ]);

        Booking::factory()->create([
            'start_date' => carbon::create(2024,1,1),
            'start_time' => carbon::create(2024,1,3,12)->toTimeString(),
            'end_time' => carbon::create(2025,1,3,16)->toTimeString(),
            'day' => Day::WEDNESDAY->value,
            'repetition' => BookingRepetition::EVEN_WEEKS->value
        ]);

        Booking::factory()->create([
            'start_date' => carbon::create(2024,1,1),
            'start_time' => carbon::create(2024,1,5,12)->toTimeString(),
            'end_time' => carbon::create(2025,1,5,16)->toTimeString(),
            'day' => Day::FRIDAY->value,
            'repetition' => BookingRepetition::WEEKS->value
        ]);

        Booking::factory()->create([
            'start_date' => carbon::create(2024,6,1),
            'end_date' => carbon::create(2024,11,30),
            'start_time' => carbon::create(2024,1,5,16)->toTimeString(),
            'end_time' => carbon::create(2025,1,5,20)->toTimeString(),
            'day' => Day::THURSDAY->value,
            'repetition' => BookingRepetition::WEEKS->value
        ]);
    }

}
