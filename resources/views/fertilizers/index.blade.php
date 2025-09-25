<?php $page = 'fertilizer-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4 class="fw-bold">List Pupuk</h4>
                        <h6>Manage your products</h6>
                    </div>
                </div>
                <ul class="table-top-head">
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
                </ul>
                <div class="page-btn">
                    <a href="{{ route('fertilizers.create') }}" class="btn btn-primary"><i
                            class="ti ti-circle-plus me-1"></i>Tambah Pupuk</a>
                </div>
                <div class="page-btn import">
                    <a href="#" class="btn btn-secondary color" data-bs-toggle="modal" data-bs-target="#view-notes"><i
                            data-feather="download" class="me-1"></i>Import Product</a>
                </div>
            </div>

            <!-- /product list -->
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                    <div class="search-set">
                        <div class="search-input">
                            <span class="btn-searchset"><i class="ti ti-search fs-14 feather-search"></i></span>
                        </div>
                    </div>
                    <div class="d-flex table-dropdown my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                        <div class="dropdown me-2">
                            <a href="javascript:void(0);"
                                class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center"
                                data-bs-toggle="dropdown">
                                Category
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-end p-3">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Computers</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Electronics</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Shoe</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Electronics</a>
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <a href="javascript:void(0);"
                                class="dropdown-toggle btn btn-white btn-md d-inline-flex align-items-center"
                                data-bs-toggle="dropdown">
                                Brand
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-end p-3">
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Lenovo</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Beats</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Nike</a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="dropdown-item rounded-1">Apple</a>
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
                                    <th>Satuan</th>
                                    <th>Harga Subsidi</th>
                                    <th>Harga Eceran</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($fertilizers as $fertilizer)
                                    <tr>
                                        <td>{{ $fertilizer->fertilizer_name }}</td>
                                        <td>{{ $fertilizer->unit ?? '-' }}</td>
                                        <td>{{ $fertilizer->subsidized_price ? 'Rp ' . number_format($fertilizer->subsidized_price, 2, ',', '.') : '-' }}
                                        </td>
                                        <td>{{ $fertilizer->retail_price ? 'Rp ' . number_format($fertilizer->retail_price, 2, ',', '.') : '-' }}
                                        </td>
                                        <td>{{ $fertilizer->description ?? '-' }}</td>
                                        <td>
                                            @if ($fertilizer->is_subsidized)
                                                <span class="badge bg-success">Subsidi</span>
                                            @else
                                                <span class="badge bg-secondary">Non Subsidi</span>
                                            @endif
                                        </td>
                                        <td class="action-table-data">
                                            <div class="edit-delete-action">
                                                <a class="me-2 p-2 mb-0"
                                                    href="{{ route('fertilizers.show', $fertilizer->id) }}">
                                                    <i data-feather="eye" class="action-eye"></i>
                                                </a>
                                                <a class="me-2 p-2 mb-0" data-bs-toggle="modal"
                                                    data-bs-target="#edit-fertilizer-{{ $fertilizer->id }}">
                                                    <i data-feather="edit" class="feather-edit"></i>
                                                </a>
                                                <form action="{{ route('fertilizers.destroy', $fertilizer->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 mb-0 btn btn-link text-danger">
                                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Fertilizer Modal -->
                                    <div class="modal fade" id="edit-fertilizer-{{ $fertilizer->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="{{ route('fertilizers.update', $fertilizer->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Data Pupuk</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Pupuk</label>
                                                            <input type="text" name="fertilizer_name"
                                                                class="form-control"
                                                                value="{{ $fertilizer->fertilizer_name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Satuan</label>
                                                            <input type="text" name="unit" class="form-control"
                                                                value="{{ $fertilizer->unit }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Harga Subsidi</label>
                                                            <input type="number" step="0.01" name="subsidized_price"
                                                                class="form-control"
                                                                value="{{ $fertilizer->subsidized_price }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Harga Eceran</label>
                                                            <input type="number" step="0.01" name="retail_price"
                                                                class="form-control"
                                                                value="{{ $fertilizer->retail_price }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Deskripsi</label>
                                                            <textarea name="description" class="form-control">{{ $fertilizer->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Jenis Pupuk</label>
                                                            <select name="is_subsidized" class="form-select">
                                                                <option value="1"
                                                                    {{ $fertilizer->is_subsidized ? 'selected' : '' }}>
                                                                    Subsidi</option>
                                                                <option value="0"
                                                                    {{ !$fertilizer->is_subsidized ? 'selected' : '' }}>Non
                                                                    Subsidi</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary me-2"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Simpan Data
                                                            Pupuk</button>
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
        <div class="footer d-sm-flex align-items-center justify-content-between border-top bg-white p-3">
            <p class="mb-0 text-gray-9">2014 - 2025 &copy; DreamsPOS. All Right Reserved</p>
            <p>Designed &amp; Developed by <a href="javascript:void(0);" class="text-primary">Dreams</a></p>
        </div>
    </div>
@endsection
