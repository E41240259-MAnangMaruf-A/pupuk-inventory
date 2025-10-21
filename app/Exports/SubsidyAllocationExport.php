<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SubsidyAllocationExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        return DB::table('subsidy_allocations as sa')
            ->leftJoin('farmers as f', 'sa.farmer_id', '=', 'f.id')
            ->leftJoin('fertilizer_types as ft', 'sa.fertilizer_type_id', '=', 'ft.id')
            ->select(
                'f.nik',
                'f.farmer_name',
                'ft.fertilizer_name',
                'sa.maximum_quota',
                'sa.used_quota',
                'sa.remaining_quota',
                'ft.subsidized_price'
            )
            ->orderBy('sa.created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIK',
            'Nama Petani',
            'Jenis Pupuk',
            'Kuota Awal (kg)',
            'Terpakai (kg)',
            'Sisa (kg)',
            'Persentase (%)',
            'Nilai Subsidi (Rp)'
        ];
    }

    public function map($row): array
    {
        static $number = 0;
        $number++;

        $percentage = $row->maximum_quota > 0 ? ($row->used_quota / $row->maximum_quota) * 100 : 0;
        $subsidyValue = $row->used_quota * ($row->subsidized_price ?? 0);

        return [
            $number,
            $row->nik ?? '-',
            $row->farmer_name ?? '-',
            $row->fertilizer_name ?? '-',
            $row->maximum_quota,
            $row->used_quota,
            $row->remaining_quota,
            number_format($percentage, 2),
            $subsidyValue
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
        return 'Alokasi Subsidi';
    }
}