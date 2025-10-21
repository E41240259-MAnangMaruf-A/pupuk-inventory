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
        <h2>LAPORAN ALOKASI SUBSIDI PUPUK</h2>
        <h2>KOPERASI TANI</h2>
        <p>Periode: {{ $periodInfo }}</p>
        <p>Dicetak pada: {{ $exportDate }}</p>
    </div>

    <div class="summary-box">
        <h3 style="margin-top: 0;">Ringkasan</h3>
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 50%;">Total Dialokasi: <strong>{{ number_format($summary['total_allocated']) }} kg</strong></td>
                <td style="border: none; width: 50%;">Total Terpakai: <strong>{{ number_format($summary['total_used']) }} kg</strong></td>
            </tr>
            <tr>
                <td style="border: none;">Sisa Kuota: <strong>{{ number_format($summary['total_remaining']) }} kg</strong></td>
                <td style="border: none;">Nilai Subsidi: <strong>Rp {{ number_format($summary['total_subsidy_value'], 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="18%">Nama Petani</th>
                <th width="14%">NIK</th>
                <th width="16%">Jenis Pupuk</th>
                <th width="10%">Kuota</th>
                <th width="10%">Terpakai</th>
                <th width="10%">Sisa</th>
                <th width="10%">Persentase</th>
                <th width="12%">Nilai Subsidi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allocations as $index => $allocation)
            @php
                $percentage = $allocation->maximum_quota > 0 
                    ? round(($allocation->used_quota / $allocation->maximum_quota) * 100, 1)
                    : 0;
                $subsidyValue = $allocation->used_quota * ($allocation->subsidized_price ?? 0);
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $allocation->farmer_name ?? 'N/A' }}</td>
                <td>{{ $allocation->nik ?? 'N/A' }}</td>
                <td>{{ $allocation->fertilizer_name ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($allocation->maximum_quota) }} kg</td>
                <td class="text-right">{{ number_format($allocation->used_quota) }} kg</td>
                <td class="text-right">{{ number_format($allocation->remaining_quota) }} kg</td>
                <td class="text-center">{{ $percentage }}%</td>
                <td class="text-right">Rp {{ number_format($subsidyValue, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data alokasi subsidi</td>
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