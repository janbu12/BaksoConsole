<?php

namespace App\Http\Controllers;

use App\Application\Bookings\CreateBooking;
use App\Domain\Loyalty\BaksoRank;
use App\Domain\Rentals\RentalWarning;
use App\Enums\BookingStatus;
use App\Enums\DeliveryStatus;
use App\Enums\ExtensionStatus;
use App\Enums\RentalStatus;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Delivery;
use App\Models\Rental;
use App\Models\RentalExtension;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function catalogue(Request $request): View
    {
        $units = Unit::query()->with('categories')->when($request->q, fn ($q, $v) => $q->where('name', 'like', "%$v%"))->when($request->players, fn ($q, $v) => $q->where('max_players', '>=', $v))->when($request->budget, fn ($q, $v) => $q->where('daily_price', '<=', $v))->when($request->category, fn ($q, $v) => $q->whereHas('categories', fn ($c) => $c->where('categories.id', $v)))->orderBy('daily_price')->paginate(12)->withQueryString();

        return view('portal.catalogue', ['units' => $units, 'categories' => Category::orderBy('name')->get(), 'combos' => Combo::where('is_active', true)->get()]);
    }

    public function bookings(Request $request): View
    {
        return view('portal.bookings', ['bookings' => $request->user()->bookings()->with('unit')->latest()->paginate()]);
    }

    public function storeBooking(Request $request, CreateBooking $action): RedirectResponse
    {
        $data = $request->validate(['unit_id' => ['required', 'exists:units,id'], 'start_date' => ['required', 'date', 'after_or_equal:today'], 'end_date' => ['required', 'date', 'after_or_equal:start_date'], 'notes' => ['nullable', 'string', 'max:1000']]);
        try {
            $action->handle($request->user(), Unit::findOrFail($data['unit_id']), $data['start_date'], $data['end_date'], $data['notes'] ?? null);
        } catch (\DomainException $e) {
            return back()->withInput()->withErrors(['schedule' => $e->getMessage()]);
        }

        return redirect('/bookings')->with('success', 'Booking berhasil dibuat.');
    }

    public function rentals(Request $request): View
    {
        $rentals = $request->user()->rentals()->with(['unit', 'transaction', 'deliveries', 'extensions'])->latest()->get();
        $warnings = $rentals->mapWithKeys(fn ($r) => [$r->id => RentalWarning::forDueDate($r->due_date)]);

        return view('portal.rentals', compact('rentals', 'warnings'));
    }

    public function cancelBooking(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);
        abort_unless($booking->status === BookingStatus::Pending, 422);
        $booking->update(['status' => BookingStatus::Cancelled]);

        return back()->with('success', 'Booking dibatalkan.');
    }

    public function extension(Request $request, Rental $rental): RedirectResponse
    {
        abort_unless($rental->user_id === $request->user()->id, 403);
        $data = $request->validate(['requested_due_date' => ['required', 'date', 'after:'.$rental->due_date->toDateString()], 'reason' => ['nullable', 'string']]);
        RentalExtension::create(['rental_id' => $rental->id, 'requested_due_date' => $data['requested_due_date'], 'additional_days' => $rental->due_date->diffInDays($data['requested_due_date']), 'additional_cost' => 0, 'reason' => $data['reason'] ?? null, 'status' => ExtensionStatus::Pending]);

        return back()->with('success', 'Perpanjangan diajukan.');
    }

    public function delivery(Request $request, Rental $rental): RedirectResponse
    {
        abort_unless($rental->user_id === $request->user()->id, 403);
        $data = $request->validate(['type' => ['required', 'in:delivery_out,delivery_return'], 'method' => ['required', 'in:pickup,delivery'], 'address' => ['required_if:method,delivery', 'nullable', 'string'], 'contact_number' => ['required_if:method,delivery', 'nullable', 'string']]);
        Delivery::updateOrCreate(['rental_id' => $rental->id, 'type' => $data['type']], $data + ['delivery_fee' => 0, 'status' => DeliveryStatus::Waiting]);

        return back()->with('success', 'Metode layanan tersimpan.');
    }

    public function history(Request $request): View
    {
        $query = $request->user()->rentals()->where('status', RentalStatus::Returned)->with(['unit', 'transaction', 'fines', 'deliveries']);
        $days = (clone $query)->sum('duration_days');

        return view('portal.history', ['rentals' => $query->latest()->paginate(), 'rank' => BaksoRank::fromDays($days), 'days' => $days]);
    }

    public function profile(Request $request): View
    {
        return view('portal.profile', ['user' => $request->user()->load('profile')]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:255'], 'phone' => ['nullable', 'string', 'max:30'], 'address' => ['nullable', 'string'], 'date_of_birth' => ['nullable', 'date', 'before:today']]);
        $request->user()->update(['name' => $data['name']]);
        $request->user()->profile()->updateOrCreate([], collect($data)->except('name')->all());

        return back()->with('success', 'Profil diperbarui.');
    }
}
