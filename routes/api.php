<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;

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
Route::post('login',[VehicleController::class,'login']);
Route::post('register',[VehicleController::class,'register']);
Route::middleware('auth:sanctum')->group (function () {
    Route::apiResource('vehicles',VehicleController::class);
    Route::post('logout',[VehicleController::class,'logout']);

});
