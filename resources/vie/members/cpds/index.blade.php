@extends('layouts.app')
@section('content')
<div class="row">
	@foreach($cpds as $event)
<div class="col-sm-6 col-xl-4">
	<!-- Simple card -->
	<div class="card">
		<img class="card-img-top img-fluid image" src="{{ asset('storage/banners/'.$event->banner) }}" alt="{{ $event->topic }}" onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">
		<div class="card-body">
			<h4 class="card-title mb-2">
				<a href="{{ url('cpd_details/'.$event->id) }}">{{ $event->topic }}</a>
			</h4>
			<div class="card-text">
				<div>
					<div class="bg-light p-2"> 
						<span class="text-primary ">{{ number_format($event->attendences->count()) }} Attendees</span>
						<span class="mx-1"> -</span>
						<span class="text-danger font-weight-bold fw-medium">{{ number_format($event->member_rate) }}</span>
						<span class="text-primary ml-1 font-weight-bold fw-medium">
							({{ date('F j, Y, g:i a',strtotime($event->start_date)).' - '.date('F j, Y, g:i a',strtotime($event->end_date)) }})
						</span>
					</div>
				</div>
			</div>
		{{-- 	<div class="text-end mt-2">
				@if((($event->start_date >= date('Y-m-d')) || ($event->end_date <= date('Y-m-d'))) && is_null($event->attended))
				<a href="{{ url('attend_event/'.$event->id) }}" class="btn btn-primary">Attend</a>
				@elseif(!is_null($event->attended))
				<span href="javascript:void(0)" class="btn btn-light btn-sm">
					Attended
				</span>
				@endif
			</div> --}}
		</div>
	</div><!-- end card -->
</div>
@endforeach
</div>
@endsection