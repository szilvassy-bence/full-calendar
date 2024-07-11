<?php

namespace App\Models;

use App\Casts\EnumCast;
use App\Enums\BookingRepetition;
use App\Enums\Day;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $fillable = [
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'day',
        'repetition',
        'user',
    ];

    protected $casts = [
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'repetition' => EnumCast::class . ':' . BookingRepetition::class,
        'day' => EnumCast::class . ':' . Day::class,
    ];





//    public function getEndDateAttribute($value)
//    {
//        return Carbon::parse($value)->format('Y-m-d');
//    }
//    public function getStartTimeAttribute($value)
//    {
//        return Carbon::parse($value)->format('H:i');
//    }
//
//    public function getEndTimeAttribute($value)
//    {
//        return Carbon::parse($value)->format('H:i');
//    }
//
//    public function getRepetitionAttribute($value)
//    {
//        return BookingRepetition::from($value);
//    }
}
