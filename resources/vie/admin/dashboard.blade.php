@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xl-3 col-md-6">
		<!-- card -->
		<div class="card card-animate">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1 overflow-hidden">
						<p class="text-uppercase fw-medium text-muted text-truncate mb-0">Members</p>
					</div>
					{{-- <div class="flex-shrink-0">
						<h5 class="text-success fs-14 mb-0">
							<i class="ri-arrow-right-up-line fs-13 align-middle"></i> +29.08 %
						</h5>
					</div> --}}
				</div>
				<div class="d-flex align-items-end justify-content-between mt-4">
					<div>
						<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $members_count }}">0</span> </h4>
						<a href="{{ url('admin/members') }}" class="text-decoration-underline">See All</a>
					</div>
					<div class="avatar-sm flex-shrink-0">
						<span class="avatar-title bg-soft-warning rounded fs-3">
							<i class="bx bx-user-circle text-warning"></i>
						</span>
					</div>
				</div>
			</div><!-- end card body -->
		</div><!-- end card -->
	</div><!-- end col -->

	<div class="col-xl-3 col-md-6">
		<!-- card -->
		<div class="card card-animate">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1 overflow-hidden">
						<p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Invoices</p>
					</div>
					<div class="flex-shrink-0">
						{{-- <h5 class="text-success fs-14 mb-0">
							<i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 %
						</h5> --}}
					</div>
				</div>
				<div class="d-flex align-items-end justify-content-between mt-4">
					<div>
						<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $invoices_count }}">0</span> </h4>
						<a href="{{ url('admin/invoices') }}" class="text-decoration-underline">View Invoices</a>
					</div>
					<div class="avatar-sm flex-shrink-0">
						<span class="avatar-title bg-soft-success rounded fs-3">
							<i class="bx bx-dollar-circle text-success"></i>
						</span>
					</div>
				</div>
			</div><!-- end card body -->
		</div><!-- end card -->
	</div><!-- end col -->

	<div class="col-xl-3 col-md-6">
		<!-- card -->
		<div class="card card-animate">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1 overflow-hidden">
						<p class="text-uppercase fw-medium text-muted text-truncate mb-0">CPDs</p>
					</div>
					<div class="flex-shrink-0">
						{{-- <h5 class="text-danger fs-14 mb-0">
							<i class="ri-arrow-right-down-line fs-13 align-middle"></i> -3.57 %
						</h5> --}}
					</div>
				</div>
				<div class="d-flex align-items-end justify-content-between mt-4">
					<div>
						<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $cpds_count }}">0</span></h4>
						<a href="{{ url('admin/cpds') }}" class="text-decoration-underline">View all CPDs</a>
					</div>
					<div class="avatar-sm flex-shrink-0">
						<span class="avatar-title bg-soft-info rounded fs-3">
							<i class="bx bx-shopping-bag text-info"></i>
						</span>
					</div>
				</div>
			</div><!-- end card body -->
		</div><!-- end card -->
	</div><!-- end col -->

	<div class="col-xl-3 col-md-6">
		<!-- card -->
		<div class="card card-animate">
			<div class="card-body">
				<div class="d-flex align-items-center">
					<div class="flex-grow-1 overflow-hidden">
						<p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Events</p>
					</div>
					<div class="flex-shrink-0">
						{{-- <h5 class="text-muted fs-14 mb-0">
							+0.00 %
						</h5> --}}
					</div>
				</div>
				<div class="d-flex align-items-end justify-content-between mt-4">
					<div>
						<h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $events_count }}">0</span></h4>
						<a href="{{ url('admin/events') }}" class="text-decoration-underline">See Events</a>
					</div>
					<div class="avatar-sm flex-shrink-0">
						<span class="avatar-title bg-soft-primary rounded fs-3">
							<i class="bx bx-wallet text-primary"></i>
						</span>
					</div>
				</div>
			</div><!-- end card body -->
		</div><!-- end card -->
	</div><!-- end col -->
</div>
<div class="row">
	<div class="col-xl-4">
		<div class="card card-height-100">
			<div class="card-header align-items-center d-flex">
				<h4 class="card-title mb-0 flex-grow-1">Users Summary</h4>
				{{-- <div class="flex-shrink-0">
					<div class="dropdown card-header-dropdown">
						<a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="#">Download Report</a>
							<a class="dropdown-item" href="#">Export</a>
							<a class="dropdown-item" href="#">Import</a>
						</div>
					</div>
				</div> --}}
			</div><!-- end card header -->

			<div class="card-body">
				<div id="store-visits-source" data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger", "--vz-info"]' class="apex-charts" dir="ltr"></div>
			</div>
		</div> <!-- .card-->
	</div>
	<div class="col-xl-8">
		<div class="card">
			<div class="card-header align-items-center d-flex">
				<h4 class="card-title mb-0 flex-grow-1">Recent Invoices</h4>
				<div class="flex-shrink-0">
					<button type="button" class="btn btn-soft-info btn-sm">
						<i class="ri-file-list-3-line align-middle"></i> Generate Report
					</button>
				</div>
			</div><!-- end card header -->

			<div class="card-body">
				<div class="table-responsive table-card">
					<table class="table table-borderless table-centered align-middle table-nowrap mb-0">
						<thead class="text-muted table-light">
							<tr>
								<th scope="col">Invoice ID</th>
								<th scope="col">Customer</th>
								<th scope="col">Category</th>
								{{-- <th scope="col">Product</th> --}}
								<th scope="col">Amount</th>
								<th scope="col">Invoice Date</th>
								<th scope="col">Due Date</th>
							</tr>
						</thead>
						<tbody>
							@foreach($invoices as $invoice)
							<tr>
								<td>
									<a href="javascript:void(0);" class="fw-medium link-primary">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>
								</td>
								<td>
									<div class="d-flex align-items-center">
										{{-- <div class="flex-shrink-0 me-2">
											<img src="assets/images/users/avatar-1.jpg" alt="" class="avatar-xs rounded-circle" />
										</div> --}}
										<div class="flex-grow-1">{{ $invoice->customer->name }}</div>
									</div>
								</td>
								<td>
									<span class="badge badge-soft-success">{{ $invoice->category->name }}</span>
								</td>
								{{-- <td>Clothes</td> --}}
								<td>
									<span class="text-success">{{ number_format($invoice->getTotal()) }}</span>
								</td>
								<td>{{ date('d M, Y',strtotime($invoice->issue_date)) }}</td>
								
								<td>{{ date('d M, Y',strtotime($invoice->due_date)) }}</h5>
								</td>
							</tr>
							@endforeach
						</tbody><!-- end tbody -->
					</table><!-- end table -->
				</div>
			</div>
		</div> <!-- .card-->
	</div>
</div>
@endsection
@section('customjs')
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script>
	var options = {
		series: {{ $users_chart_series }},
		labels: {!! $users_chart_labels !!},
		chart: {
			height: 333,
			type: "donut"
		},
		legend: {
			position: "bottom"
		},
		stroke: {
			show: !1
		},
		dataLabels: {
			dropShadow: {
				enabled: !1
			}
		}
	};

	var chart = new ApexCharts(document.querySelector("#store-visits-source"), options);
	chart.render();
</script>
@endsection