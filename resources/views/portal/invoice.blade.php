<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $rental->transaction->invoice_number }}</title>
    <style>
        @page { margin: 34px 42px; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #1e293b; font-family: "DejaVu Sans", sans-serif; font-size: 11px; line-height: 1.45; }
        .header { border-bottom: 3px solid #f97316; padding-bottom: 18px; margin-bottom: 22px; }
        .brand { color: #0f172a; font-size: 25px; font-weight: bold; }
        .brand span { color: #f97316; }
        .tagline { color: #64748b; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; }
        .invoice-title { color: #f97316; font-size: 22px; font-weight: bold; text-align: right; }
        .invoice-number { color: #475569; text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        .two-column td { width: 50%; vertical-align: top; }
        .two-column td:last-child { text-align: right; }
        .label { color: #64748b; font-size: 9px; font-weight: bold; letter-spacing: .6px; text-transform: uppercase; }
        .value { color: #0f172a; font-size: 12px; font-weight: bold; margin-top: 3px; }
        .box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 18px; }
        .details { margin-top: 18px; }
        .details th { background: #0f172a; color: #fff; padding: 10px 8px; font-size: 9px; text-align: left; text-transform: uppercase; }
        .details td { border-bottom: 1px solid #e2e8f0; padding: 11px 8px; vertical-align: top; }
        .details th.right, .details td.right { text-align: right; }
        .summary { width: 46%; margin: 18px 0 0 auto; }
        .summary td { padding: 5px 8px; }
        .summary td:last-child { text-align: right; font-weight: bold; }
        .summary .total td { border-top: 2px solid #f97316; color: #f97316; font-size: 14px; padding-top: 9px; }
        .status { display: inline-block; border-radius: 12px; padding: 4px 10px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .paid { background: #dcfce7; color: #15803d; }
        .pending { background: #fef3c7; color: #b45309; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; border-top: 1px solid #e2e8f0; padding-top: 10px; color: #64748b; font-size: 9px; }
        .muted { color: #64748b; }
    </style>
</head>
<body>
    @php
        $transaction = $rental->transaction;
        $deliveryOut = $rental->deliveries->firstWhere('type.value', 'delivery_out');
    @endphp

    <div class="header">
        <table class="two-column">
            <tr>
                <td>
                    <div class="brand">BAKSO <span>CONSOLE</span></div>
                    <div class="tagline">Rent Smarter. Play Better.</div>
                </td>
                <td>
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">{{ $transaction->invoice_number }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="two-column box">
        <tr>
            <td>
                <div class="label">Ditagihkan kepada</div>
                <div class="value">{{ $rental->user->name }}</div>
                <div>{{ $rental->user->email }}</div>
                @if($rental->user->profile?->phone)<div>{{ $rental->user->profile->phone }}</div>@endif
                @if($rental->user->profile?->address)<div class="muted">{{ $rental->user->profile->address }}</div>@endif
            </td>
            <td>
                <div class="label">Tanggal invoice</div>
                <div class="value">{{ $transaction->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }} WIB</div>
                <div style="margin-top: 9px" class="label">Status pembayaran</div>
                <div style="margin-top: 4px">
                    <span class="status {{ $transaction->status->value === 'paid' ? 'paid' : 'pending' }}">{{ $transaction->status->value === 'paid' ? 'Lunas' : 'Menunggu Pembayaran' }}</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="details">
        <thead>
            <tr>
                <th>Unit dan layanan</th>
                <th>Periode sewa</th>
                <th class="right">Durasi</th>
                <th class="right">Tarif/hari</th>
                <th class="right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>{{ $rental->unit->name }}</strong><br>
                    <span class="muted">Kode: {{ $rental->unit->code }}</span>
                </td>
                <td>{{ $rental->start_date->format('d/m/Y') }} - {{ $rental->due_date->format('d/m/Y') }}</td>
                <td class="right">{{ $rental->duration_days }} hari</td>
                <td class="right">Rp{{ number_format($rental->daily_price, 0, ',', '.') }}</td>
                <td class="right">Rp{{ number_format($transaction->rental_amount, 0, ',', '.') }}</td>
            </tr>
            @if((float) $transaction->delivery_fee > 0)
                <tr>
                    <td><strong>Pickup & Delivery</strong><br><span class="muted">{{ $deliveryOut?->method->value === 'delivery' ? 'Diantar ke alamat pelanggan' : 'Ambil di outlet' }}</span></td>
                    <td>-</td><td class="right">-</td><td class="right">-</td>
                    <td class="right">Rp{{ number_format($transaction->delivery_fee, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if((float) $transaction->fine_amount > 0)
                <tr>
                    <td><strong>Denda</strong><br><span class="muted">Keterlambatan atau kerusakan unit</span></td>
                    <td>-</td><td class="right">-</td><td class="right">-</td>
                    <td class="right">Rp{{ number_format($transaction->fine_amount, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="summary">
        <tr><td>Subtotal sewa</td><td>Rp{{ number_format($transaction->rental_amount, 0, ',', '.') }}</td></tr>
        <tr><td>Biaya delivery</td><td>Rp{{ number_format($transaction->delivery_fee, 0, ',', '.') }}</td></tr>
        <tr><td>Denda</td><td>Rp{{ number_format($transaction->fine_amount, 0, ',', '.') }}</td></tr>
        @if((float) $transaction->discount_amount > 0)
            <tr><td>Diskon</td><td>- Rp{{ number_format($transaction->discount_amount, 0, ',', '.') }}</td></tr>
        @endif
        <tr class="total"><td>Total</td><td>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</td></tr>
    </table>

    <div class="footer">
        Invoice ini diterbitkan otomatis oleh Bakso Console pada {{ now()->format('d/m/Y H:i') }} WIB.
        Simpan dokumen ini sebagai bukti transaksi Anda.
    </div>
</body>
</html>
