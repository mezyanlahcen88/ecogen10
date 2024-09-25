<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceptionController;



//chaneg the status
Route::post('/receptions/changestatus', [ReceptionController::class, 'changeStatus'])->name('receptions.changestatus');
//to permanently delete
Route::delete('/receptions/{id}/force_delete', [ReceptionController::class, 'forceDelete'])->name('receptions.forceDelete');
// to restore
Route::put('/receptions/{id}/restore', [ReceptionController::class, 'restore'])->name('receptions.restore');
//liste all deleted
Route::get('/receptions/trashed', [ReceptionController::class, 'trashed'])->name('receptions.trashed');
Route::resource('receptions', ReceptionController::class);


