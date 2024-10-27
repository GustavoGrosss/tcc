<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LembreteController;
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
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['apiJwt']], function () {
    Route::post('confirmar', [LembreteController::class, 'recebeConfirmacao']);
    Route::get('relatorio', [LembreteController::class, 'relatorio']);
});
