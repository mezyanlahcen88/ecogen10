<?php

use App\Models\Car;
use App\Models\Driver;

/**
 * Get all cars.
 *
 * @return int|null The ID of the default language, or null if not found.
 */
if (!function_exists('cars')) {
    function cars()
    {
        return Car::where('active', 1)->pluck('type','id') ?? [];
    }
}


/**
 * Get all drivers.
 *
 * @return int|null The ID of the default language, or null if not found.
 */
if (!function_exists('drivers')) {
    function drivers()
    {
        return Driver::where('active', 1)->pluck('last_name','id');
    }
}
