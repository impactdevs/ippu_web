@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Reports</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Events Report</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<form class="row mb-3">
	<div class="col-md-8 row">
		<div class="form-group col-md-6">
			<select class="form-control" name="cpd">
				<option value="*" {{ (is_null(request('cpd'))?'': ((request('cpd') == '*') ? 'selected' : ''))  }}>All Events</option>
				@foreach($cpds as $cpd)
					<option value="{{ $cpd->id }}" {{ (is_null(request('cpd'))?'': ((request('cpd') == $cpd->id) ? 'selected' : ''))  }}>{{ $cpd->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group col-md-6">
			<select class="form-control" name="status">
				<option value="">Status</option>
				<option value="Attended" {{ (is_null(request('status'))?'': ((request('status') == "Attended") ? 'selected' : ''))  }}>Attended</option>
				<option value="Pending" {{ (is_null(request('status'))?'': ((request('status') == "Pending") ? 'selected' : ''))  }}>Pending</option>
			</select>
		</div>

	</div>
	<div class="col-md-4">
		<input type="submit" class="btn btn-primary" name="" value="Search">
	</div>
</form>
<div class="card">
	<div class="card-header">
		<h5>Event Reports</h5>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered dataTable">
				<thead>
					<th>Event</th>
					<th>Member</th>
					<th>Status</th>
					<th>Payment</th>
				</thead>
				<tbody>
					@foreach($attendences as $attendence)
						<tr>
							<td>{{ $attendence?->event?->name }}</td>
							<td>{{ $attendence?->user?->name }}</td>
							<td>{{ $attendence?->status }}</td>
							<td>{{ number_format($attendence?->cpd_payment?->amount) }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection