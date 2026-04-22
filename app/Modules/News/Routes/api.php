<?php

use App\Modules\News\Controllers\NewsAdminController;
use App\Modules\News\Controllers\NewsPublicController;
use Illuminate\Support\Facades\Route;

Route::prefix('public')->group(function () {
    Route::get('/news', [NewsPublicController::class, 'index']);
    Route::get('/news/{slug}', [NewsPublicController::class, 'show']);
});

Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/news', [NewsAdminController::class, 'index']);
    Route::post('/news', [NewsAdminController::class, 'store']);
    Route::get('/news/{id}', [NewsAdminController::class, 'show']);
    Route::match(['put', 'patch'], '/news/{id}', [NewsAdminController::class, 'update']);
    Route::delete('/news/{id}', [NewsAdminController::class, 'destroy']);
});
