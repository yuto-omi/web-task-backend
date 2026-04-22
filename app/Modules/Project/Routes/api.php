<?php

use App\Modules\Project\Controllers\ProjectController;
use App\Modules\Project\Controllers\ProjectPhaseController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    // プロジェクトCRUD
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    Route::patch('/projects/{id}/status', [ProjectController::class, 'updateStatus']);

    // フェーズCRUD
    Route::get('/projects/{projectId}/phases', [ProjectPhaseController::class, 'index']);
    Route::post('/projects/{projectId}/phases', [ProjectPhaseController::class, 'store']);
    Route::put('/projects/{projectId}/phases/{id}', [ProjectPhaseController::class, 'update']);
    Route::delete('/projects/{projectId}/phases/{id}', [ProjectPhaseController::class, 'destroy']);
    Route::patch('/projects/{projectId}/phases/{id}/status', [ProjectPhaseController::class, 'updateStatus']);
    Route::put('/projects/{projectId}/phases/sort', [ProjectPhaseController::class, 'sort']);
});
