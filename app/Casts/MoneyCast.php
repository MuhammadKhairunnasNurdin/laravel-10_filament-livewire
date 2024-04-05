<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * Filament stores currency values as integers (not floats) to avoid
 * rounding and precision issues — a widely-accepted approach in the
 * Laravel community. However, this requires creating a cast in Laravel
 * that transforms the float into an integer when retrieved and back to an
 * integer when stored in the database. Use the following artisan command
 * to create the cast:
 */
class MoneyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): float
    {
        return round(floatval($value) / 100, precision: 2);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): float
    {
        return round(floatval($value) * 100);
    }
}
