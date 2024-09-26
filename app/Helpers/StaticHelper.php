<?php

/**
 * Get all Purchase status.
 *
 *
 */
if (!function_exists('purchaseStatus')) {
    function purchaseStatus()
    {
        return [
        'En attente'=>'En attente',
        'Validé'=>'Validé',
    ];
    }
}
