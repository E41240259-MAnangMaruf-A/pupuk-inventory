<?php $page = 'reports'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4 class="fw-bold">Laporan Keuangan Koperasi</h4>
                    <h6>Ringkasan Pemasukan dan Pengeluaran</h6>
                </div>
            </div>

            <!-- Tabel dengan ID yang UNIK -->
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table id="financialReportTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No. Transaksi</th>
                                    <th>Petani</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $index => $transaction)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                        <td>{{ $transaction->transaction_number }}</td>
                                        <td>{{ $transaction->farmer->farmer_name ?? '-' }}</td>
                                        <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $transaction->payment_status == 'paid' ? 'success' : 'warning' }}">
                                                {{ $transaction->payment_status == 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="ti ti-inbox fs-1 text-muted"></i>
                                            <h5 class="mt-3">Tidak Ada Data</h5>
                                            <p class="text-muted">Belum ada transaksi.</p>
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
                if (!$.fn.DataTable.isDataTable('#financialReportTable')) {
                    $('#financialReportTable').DataTable({
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
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
