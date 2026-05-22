<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SubscriptionController;

//---API Resource untuk Subscriptions
Route::apiResource('subscriptions', SubscriptionController::class);

//---API Resource untuk Customers
Route::apiResource('customers', CustomerController::class);

//---API Resource untuk Services
Route::apiResource("services", ServiceController::class);
Route::patch("services/{service}/activate", [
    ServiceController::class,
    "activate",
]);
Route::patch("services/{service}/deactivate", [
    ServiceController::class,
    "deactivate",
]);

// Route untuk root API, bisa digunakan untuk health check atau welcome message
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Welcome to the API!'
    ]);
});