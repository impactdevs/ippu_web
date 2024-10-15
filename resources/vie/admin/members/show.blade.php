@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<h4 class="mb-sm-0">Member Details</h4>
	<div class="page-title-right">
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
			<li class="breadcrumb-item"><a href="{{ url('admin/members') }}">Members</a></li>
			<li class="breadcrumb-item active">{{ $member->name }}</li>
		</ol>
	</div>
</div>
@endsection
@section('content')
<div class="row">
	<div class="col-xl-4">
		<div class="card">
			<div class="card-header bg-primary">
				<h5 class="text-white">Personal Information</h5>
			</div>
			<div class="card-body">
				<div class="mb-3">
					<h6 class="text-bold-700">Name</h6>
					<div>{{ $member->name }}</div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Membership Number</h6>
					<div>{{ ($member->membership_number) ? $member->membership_number : 'Not Provided'}}</div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Contacts</h6>
					<div>{{ $member->phone_no }} / {{ $member->alt_phone_no }}</div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Email</h6>
					<div>{{ $member->email }}</div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Type</h6>
					<div>{{ $member?->account_type?->name }}</div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Since</h6>
					<div>{{ date('d M, y',strtotime($member->created_at)) }} <span class="badge bg-secondary">{{ $member->status }}</span></div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Status</h6>
					<div>
						@if($member?->latestMembership?->status == "Approved")
									<span class="badge bg-success">Subscribed</span>
								@endif
					</div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">NOK Name</h6>
					<div>{{ ($member->nok_name)?:"(blank)" }}</div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">NOK Contacts</h6>
					<div>{{ ($member->nok_phone_no)?:"(blank)" }} </div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">NOK Email</h6>
					<div>{{ ($member->nok_address)?:"(blank)" }}</div>
				</div>
			</div>
			<div class="card-footer text-end">

				@if($member->status == "Inactive")
				<a href="javascript:void(0);" class="btn btn-primary" title="{{__('Change Account Status')}}" data-bs-toggle="tooltip" data-url="{{ url('admin/change_member_status/'.$member->id) }}" data-ajax-popup="true" data-size="lg">Activate Account</a>
				@elseif($member->status == "Active")
				<a href="javascript:void(0);" class="btn btn-danger" title="{{__('Change Account Status')}}" data-bs-toggle="tooltip" data-url="{{ url('admin/change_member_status/'.$member->id) }}" data-ajax-popup="true" data-size="lg">Suspend Account</a>
				@else
				<a href="javascript:void(0);" class="btn btn-primary" title="{{__('Change Account Status')}}" data-bs-toggle="tooltip" data-url="{{ url('admin/change_member_status/'.$member->id) }}" data-ajax-popup="true" data-size="lg">Unsuspend Account</a> 
				@endif
			</div>
		</div>
	</div>
	<div class="col-xl-8">
		<div class="card">
			<div class="card-body">
				<ul class="nav nav-tabs nav-justified mb-3" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-bs-toggle="tab" href="#tab-cps" role="tab" aria-selected="false">
							CPDs
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#tab-events" role="tab" aria-selected="false">
							Events
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#tab-education" role="tab" aria-selected="false">
							Education
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#tab-employment" role="tab" aria-selected="true">
							Employment
						</a>
					</li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content  text-muted">
					<div class="tab-pane active" id="tab-cps" role="tabpanel">
						<h6>CPDs</h6>
						<div class="table-responsive">
							<table class="table table-striped dataTable">
								<thead>
									<th>Name</th>
									<th>Points</th>
									<th>Duration</th>
									<th>Type</th>
									<th>Date</th>
								</thead>
								<tbody>
									@foreach($member->cpd_attendences as $attendence)
										<tr>
											<td>{{ $attendence?->cpd?->topic }}</td>
											<td>{{ $attendence?->cpd?->points }}</td>
											<td>{{ date('d M, Y',strtotime($attendence?->cpd?->start_date)).' - '. date('d M, Y',strtotime($attendence?->cpd?->end_date))}}</td>
											<td>{{ $attendence?->cpd?->type }}</td>
											<td>{{ date('d M, Y',strtotime($attendence->created_at)) }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane" id="tab-events" role="tabpanel">
						<h6>Events</h6>
						<div class="table-responsive">
							<table class="table table-striped dataTable">
								<thead>
									<th>Name</th>
									<th>Duration</th>
									<th>Date</th>
								</thead>
								<tbody>
									@foreach($member->event_attendences as $attendence)
										<tr>
											<td>{{ $attendence?->event?->name }}</td>
											<td>{{ date('d M, Y',strtotime($attendence?->event?->start_date)).' - '. date('d M, Y',strtotime($attendence?->event?->end_date))}}</td>
											<td>{{ date('d M, Y',strtotime($attendence->created_at)) }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane" id="tab-education" role="tabpanel">
						<h6>Education</h6>
						@foreach($member->education as $educ)
							<div class="mb-4">
								<h5>{{ $educ->title }}</h5>
								<h6>{{ $educ->field }}</h6>
								<div>
									{!! $educ->description !!}
								</div>
								<div class="text-end">
									{{ date('d M, y',strtotime($educ->start_date)) }} - {{ date('d M, y',strtotime($educ->end_date)) }}
								</div>
							</div>
						@endforeach
					</div>
					<div class="tab-pane" id="tab-employment" role="tabpanel">
						<h6>Employment Experience</h6>
						@foreach($member->employment as $educ)
							<div class="mb-4">
								<h5>{{ $educ->title }}</h5>
								<h6>{{ $educ->position }}</h6>
								<div>
									{!! $educ->description !!}
								</div>
								<div class="text-end">
									{{ date('d M, y',strtotime($educ->start_date)) }} - {{ date('d M, y',strtotime($educ->end_date)) }}
								</div>
							</div>
						@endforeach
					</div>
				</div>
			</div><!-- end card-body -->
		</div>
	</div>
</div>
@endsection