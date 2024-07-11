<?php

return [
    'opening_hour' => 8, // Example opening hour
    'closing_hour' => 20, // Example closing hour
    'closed_days' => [
        \App\Enums\Day::SATURDAY,
        \App\Enums\Day::SUNDAY
    ]
];
