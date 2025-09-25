@if(Route::is(['fertilizers.stock-subsidy']))
		<!-- Add Adjustment -->
		<div class="modal fade" id="add-stock-adjustment">
			<div class="modal-dialog modal-dialog-centered stock-adjust-modal">
				<div class="modal-content">
					<div class="modal-header">
						<div class="page-title">
							<h4>Add Adjustment</h4>
						</div>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="{{url('stock-adjustment')}}">
						<div class="modal-body">
							<div class="search-form mb-3">
								<label class="form-label">Product<span class="text-danger ms-1">*</span></label>
								<div class="position-relative">
									<input type="text" class="form-control" placeholder="Search Product">
									<i data-feather="search" class="feather-search"></i>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<div class="mb-3">
										<label class="form-label">Warehouse<span class="text-danger ms-1">*</span></label>
										<select class="select">
											<option>Select</option>
											<option>Lavish Warehouse</option>
											<option>Quaint Warehouse</option>
											<option>Cool Warehouse</option>
											<option>Retail Supply Hub</option>
										</select>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="mb-3">
										<label class="form-label">Reference Number<span class="text-danger ms-1">*</span></label>
										<input type="text" class="form-control">
									</div>
								</div>
								<div class="col-lg-12">
									<div class="mb-3">
										<label class="form-label">Store<span class="text-danger ms-1">*</span></label>
										<select class="select">
											<option>Select</option>
											<option>Electro Mart</option>
											<option>Quantum Gadgets</option>
											<option>Prime Bazaar</option>
											<option>Gadget World</option>
										</select>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="mb-3">
										<label class="form-label">Responsible Person<span class="text-danger ms-1">*</span></label>
										<select class="select">
											<option>Select</option>
											<option>James Kirwin</option>
											<option>Francis Chang</option>
											<option>Gary Hennessy</option>
											<option>Eleanor Panek</option>
										</select>
									</div>
								</div>
							</div>
							
							<div class="col-lg-12">
								<div>
									<label class="form-label">Notes<span class="text-danger ms-1">*</span></label>
									<textarea class="form-control"></textarea>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Create Adjustment</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Add Adjustment -->

		<!-- Edit Adjustment -->
		<div class="modal fade" id="edit-stock-adjustment">
			<div class="modal-dialog modal-dialog-centered stock-adjust-modal">
				<div class="modal-content">
					<div class="modal-header">
						<div class="page-title">
							<h4>Edit Adjustment</h4>
						</div>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form action="{{url('stock-adjustment')}}">
					<div class="modal-body">
						<div class="mb-3 search-form">
							<label class="form-label">Product<span class="text-danger ms-1">*</span></label>
							<div class="position-relative">
								<input type="text" class="form-control" value="Nike Jordan">
								<i data-feather="search" class="feather-search"></i>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<div class="mb-3">
									<label class="form-label">Warehouse<span class="text-danger ms-1">*</span></label>
									<select class="select">
										<option>Select</option>
										<option selected>Lavish Warehouse</option>
										<option>Quaint Warehouse</option>
										<option>Cool Warehouse</option>
										<option>Retail Supply Hub</option>
									</select>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="mb-3">
									<label class="form-label">Reference Number<span class="text-danger ms-1">*</span></label>
									<input type="text" class="form-control" value="PT003">
								</div>
							</div>
							<div class="col-lg-12">
								<div class="p-3 border bg-light rounded mb-3">
									<div class="table-responsive">
										<table class="table">
											<thead>
												<tr>
													<th>Product</th>
													<th>SKU</th>
													<th>Category</th>
													<th>Qty</th>
													<th>Type</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="d-flex align-items-center">
															<a href="javascript:void(0);" class="avatar avatar-md me-2">
																<img src="{{URL::asset('build/img/products/stock-img-02.png')}}" alt="product">
															</a>
															<a href="javascript:void(0);">Nike Jordan</a>
														</div>												
													</td>
													<td>PT002</td>
													<td>Nike</td>
													<td>
														<div class="product-quantity border-0 bg-gray-transparent">
															<span class="quantity-btn"><i data-feather="minus-circle" class="feather-search"></i></span>
															<input type="text" class="quntity-input bg-transparent" value="2">
															<span class="quantity-btn">+<i data-feather="plus-circle" class="plus-circle"></i></span>
														</div>
													</td>
													<td>
														<select class="select">
															<option>Addition</option>
															<option>Addition</option>
															<option>Addition</option>
														</select>
													</td>
													<td>
														<div class="edit-delete-action d-flex align-items-center">
															<a class="p-2 border rounded d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete">
																<i data-feather="trash-2" class="feather-trash-2"></i>
															</a>
														</div>
														
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Store<span class="text-danger ms-1">*</span></label>
									<select class="select">
										<option>Select</option>
										<option selected>Electro Mart</option>
										<option>Quantum Gadgets</option>
										<option>Prime Bazaar</option>
										<option>Gadget World</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Responsible Person<span class="text-danger ms-1">*</span></label>
									<select class="select">
										<option>Select</option>
										<option selected>James Kirwin</option>
										<option>Francis Chang</option>
										<option>Gary Hennessy</option>
										<option>Eleanor Panek</option>
									</select>
								</div>
							</div>
							<div class="col-lg-12">
								<div class="mb-3">
									<label class="form-label">Notes<span class="text-danger ms-1">*</span></label>
									<textarea class="form-control">The Jordan brand is owned by Nike (owned by the Knight family), as, at the time, the company was building its strategy to work with athletes to launch shows that could inspire consumers.Although Jordan preferred Converse and Adidas, they simply could not match the offer Nike made. </textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Save Changes</button>
					</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Edit Adjustment -->

		<!-- View Notes -->
		<div class="modal fade" id="view-notes">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<div class="page-title">
							<h4>Notes</h4>
						</div>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p>The Jordan brand is owned by Nike (owned by the Knight family), as, at the time, the company was building its strategy to work with athletes to launch shows that could inspire consumers.Although Jordan preferred Converse and Adidas, they simply could not match the offer Nike made. Jordan also signed with Nike because he loved the way they wanted to market him with the banned colored shoes. Nike promised to cover the fine Jordan would receive from the NBA.</p>
					</div>
				</div>
			</div>
		</div>
		<!-- /View Notes -->

		<!-- Delete -->
		<div class="modal fade modal-default" id="delete">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-body p-0">
						<div class="success-wrap text-center">
							<form action="{{url('stock-adjustment')}}">
								<div class="icon-success bg-danger-transparent text-danger mb-2">
									<i class="ti ti-trash"></i>
								</div>
								<h3 class="mb-2">Delete Stock Adjustment</h3>
								<p class="fs-16 mb-3">Are you sure you want to delete stock adjustment?</p>
								<div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
									<button type="button" class="btn btn-md btn-secondary" data-bs-dismiss="modal">No, Cancel</button>
									<button type="submit" class="btn btn-md btn-primary">Yes, Delete</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Delete -->
@endif


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
                                        <div class="col-sm-6 col-12">
                                            <div class="input-blocks">
                                                <label>Supplier</label>
                                                <select class="select">
                                                    <option>Pilih Supplier</option>
                                                    <option>Nama Supplier</option>
                                                </select>
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
                                        <div class="input-blocks summer-description-box">
                                            <label>Cattatan</label>
                                            <div id="summernote"></div>
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
