@if (Route::is(['transactions.index']))
    <div class="modal fade" id="add-sales-new">
        <div class="modal-dialog add-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4> Add Sales</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('online-orders') }}">
                    <div class="card border-0">
                        <div class="card-body pb-0">
                            <div class="table-responsive no-pagination mb-3">
                                <table class="table datanew">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Purchase Price($)</th>
                                            <th>Discount($)</th>
                                            <th>Tax(%)</th>
                                            <th>Tax Amount($)</th>
                                            <th>Unit Cost($)</th>
                                            <th>Total Cost(%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Name<span
                                                class="text-danger ms-1">*</span></label>
                                        <div class="row">
                                            <div class="col-lg-10 col-sm-10 col-10">
                                                <select class="form-select select2-ajax" name="farmer_id"
                                                    style="width: 100%; height: 48px !important;">
                                                    <option value="">Ketik NIK atau Nama</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-sm-2 col-2 ps-0 d-flex">
                                                <a href="#"
                                                    class="btn btn-dark flex-fill d-flex justify-content-center align-items-center"
                                                    data-bs-toggle="modal" data-bs-target="#add_customer">
                                                    <i data-feather="plus-circle"></i>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Transaksi<span
                                                class="text-danger ms-1">*</span></label>
                                        <div class="input-groupicon calender-input">
                                            <i data-feather="calendar" class="info-img"></i>
                                            <input type="text" id="transaction-date"
                                                class="datetimepicker form-control" placeholder="Pilih Tanggal">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Pupuk<span class="text-danger ms-1">*</span></label>
                                        <div class="input-groupicon select-code">
                                            <select class="form-select fertilizer-select" name="fertilizer_id"
                                                style="width: 100%;">
                                                <option value="">Ketik nama pupuk atau scan di sini</option>
                                            </select>
                                            <div class="addonset">
                                                <img src="{{ URL::asset('build/img/icons/qrcode-scan.svg') }}"
                                                    alt="img">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 ms-auto">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul class="border-1 rounded-2">
                                            <li class="border-bottom">
                                                <h4 class="border-end">Order Tax</h4>
                                                <h5>$ 0.00</h5>
                                            </li>
                                            <li class="border-bottom">
                                                <h4 class="border-end">Discount</h4>
                                                <h5>$ 0.00</h5>
                                            </li>
                                            <li class="border-bottom">
                                                <h4 class="border-end">Shipping</h4>
                                                <h5>$ 0.00</h5>
                                            </li>
                                            <li class="border-bottom">
                                                <h4 class="border-end">Grand Total</h4>
                                                <h5>$ 0.00</h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Order Tax<span
                                                class="text-danger ms-1">*</span></label>
                                        <div class="input-groupicon select-code">
                                            <input type="text" value="0" class="form-control p-2">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Discount<span
                                                class="text-danger ms-1">*</span></label>
                                        <div class="input-groupicon select-code">
                                            <input type="text" value="0" class="form-control p-2">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Shipping<span
                                                class="text-danger ms-1">*</span></label>
                                        <div class="input-groupicon select-code">
                                            <input type="text" value="0" class="form-control p-2">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="mb-3 mb-5">
                                        <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                        <select class="select">
                                            <option>Select</option>
                                            <option>Completed</option>
                                            <option>Inprogress</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary add-cancel me-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary add-sale">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@section('scripts')
    <!-- JS -->
    <script>
        $(document).ready(function() {
            $('#transaction-date').datetimepicker({
                format: 'Y-m-d',
                defaultDate: new Date()
            });

            $('.fertilizer-select').select2({
                placeholder: 'Ketik nama pupuk atau scan di sini',
                allowClear: true,
                ajax: {
                    url: "{{ route('fertilizers.ajax') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // kata kunci pencarian
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results // format JSON harus {id:1, text:"Urea"}
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });

            $('.select2-ajax').select2({
                placeholder: 'Ketik NIK atau Nama',
                allowClear: true,
                ajax: {
                    url: "{{ route('farmers.ajax') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2-ajax').select2({
                dropdownParent: $('#add-sales-new'),
                placeholder: 'Ketik NIK atau Nama',
                allowClear: true,
                ajax: {
                    url: "{{ route('farmers.ajax') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });
        });
    </script>
@endsection
