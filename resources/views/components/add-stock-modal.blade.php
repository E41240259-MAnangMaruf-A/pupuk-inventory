@if (Route::is(['fertilizers.stock']))
    <div class="modal fade" id="add-stock">
        <div class="modal-dialog add-centered">
            <div class="modal-content">
                <div class="page-wrapper p-0 m-0">
                    <div class="content p-0">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Tambah Stok Pupuk</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('fertilizers.stock.store') }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-6 col-sm-12">
                                            <div class="input-blocks">
                                                <label>Tanggal</label>

                                                <div class="input-groupicon calender-input">
                                                    <i data-feather="calendar" class="info-img"></i>
                                                    <input type="text" class="datetimepicker form-control"
                                                        id="stockDate" placeholder="Select Date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Nama Pupuk<span
                                                        class="text-danger ms-1">*</span></label>
                                                <select id="fertilizerSelect" class="select">
                                                    <option value="">Pilih Pupuk</option>
                                                    @foreach ($fertilizers as $fertilizer)
                                                        <option value="{{ $fertilizer->id }}"
                                                            data-name="{{ $fertilizer->fertilizer_name }}"
                                                            data-price="{{ $fertilizer->retail_price }}"
                                                            data-stock="{{ $fertilizer->stock?->current_stock ?? 0 }}">
                                                            {{ $fertilizer->fertilizer_name }} ({{ $fertilizer->unit }})
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>

                                    </div>

                                    <div class="table-responsive no-pagination">
                                        <table class="table datanew" id="productTable">
                                            <thead>
                                                <tr>
                                                    <th>Nama Pupuk</th>
                                                    <th>Harga (Rp)</th>
                                                    <th>Stok Saat Ini</th>
                                                    <th>Stok Baru</th>
                                                    <th>Stok Akhir</th>
                                                    <th>Subtotal (Rp)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- products will be inserted here -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 ms-auto">
                                            <div class="total-order w-100 max-widthauto m-auto mb-4">
                                                <ul>
                                                    <li>
                                                        <h4>Total</h4>
                                                        <h5 id="grandTotal">Rp. 0.00</h5>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="modal-footer-btn">
                                            <a href="javascript:void(0);" class="btn btn-cancel me-2"
                                                data-bs-dismiss="modal">Cancel</a>
                                            <button type="submit" class="btn btn-submit">Submit</button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
