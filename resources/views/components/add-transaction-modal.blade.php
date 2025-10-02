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
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="card border-0">
                        <div class="card-body pb-0">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="table-responsive no-pagination mb-3">
                                <table class="table datanew">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th>Harga Beli(Rp.)</th>
                                            <th>Harga Satuan(Rp.)</th>
                                            <th>Satuan</th>
                                            <th>Deskripsi/Subsidi</th>
                                            <th>Subtotal(Rp.)</th>
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
                                                <select class="form-select customer-select" name="farmer_id"
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
                                                <h4 class="border-end">Grand Total</h4>
                                                <h5>$ 0.00</h5>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary add-cancel me-3"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary add-sale">Checkout</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@section('scripts')
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('add-sales-new'));
                myModal.show();
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#transaction-date').datetimepicker({
                format: 'Y-m-d',
                defaultDate: new Date()
            });

            $('.fertilizer-select').select2({
                dropdownParent: $('#add-sales-new'),
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
                minimumInputLength: 0
            });

            function updateGrandTotal() {
                let grandTotal = 0;
                $('.subtotal').each(function() {
                    let val = parseFloat($(this).val()) || 0;
                    grandTotal += val;
                });

                // format ke Rp (atau sesuai kebutuhan)
                let formattedTotal = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(grandTotal);

                $('.total-order h5').text(formattedTotal);
            }

            // setelah row ditambahkan
            $('.fertilizer-select').on('select2:select', function(e) {
                let data = e.params.data;
                let tableBody = $('.datanew tbody');

                let existingRow = tableBody.find(`input[name="fertilizer_id[]"][value="${data.id}"]`)
                    .closest('tr');

                if (existingRow.length > 0) {
                    let qtyInput = existingRow.find('input.qty');
                    let currentQty = parseInt(qtyInput.val()) || 0;
                    qtyInput.val(currentQty + 1);

                    let purchasePrice = parseFloat(existingRow.find('input.purchase_price').val()) || 0;
                    existingRow.find('.subtotal').val((currentQty + 1) * purchasePrice);
                } else {
                    let purchasePrice = data.is_subsidized ? data.subsidized_price : data.retail_price;

                    let newRow = `
        <tr>
            <td>
                ${data.text} 
                <input type="hidden" name="fertilizer_id[]" value="${data.id}">
            </td>
            <td><input type="number" class="form-control qty" name="qty[]" value="1" min="1"></td>
            <td><input type="number" class="form-control purchase_price" name="purchase_price[]" value="${purchasePrice || 0}" readonly></td>
            <td><input type="number" class="form-control retail_price" name="retail_price[]" value="${data.retail_price || 0}" readonly></td>
            <td>${data.unit || ''}</td>
            <td>${data.description || ''} ${data.is_subsidized ? '(Subsidi)' : ''}</td>
            <td><input type="number" class="form-control subtotal" name="subtotal[]" value="${purchasePrice || 0}" readonly></td>
        </tr>
        `;
                    tableBody.append(newRow);
                }

                updateGrandTotal();
            });

            // update subtotal & grand total ketika qty berubah
            $(document).on('input', '.qty', function() {
                let row = $(this).closest('tr');
                let qty = parseFloat($(this).val()) || 0;
                let price = parseFloat(row.find('input.purchase_price').val()) || 0;
                row.find('.subtotal').val(qty * price);

                updateGrandTotal();
            });

            $('.customer-select').select2({
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
                minimumInputLength: 0
            });
        });
    </script>
@endsection
