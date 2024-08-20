@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Reports</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Points Reports</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<form class="row mb-3">
	<div class="col-md-4">
		<div class="form-group">
			<select class="form-control" name="year">
				<option value="2020" {{ (is_null(request('year'))?'': ((request('year') == '2020') ? 'selected' : ''))  }}>2020</option>
				<option value="2021" {{ (is_null(request('year'))?'': ((request('year') == 2021) ? 'selected' : ''))  }}>2021</option>
				<option value="2022" {{ (is_null(request('year'))?'': ((request('year') == 2022) ? 'selected' : ''))  }}>2022</option>
				<option value="2023" {{ (is_null(request('year'))?'': ((request('year') == 2023) ? 'selected' : ''))  }}>2023</option>
				<option value="2024" {{ (is_null(request('year'))?'': ((request('year') == 2024) ? 'selected' : ''))  }}>2024</option>
				<option value="2025" {{ (is_null(request('year'))?'': ((request('year') == 2025) ? 'selected' : ''))  }}>2025</option>
			</select>
		</div>
	</div>
	<div class="col-md-4">
		<input type="submit" class="btn btn-primary" name="" value="Search">
	</div>
</form>
<div class="card">
	<div class="card-header">
		<h5>Point Reports</h5>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered dataTable">
				<thead>
					<th>Name</th>
					<th>Address</th>
					<th>Contacts</th>
					<th>Points</th>
				</thead>
				<tbody>
					@foreach($members as $member)
						<tr>
							<td>{{ $member->name }}</td>
							<td>{{ $member->address }}</td>
							<td>{{ $member->phone_no.' '.$member->alt_phone_no }}</td>
							<td>{{ number_format($member->points_details_sum_points) }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection