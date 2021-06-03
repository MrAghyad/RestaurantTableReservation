<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantTableController;
use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These are the APIs of the project
|
*/

// group apis by api_version
Route::prefix('v1')->group(function () {
    // definition of route that creates new user, using function store defined in UserController class
    Route::post('/user', [UserController::class, 'store']);

    // definition of route that allow users to login, using function login defined in UserController class
    Route::post('/user/login', [UserController::class, 'login']);

    // definition of resource route for "restaurant_tables" that uses 'index, delete, and store) functions in RestaurantTableController class
    Route::resource('/table', RestaurantTableController::class)->except([
        'create', 'show', 'update'
    ]);

    // definition of resource route for "reservations" that uses 'index, delete, and store) functions in ReservationController class
    Route::resource('/reservation', ReservationController::class)->except([
        'create', 'show', 'update'
    ]);

    // definition of route that gets the current day's reservations, using function "getTodayReservations" defined
    // in ReservationController class
    Route::get('/reservation', [ReservationController::class, 'getTodayReservations']);

    // definition of route that gets all reservations, using function "getAll" defined in ReservationController class
    Route::get('/reservation/all', [ReservationController::class, 'getAll']);

    // definition of route that returns available time slots, using function "checkAvailable" defined in ReservationController class
    Route::get('/reservation/available/{seats}', [ReservationController::class, 'checkAvailable'])->whereNumber('seats');
});

