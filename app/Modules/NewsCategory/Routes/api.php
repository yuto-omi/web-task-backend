<?php

use App\Modules\NewsCategory\Controllers\NewsCategoryAdminController;
use App\Modules\NewsCategory\Controllers\NewsCategoryPublicController;
use Illuminate\Support\Facades\Route;

Route::prefix('public')->group(function () {
    Route::get('/news-categories', [NewsCategoryPublicController::class, 'index']);
    Route::get('/news-categories/{slug}', [NewsCategoryPublicController::class, 'show']);
});

Route::prefix('admin')->middleware('auth:api')->group(function () {
    Route::get('/news-categories', [NewsCategoryAdminController::class, 'index']);
    Route::post('/news-categories', [NewsCategoryAdminController::class, 'store']);
    Route::get('/news-categories/{id}', [NewsCategoryAdminController::class, 'show']);
    Route::match(['put', 'patch'], '/news-categories/{id}', [NewsCategoryAdminController::class, 'update']);
    Route::delete('/news-categories/{id}', [NewsCategoryAdminController::class, 'destroy']);
});
