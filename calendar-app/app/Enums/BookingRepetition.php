<?php

namespace App\Enums;

enum BookingRepetition: string
{
    case NO = 'no';
    case WEEKS = 'weeks';
    case EVEN_WEEKS = 'even_weeks';
    case ODD_WEEKS = 'odd_weeks';
}
