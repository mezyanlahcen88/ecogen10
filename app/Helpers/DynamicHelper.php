<?php

use App\Models\Car;
use App\Models\Driver;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

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

/**
 * Get all suppliers.
 *
 *
 */
if (!function_exists('suppliers')) {
    function suppliers()
    {
        return Supplier::where('active', 1)->pluck('name_fr','id');
    }
}

/**
 * Get all categories.
 *
 *
 */
if (!function_exists('categories')) {
    function categories()
    {
        return Category::where('active', 1)->pluck('name','id');
    }
}

/**
 * Get all products.
 *
 *
 */
if (!function_exists('products')) {
    function products()
    {
        return Product::where('active', 1)->pluck('name_fr','id');
    }
}
