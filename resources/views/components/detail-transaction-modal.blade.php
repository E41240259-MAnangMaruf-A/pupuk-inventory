@if (Route::is(['transactions.index']))
    <div class="modal fade" id="sales-details-modal">
        <div class="modal-dialog sales-details-modal">
            <div class="modal-content">
                <div class="page-header p-4 border-bottom mb-0">
                    <div class="add-item d-flex align-items-center">
                        <div class="page-title modal-datail">
                            <h4 class="mb-0 me-2">Sales Detail</h4>
                        </div>
                    </div>
                    <ul class="table-top-head">
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img
                                    src="{{ URL::asset('build/img/icons/pdf.svg') }}" alt="img"></a>
                        </li>
                        <li>
                            <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><img
                                    src="{{ URL::asset('build/img/icons/printer.svg') }}" alt="img"></a>
                        </li>
                    </ul>
                    <div class="page-btn">
                        <a href="{{ url('online-orders') }}" class="btn btn-secondary"><i data-feather="arrow-left"
                                class="me-2"></i> Back to Sales</a>
                    </div>
                </div>

                <form action="{{ url('online-orders') }}">
                    <div class="card border-0">
                        <div class="card-body pb-0">
                            <div class="invoice-box table-height"
                                style="max-width: 1600px;width:100%;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                                <div class="row sales-details-items d-flex">
                                    {{-- Customer Info --}}
                                    <div class="col-lg-4 details-item">
                                        <h6>Nama Pelanggan:</h6>
                                        <h4 class="mb-1" id="customer-name">-</h4>
                                    </div>

                                    {{-- Company Info --}}
                                    <div class="col-lg-4 details-item">
                                        <h6>Petugas</h6>
                                        <h4 class="mb-1" id="officer-name">-</h4>
                                    </div>

                                    {{-- Invoice Info --}}
                                    <div class="col-md-4 details-item">
                                        <h6>Informasi Transaksi</h6>
                                        <p class="mb-0">Nomor: <span class="fs-16 text-primary ms-2"
                                                id="transaction-number">-</span></p>
                                        <p class="mb-0">Tanggal: <span class="ms-2 text-gray-9"
                                                id="transaction-date">-</span></p>
                                        <p class="mb-0">Payment Status:
                                            <span
                                                class="badge badge-soft-success shadow-none badge-xs d-inline-flex align-items-center ms-2"
                                                id="payment-status">
                                                <i class="ti ti-point-filled"></i> -
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                {{-- Order Summary --}}
                                <h5 class="order-text mt-3">Order Summary</h5>
                                <div class="table-responsive no-pagination mb-3">
                                    <table class="table datanew" id="order-summary-table">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th>Satuan</th>
                                                <th>Jumlah</th>
                                                <th>Harga Satuan</th>
                                                <th>Subtotal</th>
                                                <th>Bersubsidi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="order-summary-body">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Total Section --}}
                            <div class="row">
                                <div class="col-lg-6 ms-auto">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul class="border-1 rounded-1">
                                            <li class="border-bottom">
                                                <h4 class="border-end">Total Transaksi</h4>
                                                <h5 id="total-amount-label">Rp 0</h5>
                                            </li>
                                            <li class="border-bottom">
                                                <h4 class="border-end">Total Bayar</h4>
                                                <h5 id="total-payment-label">Rp 0</h5>
                                            </li>
                                            <li class="border-bottom">
                                                <h4 class="border-end">Kembalian</h4>
                                                <h5 id="total-change-label">Rp 0</h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
