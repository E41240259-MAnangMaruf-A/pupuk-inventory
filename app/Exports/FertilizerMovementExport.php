<?php
// app/Exports/FertilizerMovementExport.php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FertilizerMovementExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return DB::table('fertilizer_stock_histories as fsh')
            ->leftJoin('fertilizer_types as ft', 'fsh.fertilizer_type_id', '=', 'ft.id')
            ->leftJoin('users as u', 'fsh.user_id', '=', 'u.id')
            ->whereBetween('fsh.created_at', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'])
            ->select(
                'fsh.created_at',
                'ft.fertilizer_name',
                'fsh.type',
                'fsh.stock_change',
                'fsh.current_stock',
                'fsh.final_stock',
                'u.name as user_name',
                'fsh.note'
            )
            ->orderBy('fsh.created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Jenis Pupuk',
            'Tipe',
            'Perubahan (kg)',
            'Stok Awal (kg)',
            'Stok Akhir (kg)',
            'Petugas',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        static $number = 0;
        $number++;

        return [
            $number,
            \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i'),
            $row->fertilizer_name ?? '-',
            $row->type == 'in' ? 'Masuk' : 'Keluar',
            $row->stock_change,
            $row->current_stock,
            $row->final_stock,
            $row->user_name ?? 'System',
            $row->note ?? '-'
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
        return 'Pergerakan Pupuk';
    }
}