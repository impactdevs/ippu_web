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
		<div class="row mb-3">
			<div class="col-md-3">
				<label for="tinTypeFilter">Filter by Member Type</label>
			    <select id="tinTypeFilter" class="form-control form-select">
			        <option value="">All</option>
			        @foreach($account_types as $account_type)
			        <option value="{{ $account_type->name }}">{{ $account_type->name }}</option>
			        @endforeach
			    </select>
			</div>
		</div>
		<div class="table-responsive mt-3">
			<table class="table table-striped table-hover" id="members_table">
				<thead>
					<th>Membership No.</th>
					<th>Name</th>
					<th>Type</th>
					<th>Organisation</th>
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
							<th>{{ $member?->organisation }}</th>
							<td>{{ $member->phone_no }}</td>
							<td>
								@if($member?->subscribed == 1)
									<span class="badge bg-success">Fully paid</span>
								@else
									<span class="badge bg-danger">Not-paid</span>
								@endif
							</td>
							<td>
								@can('members')
								<a href="{{ url('admin/members/'.$member->id) }}" class="btn btn-sm btn-primary">Show</a>
								@endcan
								@can('approve members')
								<div class="btn-group btn-group-sm">
			                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
			                            <ul class="dropdown-menu">
										@if($member?->latestMembership?->status == "Pending")
											{{-- <a href="{{ url('admin/approve_membership/'.$member->id) }}" class="btn btn-warning btn-sm">Approve</a> --}}				
			                                <li>
			                                	<a class="dropdown-item" href="{{ url('admin/review_membership/'.$member->id) }}">Review Application</a>
			                                </li>
										@endif
											<li>
				                                <button class="dropdown-item btn-danger btn-delete" delete-item-form="delete-member">Delete</button>
				                                <form id="delete-member" action="{{ route('delete-member',$member->id) }}" method="POST" style="display: none;" class="m-0 p-0">
			                                    	@csrf
			                                     	@method('DELETE')
			                                     	 {{-- <button type="submit" class="dropdown-item">@lang('Delete')</button> --}}
			                                    </form>
			                                </li>
			                            </ul>
			                        </div>
								@endcan
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
@section('customjs')
<script type="text/javascript">
	$(document).ready(function () {
        var table = $('#members_table').DataTable();

        $('#tinTypeFilter').on('change', function () {
                table.column(2).search($(this).val()).draw();
            });
        });
</script>
@endsection