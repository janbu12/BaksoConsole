<?php

namespace App\Http\Controllers\Admin;

use App\Application\Rentals\ProcessReturn;
use App\Application\Rentals\StartRental;
use App\Application\Transactions\RecalculateTransaction;
use App\Enums\BookingStatus;
use App\Enums\DeliveryMethod;
use App\Enums\DeliveryStatus;
use App\Enums\ExtensionStatus;
use App\Enums\FineType;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\UnitStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Delivery;
use App\Models\Fine;
use App\Models\Rental;
use App\Models\RentalExtension;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use App\Queries\AdminDashboardQuery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OperationsController extends Controller
{
    public function dashboard(AdminDashboardQuery $query): View
    {
        $data = $query->get();

        return view('admin.dashboard', array_merge($data, [
            'recentRentals' => Rental::with(['user', 'unit', 'transaction'])->latest()->take(5)->get(),
        ]));
    }

    public function units(): View
    {
        return view('admin.units', [
            'units' => Unit::with('categories')->orderBy('code')->paginate(15),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function categories(): View
    {
        return view('admin.categories', [
            'categories' => Category::withCount('units')->orderBy('name')->get(),
            'combos' => Combo::all(),
        ]);
    }

    public function members(): View
    {
        return view('admin.members', [
            'members' => User::where('role', 'user')->with(['profile', 'rentals.unit'])->latest()->get(),
        ]);
    }

    public function bookings(): View
    {
        return view('admin.bookings', [
            'bookings' => Booking::with(['user.profile', 'unit'])->latest()->paginate(10),
        ]);
    }

    public function returns(): View
    {
        return view('admin.returns', [
            'activeRentals' => Rental::whereIn('status', [RentalStatus::Active, RentalStatus::Overdue])
                ->with(['user.profile', 'unit', 'transaction', 'deliveries'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function deliveries(): View
    {
        return view('admin.deliveries', [
            'deliveries' => Delivery::with(['rental.user', 'rental.unit'])->latest()->paginate(10),
        ]);
    }

    public function history(): View
    {
        return view('admin.history', [
            'rentals' => Rental::with(['user.profile', 'unit', 'transaction', 'deliveries'])->latest()->get(),
        ]);
    }

    public function printHistory(): View
    {
        $rentals = Rental::with(['user', 'unit', 'transaction', 'fines', 'deliveries'])->latest()->get();
        $totalRevenue = (float) Transaction::where('status', PaymentStatus::Paid)->sum('total_amount');
        $totalFines = (float) Fine::sum('amount');

        return view('admin.history-print', compact('rentals', 'totalRevenue', 'totalFines'));
    }

    public function storeMember(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $member = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        $member->profile()->create(collect($data)->only(['phone', 'address', 'date_of_birth'])->all());

        return back()->with('success', "Anggota {$member->name} berhasil didaftarkan.");
    }

    public function updateMember(Request $request, User $member): RedirectResponse
    {
        abort_unless($member->role->value === 'user', 404);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($member)],
            'phone' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $member->update(collect($data)->only(['name', 'email'])->all());
        $member->profile()->updateOrCreate([], collect($data)->only(['phone', 'address', 'date_of_birth'])->all());

        return back()->with('success', "Data anggota {$member->name} berhasil diperbarui.");
    }

    public function destroyMember(User $member): RedirectResponse
    {
        abort_unless($member->role->value === 'user', 404);
        if ($member->rentals()->whereIn('status', ['active', 'overdue'])->exists()) {
            return back()->withErrors(['member' => 'Anggota masih memiliki rental aktif dan tidak dapat dihapus.']);
        }
        $member->delete();

        return back()->with('success', 'Anggota berhasil dihapus.');
    }

    public function storeUnit(Request $r): RedirectResponse
    {
        $data = $r->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:units',
            'daily_price' => 'required|numeric|min:0',
            'max_players' => 'required|integer|min:1|max:4',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $unit = Unit::create([
            'name' => $data['name'],
            'code' => strtoupper($data['code']),
            'daily_price' => $data['daily_price'],
            'max_players' => $data['max_players'],
            'description' => $data['description'] ?? null,
            'status' => UnitStatus::Available,
        ]);

        if (!empty($data['category_id'])) {
            $unit->categories()->sync([$data['category_id']]);
        }

        return back()->with('success', "Unit konsol {$unit->name} ({$unit->code}) berhasil ditambahkan!");
    }

    public function updateUnit(Request $r, Unit $unit): RedirectResponse
    {
        $data = $r->validate([
            'name' => 'required|string',
            'daily_price' => 'required|numeric',
            'max_players' => 'required|integer',
            'status' => 'required|string',
        ]);

        $unit->update($data);

        return back()->with('success', "Data unit {$unit->code} diperbarui.");
    }

    public function destroyUnit(Unit $unit): RedirectResponse
    {
        if ($unit->status !== UnitStatus::Available) {
            return back()->withErrors(['unit' => 'Unit yang sedang disewa atau dibooking tidak bisa dihapus.']);
        }

        $unit->delete();

        return back()->with('success', "Unit {$unit->code} berhasil dihapus.");
    }

    public function storeCategory(Request $r): RedirectResponse
    {
        $data = $r->validate([
            'name' => 'required|string|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'description' => $data['description'] ?? null,
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function storeCombo(Request $r): RedirectResponse
    {
        $data = $r->validate([
            'name' => 'required|string',
            'duration_days' => 'required|integer|min:1|max:5',
            'controller_count' => 'required|integer|min:1|max:4',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Combo::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'duration_days' => $data['duration_days'],
            'controller_count' => $data['controller_count'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', "Paket {$data['name']} berhasil dibuat!");
    }

    public function startRental(Request $r, StartRental $action): RedirectResponse
    {
        $data = $r->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'user_id' => 'required|exists:users,id',
            'unit_id' => 'required|exists:units,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'combo_id' => 'nullable|exists:combos,id',
            'delivery_method' => 'nullable|string',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric|min:0',
        ]);

        $user = User::findOrFail($data['user_id']);
        $unit = Unit::findOrFail($data['unit_id']);
        $booking = !empty($data['booking_id']) ? Booking::find($data['booking_id']) : null;
        $combo = !empty($data['combo_id']) ? Combo::find($data['combo_id']) : null;

        try {
            $rental = $action->handle(
                $user,
                $unit,
                $data['start_date'],
                $data['end_date'],
                $booking,
                $combo,
                $data['delivery_method'] ?? 'pickup',
                $data['address'] ?? null,
                $data['contact_number'] ?? null,
                (float) ($data['delivery_fee'] ?? 0)
            );
        } catch (\DomainException $e) {
            return back()->withErrors(['rental' => $e->getMessage()]);
        }

        return back()->with('success', "Rental #{$rental->rental_code} berhasil diaktifkan!");
    }

    public function reviewExtension(Request $r, RentalExtension $extension, RecalculateTransaction $recalc): RedirectResponse
    {
        $data = $r->validate([
            'status' => 'required|in:approved,rejected',
            'review_notes' => 'nullable|string',
        ]);

        $status = ExtensionStatus::from($data['status']);
        $extension->update([
            'status' => $status,
            'reviewed_by' => $r->user()->id,
            'reviewed_at' => now(),
            'review_notes' => $data['review_notes'] ?? null,
        ]);

        if ($status === ExtensionStatus::Approved) {
            $rental = $extension->rental;
            $rental->update([
                'due_date' => $extension->requested_due_date,
                'duration_days' => $rental->duration_days + $extension->additional_days,
                'subtotal' => $rental->subtotal + $extension->additional_cost,
            ]);
            $recalc->handle($rental);
        }

        return back()->with('success', "Pengajuan perpanjangan telah di-{$data['status']}.");
    }

    public function processReturn(Request $r, Rental $rental, ProcessReturn $action): RedirectResponse
    {
        $data = $r->validate([
            'returned_at' => 'required|date',
            'daily_fine' => 'nullable|numeric|min:0',
            'return_notes' => 'nullable|string',
        ]);

        try {
            $action->handle(
                $rental,
                $data['returned_at'],
                (float) ($data['daily_fine'] ?? 10000),
                $data['return_notes'] ?? null
            );
        } catch (\DomainException $e) {
            return back()->withErrors(['return' => $e->getMessage()]);
        }

        return back()->with('success', "Pengembalian rental #{$rental->rental_code} selesai diproses.");
    }

    public function addFine(Request $r, Rental $rental, RecalculateTransaction $recalc): RedirectResponse
    {
        $data = $r->validate([
            'amount' => 'required|numeric|min:1',
            'reason' => 'required|string|max:255',
        ]);

        Fine::create([
            'rental_id' => $rental->id,
            'type' => FineType::Damage,
            'amount' => $data['amount'],
            'reason' => $data['reason'],
            'status' => PaymentStatus::Pending,
        ]);

        $recalc->handle($rental);

        return back()->with('success', "Denda kerusakan sebesar Rp " . number_format($data['amount'], 0, ',', '.') . " berhasil ditambahkan.");
    }

    public function updateDelivery(Request $r, Delivery $delivery): RedirectResponse
    {
        $data = $r->validate([
            'status' => 'required|string',
            'courier_name' => 'nullable|string',
            'delivery_fee' => 'nullable|numeric|min:0',
        ]);

        $status = DeliveryStatus::from($data['status']);
        $updates = [
            'status' => $status,
            'courier_name' => $data['courier_name'] ?? $delivery->courier_name,
        ];

        if (isset($data['delivery_fee'])) {
            $updates['delivery_fee'] = $data['delivery_fee'];
            if ($delivery->rental && $delivery->rental->transaction) {
                $delivery->rental->transaction->update(['delivery_fee' => $data['delivery_fee']]);
                app(RecalculateTransaction::class)->handle($delivery->rental);
            }
        }

        if ($status === DeliveryStatus::Received || $status === DeliveryStatus::ReturnedToOutlet || $status === DeliveryStatus::PickedUp) {
            $updates['completed_at'] = now();
        }

        $delivery->update($updates);

        return back()->with('success', "Status pengantaran kurir diperbarui.");
    }

    public function markPaymentPaid(Transaction $transaction): RedirectResponse
    {
        $transaction->update([
            'status' => PaymentStatus::Paid,
            'paid_at' => now(),
        ]);

        return back()->with('success', "Pembayaran invoice #{$transaction->invoice_number} telah lunas.");
    }
}
