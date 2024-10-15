@extends('layouts.app')
@section('content')
	<div class="card">
		<div class="card-header">
			{{ $communication->title }}
		</div>
		<div class="card-body">
			{!! $communication->message !!}
		</div>
		<div class="card-footer">
			Created by: {{ $communication->user->name }}
		</div>
	</div>
@endsection