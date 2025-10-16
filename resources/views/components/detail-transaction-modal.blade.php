@if (Route::is(['transactions.index']))
    @foreach ($transactions as $transaction)
        <div class="modal fade" id="sales-details-{{ $transaction->id }}">
            <div class="modal-dialog sales-details-modal">
                <div class="modal-content">
                    <div class="page-header p-4 border-bottom mb-0 d-flex justify-content-between align-items-center">
                        <div class="page-title modal-detail">
                            <h4 class="mb-0">Detail Transaksi</h4>
                        </div>
                        <ul class="table-top-head d-flex mb-0">
                            <li>
                                <a data-bs-toggle="tooltip" title="Pdf">
                                    <img src="{{ URL::asset('build/img/icons/pdf.svg') }}" alt="pdf">
                                </a>
                            </li>
                            <li>
                                <a data-bs-toggle="tooltip" title="Print">
                                    <img src="{{ URL::asset('build/img/icons/printer.svg') }}" alt="print">
                                </a>
                            </li>
                        </ul>
                        <div class="page-btn">
                            <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                <i data-feather="arrow-left" class="me-2"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <form>
                        <div class="card border-0">
                            <div class="card-body pb-0">
                                <div class="invoice-box table-height"
                                    style="max-width:1600px;width:100%;padding:0;font-size:14px;line-height:24px;color:#555;">
                                    <div class="row sales-details-items d-flex align-items-start">
                                        <!-- Customer Info -->
                                        <div class="col-md-4 details-item">
                                            <h6>Info Pelanggan</h6>
                                            <h4 class="mb-1">{{ $transaction->farmer->farmer_name }}</h4>
                                            <p class="mb-0">Alamat: <span>{{ $transaction->farmer->address }}</span>
                                            </p>
                                            <p class="mb-0">No. Telepon:
                                                <span>{{ $transaction->farmer->phone_number }}</span></p>
                                        </div>

                                        <!-- Land Info -->
                                        <div class="col-md-4 details-item">
                                            <h6>Info Lahan</h6>
                                            <p class="mb-0">Luas Lahan:
                                                <span>{{ $transaction->farmer->land_area }}</span></p>
                                            <p class="mb-0">Lokasi Lahan:
                                                <span>{{ $transaction->farmer->land_location }}</span></p>
                                            <p class="mb-0">Status Lahan:
                                                <span>{{ $transaction->farmer->land_status }}</span></p>
                                            <p class="mb-0">Komoditas Utama:
                                                <span>{{ $transaction->farmer->main_commodity }}</span></p>
                                            <p class="mb-0">Rata-rata Panen:
                                                <span>{{ $transaction->farmer->average_harvest }}</span></p>
                                        </div>

                                        <!-- Transaction Info -->
                                        <div class="col-md-4 details-item">
                                            <h6>Info Transaksi</h6>
                                            <p class="mb-0">ID Transaksi:
                                                <span
                                                    class="fs-16 text-primary ms-2">#{{ $transaction->transaction_number }}</span>
                                            </p>
                                            <p class="mb-0">Petugas:
                                                <span
                                                    class="ms-2 text-gray-9">{{ $transaction->user->name ?? '-' }}</span>
                                            </p>
                                            <p class="mb-0">Tanggal:
                                                <span class="ms-2 text-gray-9">
                                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M, Y') }}
                                                </span>
                                            </p>
                                            <p class="mb-0">Status Pembayaran:
                                                <span
                                                    class="badge badge-soft-success shadow-none badge-xs d-inline-flex align-items-center ms-2">
                                                    <i
                                                        class="ti ti-point-filled"></i>{{ $transaction->payment_status }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Order Summary -->
                                    <h5 class="order-text mt-4">Detail Transaksi Pupuk</h5>
                                    <div class="table-responsive no-pagination mb-3">
                                        <table class="table datanew">
                                            <thead>
                                                <tr>
                                                    <th>Pupuk</th>
                                                    <th>Harga Satuan (Rp)</th>
                                                    <th>Tersubsidi</th>
                                                    <th>Jumlah</th>
                                                    <th>Total (Rp)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($transaction->details as $item)
                                                    <tr>
                                                        <td>{{ $item->fertilizerType->fertilizer_name }}</td>
                                                        <td>{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                                        <td>
                                                            @if ($item->is_subsidized)
                                                                <span class="badge bg-success">Ya</span>
                                                            @else
                                                                <span class="badge bg-secondary">Tidak</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Total -->
                                    <div class="row">
                                        <div class="col-lg-6 ms-auto">
                                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                                <ul class="rounded-3">
                                                    <li>
                                                        <h4>Biaya Total</h4>
                                                        <h5>Rp.{{ number_format($transaction->total_amount, 0, ',', '.') }}
                                                        </h5>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif
