<?php

use App\Http\Controllers\Admin\OperationsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
Route::view('/dashboard', 'dashboard')->middleware('auth')->name('dashboard');
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', [OperationsController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/units', [OperationsController::class, 'storeUnit']);
    Route::post('/categories', [OperationsController::class, 'storeCategory']);
    Route::post('/combos', [OperationsController::class, 'storeCombo']);
    Route::post('/members', [OperationsController::class, 'storeMember']);
    Route::put('/members/{member}', [OperationsController::class, 'updateMember']);
    Route::delete('/members/{member}', [OperationsController::class, 'destroyMember']);
    Route::post('/bookings/{booking}/confirm', [OperationsController::class, 'confirm']);
    Route::post('/bookings/{booking}/start', [OperationsController::class, 'start']);
    Route::post('/extensions/{extension}', [OperationsController::class, 'reviewExtension']);
    Route::post('/rentals/{rental}/return', [OperationsController::class, 'returnRental']);
    Route::post('/rentals/{rental}/fines', [OperationsController::class, 'storeFine']);
    Route::post('/deliveries/{delivery}', [OperationsController::class, 'delivery']);
    Route::post('/transactions/{transaction}/pay', [OperationsController::class, 'pay']);
    Route::get('/history/print', [OperationsController::class, 'printHistory']);
});
Route::get('/catalogue', [PortalController::class, 'catalogue'])->name('catalogue');
Route::middleware('auth')->group(function () {
    Route::get('/bookings', [PortalController::class, 'bookings']);
    Route::post('/bookings', [PortalController::class, 'storeBooking']);
    Route::delete('/bookings/{booking}', [PortalController::class, 'cancelBooking']);
    Route::get('/rentals', [PortalController::class, 'rentals']);
    Route::post('/rentals/{rental}/extensions', [PortalController::class, 'extension']);
    Route::post('/rentals/{rental}/deliveries', [PortalController::class, 'delivery']);
    Route::get('/history', [PortalController::class, 'history']);
    Route::get('/profile', [PortalController::class, 'profile']);
    Route::put('/profile', [PortalController::class, 'updateProfile']);
});
