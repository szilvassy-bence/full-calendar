<?php

use App\Enums\BookingRepetition;
use App\Enums\Day;
use App\Models\BookingGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('repetition', [
                BookingRepetition::NO->value,
                BookingRepetition::WEEKS->value,
                BookingRepetition::EVEN_WEEKS->value,
                BookingRepetition::ODD_WEEKS->value])->default(BookingRepetition::NO->value); // php 8 enum
            $table->enum('day', [Day::MONDAY->value, Day::TUESDAY->value, Day::WEDNESDAY->value, Day::THURSDAY->value,
                Day::FRIDAY->value, Day::SATURDAY->value, Day::SUNDAY->value]);
            $table->string('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
