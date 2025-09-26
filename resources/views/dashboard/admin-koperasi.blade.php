<?php $page = 'admin-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
	<div class="content">
		<div class="row">
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card dash-widget w-100">
					<div class="card-body d-flex align-items-center">
						<div class="dash-widgetimg">
							<span><img src="{{URL::asset('build/img/icons/dash1.svg')}}" alt="img"></span>
						</div>
						<div class="dash-widgetcontent">
							<h5 class="mb-1">$<span class="counters" data-count="307144.00">$307,144.00</span></h5>
							<p class="mb-0">Total Purchase Due</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card dash-widget dash1 w-100">
					<div class="card-body d-flex align-items-center">
						<div class="dash-widgetimg">
							<span><img src="{{URL::asset('build/img/icons/dash2.svg')}}" alt="img"></span>
						</div>
						<div class="dash-widgetcontent">
							<h5 class="mb-1">$<span class="counters" data-count="4385.00">$4,385.00</span></h5>
							<p class="mb-0">Total Sales Due</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card dash-widget dash2 w-100">
					<div class="card-body d-flex align-items-center">
						<div class="dash-widgetimg">
							<span><img src="{{URL::asset('build/img/icons/dash3.svg')}}" alt="img"></span>
						</div>
						<div class="dash-widgetcontent">
							<h5 class="mb-1">$<span class="counters" data-count="385656.50">$385,656.50</span></h5>
							<p class="mb-0">Total Sale Amount</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="card dash-widget dash3 w-100">
					<div class="card-body d-flex align-items-center">
						<div class="dash-widgetimg">
							<span><img src="{{URL::asset('build/img/icons/dash4.svg')}}" alt="img"></span>
						</div>
						<div class="dash-widgetcontent">
							<h5 class="mb-1">$<span class="counters" data-count="40000.00">$400.00</span></h5>
							<p class="mb-0">Total Expense Amount</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="dash-count bg-primary">
					<div class="dash-counts">
						<h4 class="mb-1">100</h4>
						<p class="text-white mb-0">Total Petani Terdaftar</p>
					</div>
					<div class="dash-imgs">
						<i data-feather="user"></i>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="dash-count das1 bg-cyan-900">
					<div class="dash-counts">
						<h4 class="mb-1">110</h4>
						<p class="text-white mb-0">Total Transaksi Penyaluran</p>
					</div>
					<div class="dash-imgs">
						<i data-feather="user-check"></i>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="dash-count das2 bg-dark">
					<div class="dash-counts">
						<h4 class="mb-1">150</h4>
						<p class="text-white mb-0">Total Nilai Subsidi Tersalurkan</p>
					</div>
					<div class="dash-imgs">
						<img src="{{URL::asset('build/img/icons/file-text-icon-01.svg')}}" class="img-fluid" alt="icon">
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-sm-6 col-12 d-flex">
				<div class="dash-count das3 bg-success">
					<div class="dash-counts">
						<h4 class="mb-1">170</h4>
						<p class="text-white mb-0">Sisa Kuota Subsidi</p>
					</div>
					<div class="dash-imgs">
						<i data-feather="file"></i>
					</div>
				</div>
			</div>
		</div>
		<!-- Button trigger modal -->

		<div class="row">
			<div class="col-xl-7 col-sm-12 col-12 d-flex">
				<div class="card flex-fill">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h5 class="card-title mb-0">Distribusi Pupuk</h5>
						<div class="graph-sets">
							<ul class="mb-0">
								<li>
									<span>Tersalurkan</span>
								</li>
								<li>
									<span>Tersisa</span>
								</li>
							</ul>
							<div class="dropdown">
								<a href="javascript:void(0);" class="dropdown-toggle btn btn-sm btn-white" data-bs-toggle="dropdown" aria-expanded="false">
									2025
								</a>
								<ul class="dropdown-menu p-3">
									<li>
										<a href="javascript:void(0);" class="dropdown-item">2025</a>
									</li>
									<li>
										<a href="javascript:void(0);" class="dropdown-item">2022</a>
									</li>
									<li>
										<a href="javascript:void(0);" class="dropdown-item">2021</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="card-body pb-0">
						<div id="sales_charts"></div>
					</div>
				</div>
			</div>

			<!-- Recent Products -->
			<div class="col-xl-5 col-sm-12 col-12 d-flex">
				<div class="card flex-fill default-cover mb-4">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h4 class="card-title mb-0">Riwayat transaksi terbaru</h4>
						<a href="product-list.html" class="fs-13 fw-medium text-decoration-underline">Lihat Semua</a>
					</div>
					<div class="card-body p-0">
						<div class="table-responsive dataview">
							<table class="table dashboard-recent-products">
								<thead class="thead-light">
									<tr>
										<th>#</th>
										<th>Nama Petani</th>
										<th>Jumlah</th>
										<th>Status Pembayaran</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Sugeng Santoso</td>
										<td>250 kg</td>
										<td>Lunas</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Wahyu Pratama</td>
										<td>150 kg</td>
										<td>Belum Bayar</td>
									</tr>
									<tr>
										<td>3</td>
										<td>Siti Aminah</td>
										<td>300 kg</td>
										<td>Lunas</td>
									</tr>
									<tr>
										<td>4</td>
										<td>Budi Hartono</td>
										<td>200 kg</td>
										<td>Menunggu Konfirmasi</td>
									</tr>
									<tr>
										<td>5</td>
										<td>Dewi Lestari</td>
										<td>100 kg</td>
										<td>Lunas</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- /Recent Products -->

		</div>

		<!-- Expired Products -->
		<div class="card">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h4 class="card-title">Stok Pupuk</h4>
				<div class="view-all-link">
					<a href="expired-products.html" class="fs-13 fw-medium text-decoration-underline">Lihat Semua</a>
				</div>
			</div>
			<div class="card-body p-0">
				<div class="table-responsive dataview">
					<table class="table dashboard-expired-products">
						<thead class="thead-light">
							<tr>
								<th class="no-sort">
									<label class="checkboxs">
										<input type="checkbox" id="select-all">
										<span class="checkmarks"></span>
									</label>
								</th>
								<th>Jenis Pupuk</th>
								<th>Stok Awal (kg)</th>
								<th>Masuk (kg)</th>
								<th>Keluar (kg)</th>
								<th>Sisa (kg)</th>
								<th class="no-sort">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<label class="checkboxs">
										<input type="checkbox">
										<span class="checkmarks"></span>
									</label>
								</td>
								<td>Urea</td>
								<td>1,000</td>
								<td>500</td>
								<td>600</td>
								<td>900</td>
								<td class="action-table-data">
									<div class="edit-delete-action">
										<a class="me-2 p-2" href="#">
											<i data-feather="edit" class="feather-edit"></i>
										</a>
										<a class="p-2" href="javascript:void(0);">
											<i data-feather="trash-2" class="feather-trash-2"></i>
										</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="checkboxs">
										<input type="checkbox">
										<span class="checkmarks"></span>
									</label>
								</td>
								<td>NPK</td>
								<td>800</td>
								<td>200</td>
								<td>400</td>
								<td>600</td>
								<td class="action-table-data">
									<div class="edit-delete-action">
										<a class="me-2 p-2" href="#">
											<i data-feather="edit" class="feather-edit"></i>
										</a>
										<a class="p-2" href="javascript:void(0);">
											<i data-feather="trash-2" class="feather-trash-2"></i>
										</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="checkboxs">
										<input type="checkbox">
										<span class="checkmarks"></span>
									</label>
								</td>
								<td>ZA</td>
								<td>600</td>
								<td>100</td>
								<td>250</td>
								<td>450</td>
								<td class="action-table-data">
									<div class="edit-delete-action">
										<a class="me-2 p-2" href="#">
											<i data-feather="edit" class="feather-edit"></i>
										</a>
										<a class="p-2" href="javascript:void(0);">
											<i data-feather="trash-2" class="feather-trash-2"></i>
										</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="checkboxs">
										<input type="checkbox">
										<span class="checkmarks"></span>
									</label>
								</td>
								<td>SP-36</td>
								<td>400</td>
								<td>150</td>
								<td>100</td>
								<td>450</td>
								<td class="action-table-data">
									<div class="edit-delete-action">
										<a class="me-2 p-2" href="#">
											<i data-feather="edit" class="feather-edit"></i>
										</a>
										<a class="p-2" href="javascript:void(0);">
											<i data-feather="trash-2" class="feather-trash-2"></i>
										</a>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label class="checkboxs">
										<input type="checkbox">
										<span class="checkmarks"></span>
									</label>
								</td>
								<td>Organik</td>
								<td>300</td>
								<td>50</td>
								<td>80</td>
								<td>270</td>
								<td class="action-table-data">
									<div class="edit-delete-action">
										<a class="me-2 p-2" href="#">
											<i data-feather="edit" class="feather-edit"></i>
										</a>
										<a class="p-2" href="javascript:void(0);">
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
		<!-- /Expired Products -->

	</div>

	<div class="copyright-footer d-flex align-items-center justify-content-between border-top bg-white gap-3 flex-wrap">
		<p class="fs-13 text-gray-9 mb-0">2014 - 2025 &copy; DreamsPOS. All Right Reserved</p>
		<p>Designed & Developed By <a href="javascript:void(0);" class="link-primary">Dreams</a></p>
	</div>
</div>
@endsection