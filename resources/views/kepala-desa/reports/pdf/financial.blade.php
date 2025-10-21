<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px 4px;
            font-size: 10px;
            font-weight: bold;
        }
        table td {
            border: 1px solid #000;
            padding: 5px 4px;
            font-size: 10px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge-paid {
            background-color: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .badge-unpaid {
            background-color: #ffc107;
            color: #000;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .summary-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            margin: 15px 0;
        }
        .signature-section {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN KOPERASI</h2>
        <h2>KOPERASI TANI</h2>
        <p>Periode: {{ $periodInfo }}</p>
        <p>Dicetak pada: {{ $exportDate }}</p>
    </div>

    <div class="summary-box">
        <h3 style="margin-top: 0;">Ringkasan Keuangan</h3>
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 50%;">Total Pemasukan: <strong>Rp {{ number_format($summary['total_income'], 0, ',', '.') }}</strong></td>
                <td style="border: none; width: 50%;">Total Pengeluaran: <strong>Rp {{ number_format($summary['total_expense'], 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td style="border: none;">Laba Bersih: <strong>Rp {{ number_format($summary['net_income'], 0, ',', '.') }}</strong></td>
                <td style="border: none;">Total Transaksi: <strong>{{ number_format($summary['total_transactions']) }}</strong></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Tanggal</th>
                <th width="14%">No. Transaksi</th>
                <th width="20%">Petani</th>
                <th width="13%">Total</th>
                <th width="13%">Pembayaran</th>
                <th width="10%">Status</th>
                <th width="16%">Kasir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    @if($transaction->transaction_date instanceof \Carbon\Carbon)
                        {{ $transaction->transaction_date->format('d/m/Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}
                    @endif
                </td>
                <td>{{ $transaction->transaction_number }}</td>
                <td>{{ $transaction->farmer_name ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($transaction->total_payment ?? 0, 0, ',', '.') }}</td>
                <td class="text-center">
                    @if($transaction->payment_status == 'paid')
                        <span class="badge-paid">Lunas</span>
                    @else
                        <span class="badge-unpaid">Belum Lunas</span>
                    @endif
                </td>
                <td>{{ $transaction->cashier_name ?? 'System' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <p>Mengetahui,</p>
        <p style="margin-top: 60px;">_____________________</p>
        <p><strong>Kepala Desa</strong></p>
    </div>
</body>
</html>