<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PersonController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\CoffeeSpaceController;
use App\Http\Controllers\AllocationController;

Route::get('/ping', fn() => response()->json(['pong' => true]));

Route::apiResource('people', PersonController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('coffee-spaces', CoffeeSpaceController::class);

Route::apiResource('allocations', AllocationController::class);
// Route::get('/allocations', [AllocationController::class, 'index']);
// Route::post('/allocate', [AllocationController::class, 'assignAutomatically']);
