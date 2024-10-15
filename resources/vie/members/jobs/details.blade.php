@extends('layouts.app')
@section('content')
<div class="card">
				<div class="card-header d-flex flex-row align-items-center justify-content-between">
					<h5>{{ $job->title }}</h5>
					{{-- <a href="{{ url('jobs/'.$job->id) }}" class="badge bg-success">View</a> --}}
				</div>
				<div class="card-body">
					{!! $job->description !!}
				</div>
				<div class="card-footer">
					Deadline: {{ date('d M, Y',strtotime($job->deadline)) }}
				</div>
			</div>
@endsection