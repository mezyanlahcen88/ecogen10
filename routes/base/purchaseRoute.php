<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseController;



//chaneg the status
Route::post('/purchases/changestatus', [PurchaseController::class, 'changeStatus'])->name('purchases.changestatus');
//to permanently delete
Route::delete('/purchases/{id}/force_delete', [PurchaseController::class, 'forceDelete'])->name('purchases.forceDelete');
// to restore
Route::put('/purchases/{id}/restore', [PurchaseController::class, 'restore'])->name('purchases.restore');
//liste all deleted
Route::get('/purchases/trashed', [PurchaseController::class, 'trashed'])->name('purchases.trashed');
Route::resource('purchases', PurchaseController::class);


//copy this two lines in web.php
