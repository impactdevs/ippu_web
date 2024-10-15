@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Reports</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">CPDs Report</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<div class="card">
	<div class="card-header">
		<h5>Account Types</h5>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered dataTable">
				<thead>
					<th>Account Type</th>
					<th>Members Count</th>
				</thead>
				<tbody>
					@foreach($account_types as $account_type)
						<tr>
							<td>{{ $account_type->name }}</td>
							<td>{{ $account_type->users_count }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection