<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $year;
    protected $month;
    protected $reportType;

    public function __construct($year, $month, $reportType)
    {
        $this->year = $year;
        $this->month = $month;
        $this->reportType = $reportType;
    }

    public function collection()
    {
        $query = DB::table('transactions as t')
            ->leftJoin('farmers as f', 't.farmer_id', '=', 'f.id')
            ->leftJoin('users as u', 't.user_id', '=', 'u.id')
            ->whereYear('t.transaction_date', $this->year);

        if ($this->reportType === 'monthly' && $this->month) {
            $query->whereMonth('t.transaction_date', date('m', strtotime($this->month)));
        }

        return $query->select(
            't.transaction_number',
            't.transaction_date',
            'f.farmer_name',
            't.total_amount',
            't.payment_status',
            'u.name as cashier_name'
        )->orderBy('t.transaction_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Transaksi',
            'Tanggal',
            'Nama Petani',
            'Total (Rp)',
            'Status Pembayaran',
            'Kasir'
        ];
    }

    public function map($row): array
    {
        static $number = 0;
        $number++;

        return [
            $number,
            $row->transaction_number,
            \Carbon\Carbon::parse($row->transaction_date)->format('d/m/Y'),
            $row->farmer_name ?? '-',
            $row->total_amount,
            $row->payment_status == 'paid' ? 'Lunas' : 'Belum Lunas',
            $row->cashier_name ?? 'System'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF9933']]],
        ];
    }

    public function title(): string
    {
        return 'Laporan Keuangan';
    }
}