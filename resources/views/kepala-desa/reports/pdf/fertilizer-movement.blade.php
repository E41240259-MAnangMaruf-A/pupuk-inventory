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
        .info-section {
            margin: 15px 0;
        }
        .info-row {
            margin: 5px 0;
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
        .badge-in {
            background-color: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }
        .badge-out {
            background-color: #dc3545;
            color: white;
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
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
        }
        .signature-section {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PERGERAKAN PUPUK</h2>
        <h2>KOPERASI TANI</h2>
        <p>Periode: {{ $periodStart }} s/d {{ $periodEnd }}</p>
        <p>Dicetak pada: {{ $exportDate }}</p>
    </div>

    <div class="summary-box">
        <h3 style="margin-top: 0;">Ringkasan</h3>
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 50%;">Total Stok Masuk: <strong>{{ number_format($summary['total_stock_in']) }} kg</strong></td>
                <td style="border: none; width: 50%;">Total Stok Keluar: <strong>{{ number_format($summary['total_stock_out']) }} kg</strong></td>
            </tr>
            <tr>
                <td style="border: none;">Stok Saat Ini: <strong>{{ number_format($summary['current_stock']) }} kg</strong></td>
                <td style="border: none;">Nilai Stok: <strong>Rp {{ number_format($summary['total_value'], 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="12%">Tanggal</th>
                <th width="15%">Jenis Pupuk</th>
                <th width="8%">Tipe</th>
                <th width="10%">Perubahan</th>
                <th width="10%">Stok Awal</th>
                <th width="10%">Stok Akhir</th>
                <th width="12%">Petugas</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($movements as $index => $movement)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($movement->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $movement->fertilizer_name ?? 'N/A' }}</td>
                <td class="text-center">
                    @if($movement->type == 'in')
                        <span class="badge-in">Masuk</span>
                    @else
                        <span class="badge-out">Keluar</span>
                    @endif
                </td>
                <td class="text-right">
                    {{ $movement->type == 'in' ? '+' : '-' }}{{ number_format(abs($movement->stock_change)) }} kg
                </td>
                <td class="text-right">{{ number_format($movement->current_stock) }} kg</td>
                <td class="text-right">{{ number_format($movement->final_stock) }} kg</td>
                <td>{{ $movement->user_name ?? 'System' }}</td>
                <td>{{ $movement->note ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data pergerakan pupuk</td>
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