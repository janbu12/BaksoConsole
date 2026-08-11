<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InvoiceController extends Controller
{
    public function download(Request $request, Rental $rental): Response
    {
        abort_unless($rental->user_id === $request->user()->id, 403);

        $rental->loadMissing([
            'user.profile',
            'unit.categories',
            'booking',
            'transaction',
            'fines',
            'deliveries',
        ]);

        abort_unless($rental->transaction !== null, 404, 'Invoice tidak ditemukan.');

        $options = new Options;
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $pdf = new Dompdf($options);
        $pdf->loadHtml(view('portal.invoice', compact('rental'))->render(), 'UTF-8');
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $output = $pdf->output();

        $invoiceNumber = preg_replace('/[^A-Za-z0-9_-]/', '-', $rental->transaction->invoice_number);

        return response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Invoice-'.$invoiceNumber.'.pdf"',
            'Content-Length' => (string) strlen($output),
        ]);
    }
}
