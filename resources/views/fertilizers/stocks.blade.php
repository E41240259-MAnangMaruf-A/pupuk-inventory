<?php $page = 'low-stocks'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title me-auto">
                    <h4 class="fw-bold">Stok Pupuk</h4>
                    <h6>Kelola stok pupuk tersedia</h6>
                </div>
                <ul class="table-top-head low-stock-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img
                                src="{{ URL::asset('build/img/icons/pdf.svg') }}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img
                                src="{{ URL::asset('build/img/icons/excel.svg') }}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i
                                class="ti ti-refresh"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                class="ti ti-chevron-up"></i></a>
                    </li>
                    <li>
                        <a href="#" class="btn btn-primary w-auto shadow-none" data-bs-toggle="modal"
                            data-bs-target="#add-stock">
                            <i class="ti ti-circle-plus me-1"></i>Edit Stok
                        </a>
                    </li>
                </ul>
            </div>
            <div class="mb-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <ul class="nav nav-pills low-stock-tab d-flex me-2 mb-0" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                aria-selected="true">Seluruh Stok</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile"
                                aria-selected="false">Riwayat Stok</button>
                        </li>

                    </ul>
                </div>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <!-- /product list -->
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <div class="search-set">
                                    <div class="search-input">
                                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                                    </div>
                                </div>

                                <div
                                    class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                                    <div class="dropdown me-2">
                                        <a href="javascript:void(0);"
                                            class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center"
                                            data-bs-toggle="dropdown">
                                            Kategori Subsidi
                                        </a>
                                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Subsidi</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Non
                                                    Subsidi</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table datatable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="no-sort">
                                                    <label class="checkboxs">
                                                        <input type="checkbox" id="select-all">
                                                        <span class="checkmarks"></span>
                                                    </label>
                                                </th>
                                                <th>Nama Pupuk</th>
                                                <th>Satuan</th>
                                                <th>Harga Subsidi</th>
                                                <th>Harga Eceran</th>
                                                <th>Stok Saat Ini</th>
                                                {{-- <th class="no-sort">Aksi</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($fertilizerStocks as $item)
                                                <tr>
                                                    <td>
                                                        <label class="checkboxs">
                                                            <input type="checkbox" value="{{ $item->id }}">
                                                            <span class="checkmarks"></span>
                                                        </label>
                                                    </td>
                                                    <td>{{ $item->fertilizer_name }}</td>
                                                    <td>{{ $item->unit ?? '-' }}</td>
                                                    <td>Rp {{ number_format($item->subsidized_price, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($item->retail_price, 0, ',', '.') }}</td>
                                                    <td>{{ $item->current_stock }}</td>
                                                    {{-- <td class="action-table-data">
                                                        <div class="edit-delete-action">
                                                            <a class="me-2 p-2"
                                                                href="{{ route('fertilizer-stocks.edit', $item->stock_id) }}">
                                                                <i data-feather="edit" class="feather-edit"></i>
                                                            </a>
                                                            <form
                                                                action="{{ route('fertilizer-stocks.destroy', $item->stock_id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="p-2 btn btn-link text-danger">
                                                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td> --}}
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- /product list -->
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <!-- /product list -->
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                <div class="search-set">
                                    <div class="search-input">
                                        <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                                    </div>
                                </div>
                                <div
                                    class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                                    <div class="dropdown me-2">
                                        <a href="javascript:void(0);"
                                            class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center"
                                            data-bs-toggle="dropdown" id="fertilizerFilterBtn">
                                            Jenis Pupuk
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end p-3" id="fertilizerFilterMenu">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1"
                                                    data-type="all">All</a>
                                            </li>
                                            @foreach ($fertilizers as $type)
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item rounded-1"
                                                        data-type="{{ $type->fertilizer_name }}">
                                                        {{ $type->fertilizer_name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);"
                                            class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center"
                                            data-bs-toggle="dropdown">
                                            Sort By : Last 7 Days
                                        </a>
                                        <ul class="dropdown-menu  dropdown-menu-end p-3">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Recently
                                                    Added</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"
                                                    class="dropdown-item rounded-1">Ascending</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"
                                                    class="dropdown-item rounded-1">Desending</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last
                                                    Month</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last 7
                                                    Days</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table datatable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nama Pupuk</th>
                                                <th>Stok Awal</th>
                                                <th>Stok Akhir</th>
                                                <th>Jenis Transaksi</th>
                                                <th>Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($stockHistories as $history)
                                                <tr>
                                                    <td>{{ $history->fertilizerType->fertilizer_name ?? '-' }}</td>
                                                    <td>{{ $history->current_stock }}</td>
                                                    <td>{{ $history->final_stock }}</td>
                                                    <td>
                                                        {{ $history->type === 'in' ? 'Stok Masuk' : ($history->type === 'out' ? 'Stok Keluar' : '-') }}
                                                    </td>
                                                    <td>{{ $history->created_at->format('Y-m-d H:i') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <!-- /product list -->
                    </div>

                </div>
            </div>
        </div>
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0 text-gray-9">2014 - 2025 &copy; DreamsPOS. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">Dreams</a></p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function() {
            function attachFertilizerListener() {
                let fertSelect = document.getElementById('fertilizerSelect');
                if (!fertSelect) return;

                // Prevent duplicate listener
                fertSelect.removeEventListener('change', handleChange);
                fertSelect.addEventListener('change', handleChange);

                // If using jQuery-based plugins (Select2 / Bootstrap Select)
                if (window.jQuery) {
                    let $el = window.jQuery(fertSelect);

                    // For Select2
                    if ($el.hasClass('select2-hidden-accessible')) {
                        $el.off('select2:select').on('select2:select', function(e) {
                            handleChange.call(fertSelect, e);
                        });
                    }

                    // For Bootstrap Select
                    $el.off('changed.bs.select').on('changed.bs.select', function(e) {
                        handleChange.call(fertSelect, e);
                    });
                }
            }

            function handleChange(e) {
                let selected = this.options[this.selectedIndex];
                let fertId = selected.value;
                let fertName = selected.dataset.name;
                let fertPrice = parseFloat(selected.dataset.price || 0);
                let currentStock = parseInt(selected.dataset.stock || 0);

                if (fertId === "") return;

                let tableBody = document.querySelector('#productTable tbody');

                let row = document.createElement('tr');
                row.innerHTML = `
            <td>
                ${fertName}
                <input type="hidden" name="fertilizers[]" value="${fertId}">
            </td>
            <td>
                <input type="number" name="price[]" class="form-control price" value="${fertPrice}" readonly>
            </td>
            <td>
                <input type="number" class="form-control current-stock" value="${currentStock}" readonly>
            </td>
            <td>
                <input type="number" name="added_stock[]" class="form-control added-stock" value="0" min="1">
            </td>
            <td>
                <input type="number" name="final_stock[]" class="form-control final-stock" value="${currentStock}" readonly>
            </td>
            <td>
                <input type="number" name="subtotal[]" class="form-control subtotal" value="0" readonly>
            </td>
        `;

                // Watch "added stock" input to update final stock & subtotal
                let addedStockInput = row.querySelector('.added-stock');
                addedStockInput.addEventListener('input', function() {
                    let added = parseInt(this.value) || 0;
                    let finalStockInput = row.querySelector('.final-stock');
                    let subtotalInput = row.querySelector('.subtotal');

                    finalStockInput.value = currentStock + added;
                    subtotalInput.value = (fertPrice * added).toFixed(2);

                    updateGrandTotal();
                });

                tableBody.appendChild(row);
                this.value = "";

                if (window.jQuery) window.jQuery(this).trigger('change.select2'); // reset if select2
            }

            function updateGrandTotal() {
                let total = 0;
                document.querySelectorAll('#productTable .subtotal').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                let grand = document.getElementById('grandTotal');
                if (grand) {
                    grand.innerText = `Rp ${total.toFixed(2)}`;
                }
            }

            // Run after DOM ready
            document.addEventListener("DOMContentLoaded", attachFertilizerListener);

            // Also run again after Bootstrap modal is shown (in case it's injected late)
            document.addEventListener('shown.bs.modal', function(e) {
                if (e.target.id === 'add-stock') {
                    attachFertilizerListener();
                }
            });
        })();
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let dateInput = document.getElementById('stockDate');
            if (dateInput && !dateInput.value) {
                const today = new Date().toISOString().split('T')[0]; // yyyy-mm-dd
                dateInput.value = today;
            }

            // Initialize your datetimepicker if needed
            if (window.jQuery && jQuery.fn.datetimepicker) {
                jQuery('.datetimepicker').datetimepicker({
                    format: 'YYYY-MM-DD',
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterItems = document.querySelectorAll('#fertilizerFilterMenu .dropdown-item');
            const tableRows = document.querySelectorAll('#productTable tbody tr');
            const filterBtn = document.getElementById('fertilizerFilterBtn');

            filterItems.forEach(item => {
                item.addEventListener('click', function() {
                    const type = this.dataset.type;

                    // Update dropdown button text
                    filterBtn.textContent = type === 'all' ? 'Jenis Pupuk' : type;

                    tableRows.forEach(row => {
                        const productName = row.querySelector('td').textContent.trim();
                        console.log(productName);
                        

                        if (type === 'all' || productName.includes(type)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
@endsection
