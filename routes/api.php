<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('employees', App\Http\Controllers\Api\EmployeeController::class);
Route::apiResource('foodAllergies', App\Http\Controllers\Api\FoodAllergyController::class);
Route::apiResource('foods', App\Http\Controllers\Api\FoodController::class);