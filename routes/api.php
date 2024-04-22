<?php

use App\Http\Controllers\Auth\AuthBothController;
use App\Http\Controllers\Auth\ServiceOwnerAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Flight\BookingFlightController;
use App\Http\Controllers\Flight\FlightController;
use App\Http\Controllers\Flight\TicketController;
use App\Http\Controllers\WalletController;
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
//Registration for user and serviceOwner
Route::post('user/register', [UserAuthController::class, 'register']);
Route::post('serviceOwner/register', [ServiceOwnerAuthController::class, 'register']);

//Login for user and serviceOwner
Route::post('user/login', [UserAuthController::class, 'login']);
Route::post('serviceOwner/login', [ServiceOwnerAuthController::class, 'login']);

//Login Both
Route::post('/loginBoth',[AuthBothController::class,'loginBoth']);



//Both
Route::middleware(['auth:user,serviceOwner'])->group(function () {
    //Auth
    Route::get('/logoutBoth', [AuthBothController::class, 'logoutBoth']);
    Route::get('/profileBoth', [AuthBothController::class, 'profileBoth']);

    //Wallet
        Route::get('/wallets/{id}', [WalletController::class, 'show']);

    //Flight
         // CRUD
            Route::get('/flights', [FlightController::class, 'index']);
            Route::get('/flight/{id}', [FlightController::class, 'show']);
        //search by destination
        Route::post('/flights/searchByDestination',[FlightController::class,'searchByDestination']);

});




// Only for users
Route::middleware(['auth:user', 'type.user'])->group(function () {
   Route::get('/users/profile',[UserAuthController::class,'profile']);
    Route::get('/users/logout',[UserAuthController::class,'logout']);
    //search by price
    Route::post('/flights/searchByPrice',[FlightController::class,'searchByPrice']);
    //CRUD ON BOOK A FLIGHT
    //book a flight
    Route::post('/flights/booking',[BookingFlightController::class,'create']);
    Route::post('/flights/booking/{id}',[BookingFlightController::class,'update']);

});







// Only for service_owners
Route::middleware(['auth:serviceOwner', 'type.serviceOwner'])->group(function () {
    //Auth
   Route::get('/service_owners/profile',[ServiceOwnerAuthController::class,'profile']);
   Route::get('/service_owners/logout',[ServiceOwnerAuthController::class,'logout']);

   //Wallet
        Route::get('/wallets', [WalletController::class, 'index']);
        Route::post('/service_owners/wallet',[WalletController::class,'create']);
    //Flight
        //CRUD
        Route::post('/service_owners/flight', [FlightController::class, 'create']);
        Route::post('/service_owners/flight/{id}', [FlightController::class, 'update']);
        Route::delete('/service_owners/flight/{id}', [FlightController::class, 'delete']);


});
