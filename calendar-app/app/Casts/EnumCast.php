<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class EnumCast implements CastsAttributes
{

    protected $enum;
    public function __construct($enum)
    {
        $this->enum = $enum;
    }
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        return $value !== null ? $this->enum::from($value) : null;
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (is_string($value)) {
            $value = $this->enum::from($value);
        }

        return $value->value;
    }
}
