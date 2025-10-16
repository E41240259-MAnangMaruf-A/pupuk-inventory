<?php $page = 'reports'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4 class="fw-bold">Laporan Alokasi Subsidi Pupuk</h4>
                <h6>Monitoring Penggunaan Subsidi oleh Petani</h6>
            </div>
        </div>

        <!-- Tabel dengan ID yang UNIK -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="subsidyAllocationTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Petani</th>
                                <th>Jenis Pupuk</th>
                                <th>Kuota Dialokasi</th>
                                <th>Terpakai</th>
                                <th>Sisa</th>
                                <th>Persentase</th>
                                <th>Nilai Subsidi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allocations as $index => $allocation)
                            @php
                                $percentage = $allocation->initial_quota > 0 ? 
                                    ($allocation->used_quota / $allocation->initial_quota) * 100 : 0;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $allocation->farmer->farmer_name ?? '-' }}</td>
                                <td>{{ $allocation->fertilizerType->fertilizer_name ?? '-' }}</td>
                                <td>{{ number_format($allocation->initial_quota) }} Kg</td>
                                <td>{{ number_format($allocation->used_quota) }} Kg</td>
                                <td>{{ number_format($allocation->remaining_quota) }} Kg</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 15px;">
                                            <div class="progress-bar {{ $percentage > 80 ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-success') }}" 
                                                 role="progressbar" style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                        <small class="ms-2">{{ number_format($percentage, 1) }}%</small>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($allocation->used_quota * ($allocation->fertilizerType->subsidized_price ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="ti ti-inbox fs-1 text-muted"></i>
                                    <h5 class="mt-3">Tidak Ada Data</h5>
                                    <p class="text-muted">Belum ada alokasi subsidi.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    $(document).ready(function() {
        // Cek apakah DataTable sudah di-initialize sebelumnya
        if (!$.fn.DataTable.isDataTable('#subsidyAllocationTable')) {
            $('#subsidyAllocationTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "order": [[0, 'asc']],
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data",
                    "zeroRecords": "Tidak ada data yang ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        }
    });
</script>
@endsection
@endsection