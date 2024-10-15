@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Members</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Members</li>
        </ol>
    </div>
</div>
@endsection
@section('content')
<div class="card">
	{{-- <div class="card-header"></div> --}}
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover dataTable">
				<thead>
					<th>Membership No.</th>
					<th>Name</th>
					<th>Type</th>
					<th>Contacts</th>
					<th>Status</th>
					<th>Actions</th>
				</thead>
				<tbody>
					@foreach($members as $member)
						<tr>
							<td>{{ $member->membership_number }}</td>
							<td>{{ $member->name }}</td>
							<td>{{ $member?->account_type?->name }}</td>
							<td>{{ $member->phone_no }}</td>
							<td>
								@if($member?->subscribed == 1)
									<span class="badge bg-success">Fully paid</span>
								@else
									<span class="badge bg-danger">Not-paid</span>
								@endif
							</td>
							<td>
								<a href="{{ url('admin/members/'.$member->id) }}" class="btn btn-sm btn-primary">Show</a>

								@if($member?->latestMembership?->status == "Pending")
									{{-- <a href="{{ url('admin/approve_membership/'.$member->id) }}" class="btn btn-warning btn-sm">Approve</a> --}}
									<a href="{{ url('admin/review_membership/'.$member->id) }}" class="btn btn-danger btn-sm">Review Application</a>
								@endif
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection