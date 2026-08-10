<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Transaksi & Riwayat Sewa — Bakso Console</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            background: #fff;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
            color: #555;
        }
        .meta-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; font-size: 11px; }
        .totals-row td {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding: 0 40px;
        }
        .sig-box {
            text-align: center;
            width: 200px;
        }
        .sig-space {
            height: 60px;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="no-print" style="margin-bottom: 15px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #ea580c; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
            <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="header">
        <h1><i class="fa-solid fa-bowl-food"></i> Bakso Console Management System</h1>
        <p><b>Cetak Riwayat Rental &middot; Laporan Transaksi & Riwayat Sewa</b></p>
        <p>Gupron in da House &middot; BNSP Console Rental Certification &middot; Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <div class="meta-summary">
        <div>Total Transaksi: <b>{{ $rentals->count() }} Data</b></div>
        <div>Total Pendapatan Lunas: <b>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</b></div>
        <div>Total Denda Masuk: <b>Rp{{ number_format($totalFines, 0, ',', '.') }}</b></div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Kode Sewa</th>
                <th>No. Invoice</th>
                <th>Nama Anggota</th>
                <th>Unit Konsol</th>
                <th>Tgl Sewa</th>
                <th>Tgl Kembali</th>
                <th class="text-center">Durasi</th>
                <th class="text-right">Sewa</th>
                <th class="text-right">Denda</th>
                <th class="text-right">Ongkir</th>
                <th class="text-right">Total</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rentals as $index => $r)
                @php
                    $fines = $r->fines->sum('amount');
                    $delivery = $r->deliveries->sum('delivery_fee');
                    $total = $r->transaction?->total_amount ?? ($r->subtotal + $fines + $delivery);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-mono">{{ $r->rental_code }}</td>
                    <td class="font-mono">{{ $r->transaction?->invoice_number ?? '-' }}</td>
                    <td>{{ $r->user->name }}</td>
                    <td>{{ $r->unit->name }} ({{ $r->unit->code }})</td>
                    <td>{{ $r->start_date->format('d/m/Y') }}</td>
                    <td>{{ $r->returned_at ? \Carbon\Carbon::parse($r->returned_at)->format('d/m/Y') : $r->due_date->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $r->duration_days }} Hari</td>
                    <td class="text-right">Rp{{ number_format($r->subtotal) }}</td>
                    <td class="text-right">{{ $fines > 0 ? 'Rp' . number_format($fines) : '-' }}</td>
                    <td class="text-right">{{ $delivery > 0 ? 'Rp' . number_format($delivery) : '-' }}</td>
                    <td class="text-right font-bold">Rp{{ number_format($total) }}</td>
                    <td class="text-center">{{ strtoupper($r->transaction?->status->value ?? $r->status->value) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center" style="padding: 20px;">Tidak ada riwayat transaksi.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="totals-row">
                <td colspan="8" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-right">Rp{{ number_format($rentals->sum('subtotal')) }}</td>
                <td class="text-right">Rp{{ number_format($totalFines) }}</td>
                <td class="text-right">Rp{{ number_format($rentals->flatMap->deliveries->sum('delivery_fee')) }}</td>
                <td class="text-right">Rp{{ number_format($totalRevenue) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div class="sig-box">
            <div>Mengetahui,</div>
            <div><b>Supervisor Operasional</b></div>
            <div class="sig-space"></div>
            <div>( _________________________ )</div>
        </div>
        <div class="sig-box">
            <div>Jakarta, {{ date('d F Y') }}</div>
            <div><b>Admin Bakso Console</b></div>
            <div class="sig-space"></div>
            <div>( <b>{{ auth()->user()->name ?? 'Administrator' }}</b> )</div>
        </div>
    </div>
</body>
</html>
