<?php

use App\Http\Controllers\Api\StudentImportController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/import-students', [StudentImportController::class, 'store']);
Route::post('/import-students-file', [StudentImportController::class, 'upload']);

});

