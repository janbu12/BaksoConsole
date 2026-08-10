<?php

namespace App\Http\Controllers\Admin;

use App\Application\Rentals\ProcessReturn;
use App\Application\Rentals\StartRental;
use App\Application\Transactions\RecalculateTransaction;
use App\Enums\BookingStatus;
use App\Enums\DeliveryStatus;
use App\Enums\ExtensionStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Combo;
use App\Models\Delivery;
use App\Models\Rental;
use App\Models\RentalExtension;
use App\Models\Transaction;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OperationsController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.operations', ['stats' => ['Anggota' => User::where('role', 'user')->count(), 'Unit' => Unit::count(), 'Rental' => Rental::count(), 'Pendapatan' => Transaction::where('status', PaymentStatus::Paid)->sum('total_amount')], 'bookings' => Booking::with(['user', 'unit'])->latest()->limit(10)->get(), 'rentals' => Rental::with(['user', 'unit', 'transaction'])->latest()->limit(10)->get(), 'units' => Unit::with('categories')->get(), 'categories' => Category::all(), 'combos' => Combo::all(), 'deliveries' => Delivery::with('rental.user')->latest()->get()]);
    }
    public function storeUnit(Request $r): RedirectResponse { $d=$r->validate(['name'=>'required','code'=>'required|unique:units','daily_price'=>'required|numeric|min:0','max_players'=>'required|integer|min:1','category_ids'=>'array']); $u=Unit::create($d+['status'=>'available']); $u->categories()->sync($d['category_ids']??[]); return back()->with('success','Unit ditambahkan.'); }
    public function storeCategory(Request $r): RedirectResponse { $d=$r->validate(['name'=>'required|unique:categories','description'=>'nullable']); Category::create($d+['slug'=>Str::slug($d['name'])]); return back()->with('success','Kategori ditambahkan.'); }
    public function storeCombo(Request $r): RedirectResponse { $d=$r->validate(['name'=>'required','duration_days'=>'required|integer|min:1','controller_count'=>'required|integer|min:1','price'=>'required|numeric|min:0']); Combo::create($d+['slug'=>Str::slug($d['name']).'-'.Str::lower(Str::random(4)),'is_active'=>true]); return back()->with('success','Combo ditambahkan.'); }
    public function confirm(Booking $booking): RedirectResponse { $booking->update(['status'=>BookingStatus::Confirmed]); return back()->with('success','Booking dikonfirmasi.'); }
    public function start(Request $r, Booking $booking, StartRental $action): RedirectResponse { try{$action->handle($booking->user,$booking->unit,$booking->start_date->toDateString(),$booking->end_date->toDateString(),$booking);}catch(\DomainException $e){return back()->withErrors(['rental'=>$e->getMessage()]);} return back()->with('success','Rental dimulai.'); }
    public function reviewExtension(Request $r, RentalExtension $extension, RecalculateTransaction $recalc): RedirectResponse { $d=$r->validate(['status'=>'required|in:approved,rejected']); $extension->update(['status'=>ExtensionStatus::from($d['status']),'reviewed_by'=>$r->user()->id,'reviewed_at'=>now()]); if($d['status']==='approved'){$extension->rental->update(['due_date'=>$extension->requested_due_date,'duration_days'=>$extension->rental->start_date->diffInDays($extension->requested_due_date)+1]);$recalc->handle($extension->rental);} return back()->with('success','Perpanjangan ditinjau.'); }
    public function returnRental(Request $r, Rental $rental, ProcessReturn $action): RedirectResponse { $d=$r->validate(['returned_at'=>'required|date','daily_fine'=>'nullable|numeric|min:0','notes'=>'nullable']); $action->handle($rental,$d['returned_at'],$d['daily_fine']??0,$d['notes']??null); return back()->with('success','Pengembalian selesai.'); }
    public function delivery(Request $r, Delivery $delivery, RecalculateTransaction $recalc): RedirectResponse { $d=$r->validate(['status'=>'required','delivery_fee'=>'required|numeric|min:0','courier_name'=>'nullable']); $delivery->update(['status'=>DeliveryStatus::from($d['status']),'delivery_fee'=>$d['delivery_fee'],'courier_name'=>$d['courier_name']??null,'completed_at'=>in_array($d['status'],['received','returned_to_outlet'])?now():null]); $recalc->handle($delivery->rental); return back()->with('success','Delivery diperbarui.'); }
    public function pay(Transaction $transaction): RedirectResponse { $transaction->update(['status'=>PaymentStatus::Paid,'paid_at'=>now()]); return back()->with('success','Pembayaran dikonfirmasi.'); }
}
