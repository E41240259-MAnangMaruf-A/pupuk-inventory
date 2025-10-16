<?php $page = 'reports'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4 class="fw-bold">Laporan Pergerakan Pupuk</h4>
                    <h6>Monitoring Masuk dan Keluar Stok Pupuk</h6>
                </div>
            </div>

            <!-- Tabel dengan ID yang UNIK -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="fertilizerMovementTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">Tanggal</th>
                                    <th width="15%">Jenis Pupuk</th>
                                    <th width="10%">Tipe</th>
                                    <th width="10%">Perubahan</th>
                                    <th width="10%">Stok Awal</th>
                                    <th width="10%">Stok Akhir</th>
                                    <th width="25%">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $index => $movement)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $movement->fertilizerType->fertilizer_name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($movement->type == 'in')
                                                <span class="badge bg-success">Masuk</span>
                                            @else
                                                <span class="badge bg-danger">Keluar</span>
                                            @endif
                                        </td>
                                        <td class="{{ $movement->type == 'in' ? 'text-success' : 'text-danger' }}">
                                            {{ $movement->type == 'in' ? '+' : '-' }}{{ $movement->stock_change }}
                                        </td>
                                        <td>{{ $movement->current_stock }}</td>
                                        <td>{{ $movement->final_stock }}</td>
                                        <td>{{ $movement->note ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="ti ti-inbox fs-1 text-muted"></i>
                                            <h5 class="mt-3">Tidak Ada Data</h5>
                                            <p class="text-muted">Belum ada pergerakan stok pupuk.</p>
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

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Cek apakah DataTable sudah di-initialize sebelumnya
                if (!$.fn.DataTable.isDataTable('#fertilizerMovementTable')) {
                    $('#fertilizerMovementTable').DataTable({
                        "pageLength": 10,
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        "order": [
                            [1, 'desc']
                        ],
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
                        },
                        "columnDefs": [{
                                "orderable": false,
                                "targets": [0]
                            } // Kolom No tidak bisa diurutkan
                        ]
                    });
                }
            });
        </script>
    @endpush
@endsection
