<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceController;

Route::apiResource("services", ServiceController::class);
Route::patch("services/{service}/activate", [
    ServiceController::class,
    "activate",
]);
Route::patch("services/{service}/deactivate", [
    ServiceController::class,
    "deactivate",
]);

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'Welcome to the API!'
    ]);
});