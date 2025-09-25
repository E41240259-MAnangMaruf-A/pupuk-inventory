<?php $page = 'stock-adjustment'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Stock Adjustment</h4>
                        <h6>Manage your stock adjustment</h6>
                    </div>
                </div>
                <div class="page-btn">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-stock-adjustment">
                        <i class="ti ti-circle-plus me-1"></i>Add Adjustment
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
<th>Periode</th>
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
                                        <td>{{ \Carbon\Carbon::parse($allocation->period_start)->format('d M Y') }} -
                                            {{ \Carbon\Carbon::parse($allocation->period_end)->format('d M Y') }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $allocation->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($allocation->status) }}
                                            </span>
                                        </td>
                                        <td class="d-flex">
                                            <a href="#" class="me-2" data-bs-toggle="modal"
                                                data-bs-target="#edit-stock-{{ $allocation->id }}">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <form action="{{ route('fertilizers.stock-subsidy.store') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $allocation->id }}">
                                                <button type="submit" class="btn p-0"><i data-feather="trash-2"
                                                        class="feather-trash-2"></i></button>
                                            </form>
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
                                                                value="{{ $allocation->period_start->format('Y-m-d') }}"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Period End</label>
                                                            <input type="date" name="period_end" class="form-control"
                                                                value="{{ $allocation->period_end->format('Y-m-d') }}"
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
