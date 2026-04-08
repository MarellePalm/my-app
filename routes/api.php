<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RadiologyStudyController;

Route::get('/radiology-studies', [RadiologyStudyController::class, 'index']);
Route::get('/radiology-studies/{id}', [RadiologyStudyController::class, 'show']);
Route::post('/radiology-studies', [RadiologyStudyController::class, 'store']);
Route::post('/radiology-studies/{id}', [RadiologyStudyController::class, 'update']);
Route::delete('/radiology-studies/{id}', [RadiologyStudyController::class, 'destroy']);