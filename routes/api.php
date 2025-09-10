<?php

use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\ResetAdjustmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/upload', [FileController::class, 'upload']);
Route::get('/download/{filename}', [FileController::class, 'download']);
Route::get('/app-download/{filename}', [FileController::class, 'AppDownload']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/inventory-adjustment/{id}', [ResetAdjustmentController::class, 'update']);
