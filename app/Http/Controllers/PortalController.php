<?php

namespace App\Http\Controllers;

use App\Application\Bookings\CreateBooking;
use App\Application\Transactions\RecalculateTransaction;
use App\Domain\Loyalty\BaksoRank;
use App\Domain\Rentals\RentalWarning;
use App\Domain\Rentals\SmartPickRecommender;
use App\Enums\BookingStatus;
use App\Enums\DeliveryStatus;
use App\Enums\ExtensionStatus;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Delivery;
use App\Models\Game;
use App\Models\Rental;
use App\Models\RentalExtension;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function dashboard(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user->role->value === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $activeRentals = $user->rentals()
            ->whereIn('status', [RentalStatus::Active, RentalStatus::Overdue])
            ->with(['unit', 'transaction', 'deliveries', 'extensions'])
            ->latest()
            ->get();

        $warnings = $activeRentals->mapWithKeys(fn ($r) => [$r->id => RentalWarning::details($r->due_date)]);

        $activeBookings = $user->bookings()
            ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
            ->with('unit')
            ->latest()
            ->get();

        $completedRentals = $user->rentals()->where('status', RentalStatus::Returned);
        $totalDays = (int) (clone $completedRentals)->sum('duration_days');
        $rank = BaksoRank::fromDays($totalDays);

        $recommendedUnits = Unit::where('status', UnitStatus::Available)
            ->with('categories')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'user',
            'activeRentals',
            'warnings',
            'activeBookings',
            'totalDays',
            'rank',
            'recommendedUnits'
        ));
    }

    public function catalogue(Request $request): View
    {
        $query = Unit::query()->with([
            'categories',
            'games',
            'bookings' => fn ($bookings) => $bookings
                ->whereIn('status', [BookingStatus::Pending, BookingStatus::Confirmed])
                ->whereDate('end_date', '>=', today())
                ->orderBy('start_date'),
            'rentals' => fn ($rentals) => $rentals
                ->whereNull('booking_id')
                ->whereIn('status', [RentalStatus::Pending, RentalStatus::Active, RentalStatus::Overdue])
                ->whereDate('due_date', '>=', today())
                ->orderBy('start_date'),
        ]);

        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%')
                  ->orWhere('code', 'like', '%'.$searchTerm.'%')
                  ->orWhere('description', 'like', '%'.$searchTerm.'%')
                  ->orWhereHas('games', fn ($g) => $g->where('name', 'like', '%'.$searchTerm.'%'));
            });
        }

        if ($request->filled('players')) {
            $query->where('max_players', '>=', (int) $request->players);
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', fn ($c) => $c->where('categories.id', $request->category));
        }

        if ($request->filled('game')) {
            $query->whereHas('games', fn ($g) => $g->where('games.id', $request->game));
        }

        if ($request->filled('firmware_type')) {
            $query->where('firmware_type', $request->firmware_type);
        }

        $units = $query->orderBy('daily_price')->get();

        // Evaluate SmartPick recommendation
        $evaluatedUnits = SmartPickRecommender::evaluate(
            $units,
            $request->filled('players') ? (int) $request->players : null,
            $request->filled('duration') ? (int) $request->duration : null,
            $request->filled('budget') ? (float) $request->budget : null,
            $request->filled('category') ? (int) $request->category : null
        );

        $categories = Category::orderBy('name')->get();
        $games = Game::orderBy('name')->get();
        $combos = Combo::where('is_active', true)->get();

        return view('portal.catalogue', [
            'units' => $evaluatedUnits,
            'categories' => $categories,
            'games' => $games,
            'combos' => $combos,
        ]);
    }

    public function bookings(Request $request): View
    {
        $bookings = $request->user()
            ->bookings()
            ->with('unit')
            ->latest()
            ->paginate(10);

        return view('portal.bookings', compact('bookings'));
    }

    public function storeBooking(Request $request, CreateBooking $action): RedirectResponse
    {
        $data = $request->validate([
            'unit_id' => ['required', 'exists:units,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'delivery_method' => ['required', 'in:pickup,delivery'],
            'delivery_address' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:500'],
            'contact_number' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:30'],
            'requested_games' => ['nullable', 'array'],
        ]);

        try {
            $unit = Unit::findOrFail($data['unit_id']);
            $action->handle(
                $request->user(),
                $unit,
                $data['start_date'],
                $data['end_date'],
                $data['notes'] ?? null,
                $data['delivery_method'],
                $data['delivery_address'] ?? null,
                $data['contact_number'] ?? null,
                $data['requested_games'] ?? null
            );
        } catch (\DomainException $e) {
            return back()->withInput()->withErrors(['schedule' => $e->getMessage()]);
        }

        return redirect('/rentals')->with('success', 'Booking berhasil! Silakan selesaikan pembayaran pada tagihan di bawah ini.');
    }

    public function simulatePayment(Request $request, Rental $rental): RedirectResponse
    {
        abort_unless($rental->user_id === $request->user()->id, 403);
        abort_unless($rental->transaction->status === PaymentStatus::Pending, 422);

        $rental->transaction->update([
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Pembayaran berhasil disimulasikan! Menunggu admin melakukan serah terima unit.');
    }

    public function cancelBooking(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        abort_unless($booking->status === BookingStatus::Pending, 422);

        $booking->update(['status' => BookingStatus::Cancelled]);

        return back()->with('success', "Booking #{$booking->booking_code} berhasil dibatalkan.");
    }

    public function rentals(Request $request): View
    {
        $rentals = $request->user()
            ->rentals()
            ->with(['unit', 'transaction', 'deliveries', 'extensions'])
            ->latest()
            ->get();

        $warnings = $rentals->mapWithKeys(fn ($r) => [$r->id => RentalWarning::details($r->due_date)]);

        return view('portal.rentals', compact('rentals', 'warnings'));
    }

    public function extension(Request $request, Rental $rental): RedirectResponse
    {
        abort_unless($rental->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'requested_due_date' => ['required', 'date', 'after:'.$rental->due_date->toDateString()],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $additionalDays = (int) $rental->due_date->diffInDays($data['requested_due_date']);
        $totalEstimatedDays = $rental->duration_days + $additionalDays;

        if ($totalEstimatedDays > 5) {
            // Can still submit request for admin approval
            $note = ' (Melebihi durasi standar 5 hari, butuh persetujuan khusus admin)';
        } else {
            $note = '';
        }

        RentalExtension::create([
            'rental_id' => $rental->id,
            'requested_due_date' => $data['requested_due_date'],
            'additional_days' => $additionalDays,
            'additional_cost' => $rental->unit->daily_price * $additionalDays,
            'reason' => ($data['reason'] ?? 'Perpanjangan masa rental').$note,
            'status' => ExtensionStatus::Pending,
        ]);

        return back()->with('success', "Pengajuan perpanjangan sewa +{$additionalDays} hari telah dikirim ke admin.");
    }

    public function delivery(Request $request, Rental $rental): RedirectResponse
    {
        abort_unless($rental->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'type' => ['required', 'in:delivery_out,delivery_return'],
            'method' => ['required', 'in:pickup,delivery'],
            'address' => ['required_if:method,delivery', 'nullable', 'string', 'max:500'],
            'contact_number' => ['required_if:method,delivery', 'nullable', 'string', 'max:30'],
        ]);

        $fee = $data['method'] === 'delivery' ? 15000 : 0;

        $delivery = Delivery::updateOrCreate(
            ['rental_id' => $rental->id, 'type' => $data['type']],
            [
                'method' => $data['method'],
                'address' => $data['address'] ?? null,
                'contact_number' => $data['contact_number'] ?? null,
                'delivery_fee' => $fee,
                'status' => $data['method'] === 'delivery' ? DeliveryStatus::Waiting : DeliveryStatus::ReadyForPickup,
                'scheduled_at' => now(),
            ]
        );

        // Jika ini adalah pengiriman kembali (return pickup), perbarui total tagihan
        if ($data['type'] === 'delivery_return' && $rental->transaction) {
            app(RecalculateTransaction::class)->handle($rental);
        }

        $message = $data['method'] === 'delivery'
            ? 'Permintaan penjemputan kurir berhasil disimpan. Ongkir Rp15.000 akan dibayarkan saat kurir datang.'
            : 'Metode pengembalian (antar sendiri) berhasil disimpan.';

        return back()->with('success', $message);
    }

    public function history(Request $request): View
    {
        $query = $request->user()
            ->rentals()
            ->where('status', RentalStatus::Returned)
            ->with(['unit', 'transaction', 'fines', 'deliveries']);

        $days = (int) (clone $query)->sum('duration_days');
        $rank = BaksoRank::fromDays($days);
        $rentals = $query->latest()->paginate(10);

        return view('portal.history', compact('rentals', 'rank', 'days'));
    }

    public function profile(Request $request): View
    {
        $user = $request->user()->load('profile');
        $totalDays = (int) $user->rentals()->where('status', RentalStatus::Returned)->sum('duration_days');
        $rank = BaksoRank::fromDays($totalDays);

        return view('portal.profile', compact('user', 'rank', 'totalDays'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
        ]);

        $request->user()->update(['name' => $data['name']]);
        $request->user()->profile()->updateOrCreate([], collect($data)->except('name')->all());

        return back()->with('success', 'Informasi profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar_base64' => ['required', 'string'],
        ]);

        $base64Image = $request->input('avatar_base64');

        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            $type = strtolower($type[1]);

            if (! in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                return back()->withErrors(['avatar_base64' => 'Format gambar tidak valid.']);
            }

            $base64Image = str_replace(' ', '+', $base64Image);
            $imageFile = base64_decode($base64Image);

            if ($imageFile === false) {
                return back()->withErrors(['avatar_base64' => 'Gagal membaca gambar.']);
            }

            $fileName = 'avatars/'.$request->user()->id.'_'.time().'.'.$type;
            Storage::disk('public')->put($fileName, $imageFile);

            if ($request->user()->avatar && Storage::disk('public')->exists($request->user()->avatar)) {
                Storage::disk('public')->delete($request->user()->avatar);
            }

            $request->user()->update(['avatar' => $fileName]);

            return back()->with('success', 'Foto profil berhasil diperbarui via kamera!');
        }

        return back()->withErrors(['avatar_base64' => 'Format gambar Base64 tidak valid.']);
    }
}
