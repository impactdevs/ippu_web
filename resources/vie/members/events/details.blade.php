@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-md-8 col-lg-8 col-xl-8">
		<div class="card">
			<div class="card-header">
				<h5 class="card-title">{{ $event->name }}</h5>
			</div>
			<div class="card-body">
				{!! $event->details !!}
			</div>
			<div class="card-footer bg-light">
				<div class="d-flex flex-row align-items-center justify-content-between">
					<div>
						<h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
						<span>{{ date('F j, Y, g:i a',strtotime($event->start_date)) }}</span>
					</div>
					<div>
						<h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
						<span>{{ date('F j, Y, g:i a',strtotime($event->end_date)) }}</span>
					</div>
					<div>
						<h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
						<span>{{ ($event->member_rate) ? ($event->member_rate) : 'Free'}}</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-4 col-xl-4">
	<!-- Simple card -->
	<div class="">
		<img class="card-img-top img-fluid image" src="{{ asset('storage/banners/'.$event->banner_name) }}" alt="{{ $event->name }}" onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">
		<div class="card-body">
			<div class="text-end mt-2">
				@if((($event->start_date >= date('Y-m-d')) || ($event->end_date <= date('Y-m-d'))) && is_null($event->attended))
				{{-- <a href="{{ url('attend_event/'.$event->id) }}" class="btn btn-primary">Attend</a> --}}
				<a href="#"  data-url="{{ url('attend_event/'.$event->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Attend Event')}}" class="btn btn-primary">Attend</a>
				@elseif(!is_null($event->attended))
				{{-- <span href="javascript:void(0)" class="btn btn-light btn-sm">
					Attended
				</span> --}}
				@if($event->attended->status != "Pending")
					<a href="{{ asset('storage/attachments/'.$event->attachment_name) }}" class="btn btn-warning btn-sm" download>Download Resource</a>
				@endif
				@endif
			</div>
		</div>
	</div><!-- end card -->
</div>
</div>
@endsection