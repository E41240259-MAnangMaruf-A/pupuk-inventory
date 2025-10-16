<?php $page = 'stock-adjustment'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Subsidi Stok</h4>
                        <h6>Kelola stok subsidi petani</h6>
                    </div>
                </div>
                <div class="page-btn">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-stock-subsidy">
                        <i class="ti ti-circle-plus me-1"></i> Tambah Stok
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Petani</th>
                                    <th>Pupuk</th>
                                    <th>Kuota Maksimum</th>
                                    <th>Kuota Terpakai</th>
                                    <th>Kuota Tersisa</th>
                                    {{-- <th>Periode</th> --}}
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allocations as $index => $allocation)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $allocation->farmer->farmer_name }}</td>
                                        <td>{{ $allocation->fertilizerType->fertilizer_name }}</td>
                                        <td>{{ $allocation->maximum_quota }}</td>
                                        <td>{{ $allocation->used_quota }}</td>
                                        <td>{{ $allocation->remaining_quota }}</td>
                                        {{-- <td>
                                            @if ($allocation->period_start && $allocation->period_end)
                                                {{ \Carbon\Carbon::parse($allocation->period_start)->format('d M Y') }} -
                                                {{ \Carbon\Carbon::parse($allocation->period_end)->format('d M Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td> --}}
                                        <td>
                                            <span
                                                class="badge {{ $allocation->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($allocation->status) }}
                                            </span>
                                        </td>
                                        <td class="d-flex">
                                            <button type="button" class="btn p-0"><i data-feather="trash-2"
                                                    class="feather-trash-2"></i></button>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="edit-stock-{{ $allocation->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('fertilizers.stock-subsidy.store') }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="farmer_id"
                                                        value="{{ $allocation->farmer_id }}">
                                                    <input type="hidden" name="fertilizer_type_id"
                                                        value="{{ $allocation->fertilizer_type_id }}">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Stock Allocation</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Maximum Quota</label>
                                                            <input type="number" name="maximum_quota" class="form-control"
                                                                value="{{ $allocation->maximum_quota }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Period Start</label>
                                                            <input type="date" name="period_start" class="form-control"
                                                                value="{{ optional($allocation->period_start)->format('Y-m-d') }}"
                                                                required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Period End</label>
                                                            <input type="date" name="period_end" class="form-control"
                                                                value="{{ optional($allocation->period_end)->format('Y-m-d') }}"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            function attachFertilizerSubsidiesListener() {
                let fertSelect = document.getElementById('subsidizedFertilizerSelect');
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
                const selected = this.options[this.selectedIndex];
                const fertId = selected.value;
                const farmerId = document.getElementById('subsidizedFarmerSelect')?.value;

                if (!fertId || !farmerId) {
                    alert("Pilih petani dan pupuk terlebih dahulu!");
                    return;
                }

                // Fetch allocation data
                fetch(`/ajax/allocations?farmer_id=${farmerId}&fertilizer_id=${fertId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        const tableBody = document.querySelector('#fertilizerTable tbody');

                        // Prevent duplicate fertilizer
                        if (tableBody.querySelector(`input[name="fertilizers[]"][value="${fertId}"]`)) {
                            alert('Pupuk ini sudah ditambahkan!');
                            return;
                        }

                        // Build dynamic input row
                        const row = document.createElement('tr');
                        row.innerHTML = `
                <td>
                    ${data.fertilizer_name}
                    <input type="hidden" name="fertilizers[]" value="${data.fertilizer_type_id}">
                    <input type="hidden" name="farmer_id[]" value="${data.farmer_id}">
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control quantity-input" value="0" min="0">
                </td>
                <td>
                    <input type="number" name="used_quota[]" class="form-control bg-secondary-subtle" value="${data.used_quota}" readonly>
                </td>
                <td>
                    <input type="number" name="remaining_quota[]" class="form-control bg-secondary-subtle" value="${data.remaining_quota}" readonly>
                </td>
                <td>
                    <input type="number" name="maximum_quota[]" class="form-control bg-secondary-subtle" value="${data.maximum_quota}" readonly>
                </td>
                <td>
                    ${data.unit}
                </td>
                <td>
                    <input type="number" name="price[]" class="form-control price-input w-100" 
                        style="min-width: 130px;" value="${data.price}" readonly>
                </td>
                <td>
                    <input type="number" name="subtotal[]" class="form-control subtotal w-100" 
                        style="min-width: 150px;" value="0" readonly>
                </td>
                <td>
                    <span class="badge ${data.status === 'active' ? 'bg-success' : 'bg-secondary'}">${data.status}</span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-row">
                        <i class="ti ti-trash"></i>
                    </button>
                </td>
            `;


                        tableBody.appendChild(row);

                        // Event: update remaining quota dynamically
                        const qtyInput = row.querySelector('.quantity-input');

                        qtyInput.addEventListener('input', function() {
                            const qty = parseInt(this.value) || 0;

                            const remainingField = row.querySelector('input[name="remaining_quota[]"]');
                            const maxQuotaField = row.querySelector('input[name="maximum_quota[]"]');
                            const usedQuotaField = row.querySelector('input[name="used_quota[]"]');
                            const priceField = row.querySelector('input[name="price[]"]');
                            const subtotalField = row.querySelector('input[name="subtotal[]"]');

                            const remainingQuota = parseInt(remainingField.value) || 0;
                            const maxQuota = parseInt(maxQuotaField.value) || 0;
                            const price = parseFloat(priceField.value) || 0;

                            // Hitung kuota baru
                            const newMaxQuota = maxQuota + qty;
                            const newRemainingQuota = Math.max(newMaxQuota + remainingQuota, 0);

                            // Hitung subtotal
                            const subtotal = qty * price;

                            // Update nilai input
                            maxQuotaField.value = newMaxQuota >= 0 ? newMaxQuota : 0;
                            remainingField.value = newRemainingQuota;
                            subtotalField.value = subtotal.toFixed(2); // format 2 desimal
                            updateGrandTotal();

                            console.log("Updated max:", newMaxQuota);
                            console.log("Updated remaining:", newRemainingQuota);
                            console.log("Subtotal:", subtotal);
                        });


                        row.querySelector('.remove-row').addEventListener('click', () => {
                            row.remove();
                            updateGrandTotal();
                        });
                    })
                    .catch(err => {
                        console.error("AJAX error:", err);
                        alert("Terjadi kesalahan saat memuat data alokasi.");
                    });
            }

            // Run after DOM ready
            document.addEventListener("DOMContentLoaded", attachFertilizerSubsidiesListener);

            // Also run again after Bootstrap modal is shown (in case it's injected late)
            document.addEventListener('shown.bs.modal', function(e) {
                if (e.target.id === 'add-stock-subsidy') {
                    attachFertilizerSubsidiesListener();
                }
            });

            function updateGrandTotal() {
                let total = 0;
                document.querySelectorAll('input[name="subtotal[]"]').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });

                // Format ke Rupiah
                const formatted = total.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                });
                document.getElementById('grandTotal').textContent = formatted;
            }
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
            const tableRows = document.querySelectorAll('#fertilizerTable tbody tr');
            const filterBtn = document.getElementById('fertilizerFilterBtn');

            filterItems.forEach(item => {
                item.addEventListener('click', function() {
                    const type = this.dataset.type;

                    // Update dropdown button text
                    filterBtn.textContent = type === 'all' ? 'Jenis Pupuk' : type;

                    tableRows.forEach(row => {
                        const productName = row.querySelector('td').textContent.trim();

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
@endpush
