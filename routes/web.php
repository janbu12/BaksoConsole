<?php

use App\Domain\Loyalty\BaksoRank;
use App\Enums\UnitStatus;
use App\Http\Controllers\Admin\OperationsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PortalController;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Rental;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $units = Unit::with('categories')->where('status', UnitStatus::Available)->take(6)->get();
    $combos = Combo::where('is_active', true)->take(3)->get();
    $stats = [
        'units' => Unit::count(),
        'members' => User::where('role', 'user')->count(),
        'rentals' => Rental::count(),
        'categories' => Category::count(),
    ];
    $rankTiers = [
        BaksoRank::fromDays(3),
        BaksoRank::fromDays(10),
        BaksoRank::fromDays(20),
        BaksoRank::fromDays(35),
    ];

    return view('welcome', compact('units', 'combos', 'stats', 'rankTiers'));
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', [PortalController::class, 'bookings'])->name('bookings');
    Route::post('/bookings', [PortalController::class, 'storeBooking']);
    Route::delete('/bookings/{booking}', [PortalController::class, 'cancelBooking']);
    Route::get('/rentals', [PortalController::class, 'rentals'])->name('rentals');
    Route::post('/rentals/{rental}/extensions', [PortalController::class, 'extension']);
    Route::post('/rentals/{rental}/deliveries', [PortalController::class, 'delivery']);
    Route::get('/history', [PortalController::class, 'history'])->name('history');
    Route::get('/profile', [PortalController::class, 'profile'])->name('profile');
    Route::put('/profile', [PortalController::class, 'updateProfile']);
});

Route::get('/catalogue', [PortalController::class, 'catalogue'])->name('catalogue');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Modular Page Navigation
    Route::get('/', [OperationsController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/units', [OperationsController::class, 'units'])->name('admin.units');
    Route::get('/categories', [OperationsController::class, 'categories'])->name('admin.categories');
    Route::get('/members', [OperationsController::class, 'members'])->name('admin.members');
    Route::get('/bookings', [OperationsController::class, 'bookings'])->name('admin.bookings');
    Route::get('/returns', [OperationsController::class, 'returns'])->name('admin.returns');
    Route::get('/deliveries', [OperationsController::class, 'deliveries'])->name('admin.deliveries');
    Route::get('/history', [OperationsController::class, 'history'])->name('admin.history');
    Route::get('/history/print', [OperationsController::class, 'printHistory'])->name('admin.history.print');

    // Operations Actions
    Route::post('/units', [OperationsController::class, 'storeUnit']);
    Route::put('/units/{unit}', [OperationsController::class, 'updateUnit']);
    Route::delete('/units/{unit}', [OperationsController::class, 'destroyUnit']);
    
    Route::post('/categories', [OperationsController::class, 'storeCategory']);
    Route::delete('/categories/{category}', [OperationsController::class, 'destroyCategory']);
    
    Route::post('/combos', [OperationsController::class, 'storeCombo']);
    Route::delete('/combos/{combo}', [OperationsController::class, 'destroyCombo']);
    
    Route::post('/members', [OperationsController::class, 'storeMember']);
    Route::put('/members/{member}', [OperationsController::class, 'updateMember']);
    Route::delete('/members/{member}', [OperationsController::class, 'destroyMember']);
    
    Route::post('/rentals', [OperationsController::class, 'startRental']);
    Route::post('/extensions/{extension}', [OperationsController::class, 'reviewExtension']);
    Route::post('/rentals/{rental}/return', [OperationsController::class, 'processReturn']);
    Route::post('/rentals/{rental}/fines', [OperationsController::class, 'addFine']);
    Route::post('/deliveries/{delivery}', [OperationsController::class, 'updateDelivery']);
    Route::post('/transactions/{transaction}/pay', [OperationsController::class, 'markPaymentPaid']);
});
