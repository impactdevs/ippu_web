@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Reports</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Member Reports</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<form class="row mb-3">
	<div class="col-md-4">
		<div class="form-group">
			<select class="form-control" name="type">
				<option value="*" {{ (is_null(request('type'))?'': ((request('type') == '*') ? 'selected' : ''))  }}>All Members</option>
				<option value="1" {{ (is_null(request('type'))?'': ((request('type') == 1) ? 'selected' : ''))  }}>Subscribed</option>
				<option value="2" {{ (is_null(request('type'))?'': ((request('type') == 2) ? 'selected' : ''))  }}>Not Subscribed</option>
			</select>
		</div>
	</div>
	<div class="col-md-4">
		<input type="submit" class="btn btn-primary" name="" value="Search">
	</div>
</form>
<div class="card">
	<div class="card-header">
		<h5>Members Reports</h5>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered dataTable">
				<thead>
					<th>Name</th>
					<th>Address</th>
					<th>Contacts</th>
					<th>Subscribed</th>
				</thead>
				<tbody>
					@foreach($members as $member)
						<tr>
							<td>{{ $member->name }}</td>
							<td>{{ $member->address }}</td>
							<td>{{ $member->phone_no.' '.$member->alt_phone_no }}</td>
							<td>{{ ($member->subscribed) ? 'Subscribed' : 'Not Subscribed' }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection