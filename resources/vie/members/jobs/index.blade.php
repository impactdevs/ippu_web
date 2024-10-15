@extends('layouts.app')
@section('content')
<div class="row">
	@foreach($jobs as $job)
		<div class="col-md-6">
			<div class="card">
				<div class="card-header d-flex flex-row align-items-center justify-content-between">
					<h5>{{ $job->title }}</h5>
					<a href="{{ url('jobs/'.$job->id) }}" class="badge bg-success">View</a>
				</div>
				<div class="card-body text-truncate-two-lines">
					{!! $job->description !!}
				</div>
				<div class="card-footer">
					Deadline: {{ date('d M, Y',strtotime($job->deadline)) }}
				</div>
			</div>
		</div>
	@endforeach
</div>
@endsection