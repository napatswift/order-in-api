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
Route::apiResource('payments', App\Http\Controllers\Api\PaymentController::class);
Route::apiResource('promotions', App\Http\Controllers\Api\PromotionController::class);
Route::apiResource('ratings', App\Http\Controllers\Api\RatingController::class);
Route::apiResource('tables', App\Http\Controllers\Api\TableController::class);
Route::apiResource('orders', App\Http\Controllers\Api\OrderController::class);
Route::apiResource('orderDescriptions', App\Http\Controllers\Api\OrderDescriptionController::class);
Route::apiResource('reviews', App\Http\Controllers\Api\ReviewController::class);
Route::apiResource('categories', App\Http\Controllers\Api\CategoryController::class);

// routes/api.php
use App\Http\Controllers\Api\AuthController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('register', [AuthController::class, 'register']);
});