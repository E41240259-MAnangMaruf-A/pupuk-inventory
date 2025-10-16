@if (Route::is(['transactions.index']))
    <div class="modal fade" id="add-sales-new">
        <div class="modal-dialog add-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4> Tambah Transaksi</h4>
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
                            <div class="row">
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Petani<span
                                                class="text-danger ms-1">*</span></label>
                                        <div class="row">
                                            <div class="col-lg-10 col-sm-10 col-10">
                                                <select class="form-select farmer-select" name="farmer_id"
                                                    style="width: 100%; height: 48px !important;">
                                                    <option value="">Ketik NIK atau Nama</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Transaksi<span
                                                class="text-danger ms-1">*</span></label>
                                        <div class="input-groupicon calender-input">
                                            <i data-feather="calendar" class="info-img"></i>
                                            <input type="text" id="transaction-date"
                                                class="datetimepicker form-control" placeholder="Pilih Tanggal"
                                                value="{{ old('transaction_date') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-6 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Pupuk<span class="text-danger ms-1">*</span></label>
                                        <div class="input-groupicon select-code">
                                            <select class="form-select fertilizer-select" style="width: 100%;">
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
                            <div class="table-responsive no-pagination mb-3">
                                <table class="table datanew" id="transaction-table">
                                    <thead>
                                        <tr>
                                            <th>Nama Pupuk</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th>Stok Subsidi</th>
                                            <th>Stok Non-Subsidi</th>
                                            <th>Keterangan</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (old('fertilizer_id'))
                                            @foreach (old('fertilizer_id') as $i => $fertilizerId)
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="fertilizer_id[]"
                                                            value="{{ $fertilizerId }}">
                                                        {{ old('fertilizer_name')[$i] ?? 'Produk Lama' }}
                                                    </td>
                                                    <td><input type="number" class="form-control quantity"
                                                            name="quantity[]" value="{{ old('quantity')[$i] ?? 1 }}"
                                                            min="1"></td>
                                                    <td>{{ old('unit')[$i] ?? '' }}</td>
                                                    <td><input type="number" class="form-control subsidized_price"
                                                            name="subsidized_price[]"
                                                            value="{{ old('subsidized_price')[$i] ?? 0 }}" readonly>
                                                    </td>
                                                    <td>{{ old('unit')[$i] ?? '' }}</td>
                                                    <td><input type="number" class="form-control retail_price"
                                                            name="retail_price[]"
                                                            value="{{ old('retail_price')[$i] ?? 0 }}" readonly></td>
                                                    <td>{{ old('unit')[$i] ?? '' }}</td>
                                                    <td>{{ old('description')[$i] ?? '' }}</td>
                                                    <td><input type="number" class="form-control subtotal"
                                                            name="subtotal[]" value="{{ old('subtotal')[$i] ?? 0 }}"
                                                            readonly></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Belum ada produk</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <div class="col-lg-2 col-sm-5 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Pembayaran<span
                                                class="text-danger ms-1">*</span></label>
                                        <div class="input-groupicon select-code">
                                            <input type="text" id="total-payment" name="total_payment"
                                                class="form-control p-2 text-end" placeholder="0"
                                                value="{{ old('total-payment') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-5 col-12">
                                    <div class="mb-3">
                                        <label class="form-label">Kembalian</label>
                                        <div class="input-groupicon select-code">
                                            <input type="text" id="total-change" name="total_change"
                                                class="form-control p-2 text-end fw-semibold text-success"
                                                value="{{ old('total-change', 'Rp 0') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 ms-auto">
                                    <div class="total-order w-100 max-widthauto m-auto mb-4">
                                        <ul class="border-1 rounded-2">
                                            <li
                                                class="border-bottom d-flex justify-content-between align-items-center px-3 py-2">
                                                <h4 class="border-end mb-0 pe-2">Total Transaksi</h4>
                                                <h5 id="grand-total-text" class="mb-0">Rp. 0</h5>
                                            </li>
                                            <li
                                                class="border-bottom d-flex justify-content-between align-items-center px-3 py-2">
                                                <h4 class="border-end mb-0 pe-2">Total Bayar</h4>
                                                <h5 id="total-payment-text" class="mb-0">Rp. 0</h5>
                                            </li>
                                            <li
                                                class="border-bottom d-flex justify-content-between align-items-center px-3 py-2">
                                                <h4 class="border-end mb-0 pe-2">Kembalian</h4>
                                                <h5 id="total-change-text" class="mb-0">Rp. 0</h5>
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

@push('scripts')
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var myModal = new bootstrap.Modal(document.getElementById('add-sales-new'));
                myModal.show();
            });
        </script>
    @endif

    <script>
        const totalEl = document.getElementById('grand-total-text');
        const totalPaymentEl = document.getElementById('total-payment');
        const totalChangeEl = document.getElementById('total-change');
        const totalPaymentTextEl = document.getElementById('total-payment-text');
        const totalChangeTextEl = document.getElementById('total-change-text');

        // 🔹 Parse text seperti "Rp. 1.800,00" → 1800
        function parseRupiah(text) {
            if (!text) return 0;

            // Hapus semua karakter selain angka dan koma
            let cleaned = text.replace(/[^\d,]/g, '');

            // Hapus koma dan desimal (misalnya ,00)
            cleaned = cleaned.split(',')[0];

            // Hapus titik ribuan kalau ada
            cleaned = cleaned.replace(/\./g, '');

            return parseInt(cleaned) || 0;
        }


        // 🔹 Format angka jadi Rp x.xxx
        function formatRupiah(num) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
        }

        function handlePaymentAndChange() {
            const total = parseRupiah(totalEl.textContent);
            const bayar = parseRupiah(totalPaymentEl.value);
            const kembali = bayar - total;

            totalChangeEl.value = kembali > 0 ? formatRupiah(kembali) : 'Rp 0';
            totalPaymentTextEl.textContent = formatRupiah(bayar);
            totalChangeTextEl.textContent = kembali > 0 ? formatRupiah(kembali) : 'Rp 0';
        }

        $(document).ready(function() {
            $('#transaction-date').val(moment().format('YYYY-MM-DD'));

            totalPaymentEl.addEventListener('input', function() {
                // Bersihkan input biar tetap bisa format ribuan saat ketik
                let raw = this.value.replace(/[^\d]/g, '');
                this.value = raw ? new Intl.NumberFormat('id-ID').format(parseInt(raw)) : '';

                handlePaymentAndChange();
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
                            q: params.term,
                            farmer_id: $('.farmer-select').val()
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
                    console.log(val);

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
                let tableBody = $('#transaction-table tbody');

                tableBody.find('tr:contains("Belum ada produk")').remove();

                let existingRow = tableBody.find(`input[name="fertilizer_id[]"][value="${data.id}"]`)
                    .closest('tr');

                if (existingRow.length > 0) {
                    let quantityInput = existingRow.find('input.quantity');
                    let currentquantity = parseInt(quantityInput.val()) || 0;
                    quantityInput.val(currentquantity + 1);

                    let purchasePrice = parseFloat(existingRow.find('input.subsidized_price').val()) || 0;
                    existingRow.find('.subtotal').val((currentquantity + 1) * purchasePrice);
                } else {
                    let purchasePrice = data.is_subsidized ? data.subsidized_price : data.retail_price;

                    let newRow = `
                    <tr>
                        <td>
                            ${data.text} 
                            <input type="hidden" name="fertilizer_id[]" value="${data.id}">
                            <input type="hidden" class="subsidized_price" name="subsidized_price[]" value="${data.subsidized_price || 0}">
                            <input type="hidden" class="retail_price" name="retail_price[]" value="${data.retail_price || 0}">
                        </td>
                        <td>
                            <input type="number" class="form-control quantity" name="quantity[]" value="1" min="1">
                        </td>
                        <td>${data.unit || ''}</td>
                        <td>${data.stock_subsidized ?? 0}</td>
                        <td>${data.stock_non_subsidized ?? 0}</td>
                        <td>
                            ${data.description || ''} ${data.is_subsidized ? '(Subsidi)' : '(Non-Subsidi)'}
                            <br>
                            <small class="text-muted">
                                <strong class="${(data.stock_subsidized ?? 0) > 0 ? 'text-success' : 'text-muted'}">
                                    Rp ${(data.subsidized_price || 0).toLocaleString('id-ID')}
                                </strong><br>
                                Harga Non-Subsidi: 
                                <strong class="${(data.stock_subsidized ?? 0) === 0 ? 'text-success' : 'text-muted'}">
                                    Rp ${(data.retail_price || 0).toLocaleString('id-ID')}
                                </strong>
                            </small>
                        </td>
                        <td>
                            <input type="number" class="form-control subtotal" name="subtotal[]" 
                                value="${data.is_subsidized ? data.subsidized_price || 0 : data.retail_price || 0}" readonly>
                        </td>
                    </tr>
                    `;

                    tableBody.append(newRow);
                }

                updateGrandTotal();
            });

            // update subtotal & grand total ketika quantity berubah
            $(document).on('input', '.quantity', function() {
                let row = $(this).closest('tr');
                let quantity = parseFloat($(this).val()) || 0;
                let price = parseFloat(row.find('input.subsidized_price').val()) || 0;

                row.find('.subtotal').val(quantity * price);

                updateGrandTotal();
                handlePaymentAndChange();
            });

            const select = $('.farmer-select');
            const oldFarmerId = "{{ old('farmer_id') }}";

            $('.farmer-select').select2({
                dropdownParent: $('#add-sales-new'),
                placeholder: 'Ketik NIK atau Nama',
                allowClear: true,
                ajax: {
                    url: "{{ route('farmers.ajax') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            farmer_id: $('#farmer-select').val()
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
@endpush
