<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeliveryController;



//chaneg the status
Route::post('/deliveries/changestatus', [DeliveryController::class, 'changeStatus'])->name('deliveries.changestatus');
//to permanently delete
Route::delete('/deliveries/{id}/force_delete', [DeliveryController::class, 'forceDelete'])->name('deliveries.forceDelete');
// to restore
Route::put('/deliveries/{id}/restore', [DeliveryController::class, 'restore'])->name('deliveries.restore');
//liste all deleted
Route::get('/deliveries/trashed', [DeliveryController::class, 'trashed'])->name('deliveries.trashed');
Route::get('/deliveries/{commandId?}/create', [DeliveryController::class, 'create'])->name('deliveries.create');
Route::resource('deliveries', DeliveryController::class)->except('create');


//copy this two lines in web.php
