<?php

use App\Domain\Loyalty\BaksoRank;
use App\Enums\UnitStatus;
use App\Http\Controllers\Admin\OperationsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\PaymentController;
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
    Route::post('/rentals/{rental}/pay', [PaymentController::class, 'pay'])->name('rentals.pay');
    Route::post('/rentals/{rental}/simulate-pay', [PaymentController::class, 'simulate'])->name('rentals.pay.simulate');
    Route::get('/rentals/{rental}/invoice', [InvoiceController::class, 'download'])->name('rentals.invoice.download');
    Route::get('/history', [PortalController::class, 'history'])->name('history');
    Route::get('/profile', [PortalController::class, 'profile'])->name('profile');
    Route::put('/profile', [PortalController::class, 'updateProfile']);
    Route::post('/profile/avatar', [PortalController::class, 'updateAvatar']);
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});

// Midtrans Webhook Callback & Return URLs
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])->name('midtrans.notification');
Route::post('/midtrans/callback', [PaymentController::class, 'notification']);
Route::get('/midtrans/finish', [PaymentController::class, 'finish'])->name('midtrans.finish');
Route::get('/midtrans/unfinish', [PaymentController::class, 'unfinish'])->name('midtrans.unfinish');
Route::get('/midtrans/error', [PaymentController::class, 'error'])->name('midtrans.error');

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
    Route::get('/leaderboard', [LeaderboardController::class, 'adminIndex'])->name('admin.leaderboard');
    Route::get('/resources', [OperationsController::class, 'resources'])->name('admin.resources');
    Route::get('/resources/metrics', [OperationsController::class, 'resourceMetrics'])->name('admin.resources.metrics');

    // Operations Actions
    Route::post('/units', [OperationsController::class, 'storeUnit']);
    Route::put('/units/{unit}', [OperationsController::class, 'updateUnit']);
    Route::delete('/units/{unit}', [OperationsController::class, 'destroyUnit']);

    Route::post('/categories', [OperationsController::class, 'storeCategory']);
    Route::delete('/categories/{category}', [OperationsController::class, 'destroyCategory']);

    Route::post('/games', [OperationsController::class, 'storeGame']);
    Route::delete('/games/{game}', [OperationsController::class, 'destroyGame']);

    Route::post('/combos', [OperationsController::class, 'storeCombo']);
    Route::delete('/combos/{combo}', [OperationsController::class, 'destroyCombo']);

    Route::post('/members', [OperationsController::class, 'storeMember']);
    Route::put('/members/{member}', [OperationsController::class, 'updateMember']);
    Route::delete('/members/{member}', [OperationsController::class, 'destroyMember']);

    Route::post('/rentals/{rental}/handover', [OperationsController::class, 'handoverUnit']);
    Route::post('/extensions/{extension}', [OperationsController::class, 'reviewExtension']);
    Route::post('/rentals/{rental}/return', [OperationsController::class, 'processReturn']);
    Route::post('/rentals/{rental}/fines', [OperationsController::class, 'addFine']);
    Route::post('/rentals/{rental}/confirm-fine-paid', [OperationsController::class, 'confirmFinePaid']);
    Route::post('/deliveries/{delivery}', [OperationsController::class, 'updateDelivery']);
    Route::post('/transactions/{transaction}/pay', [OperationsController::class, 'markPaymentPaid']);
});
