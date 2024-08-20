@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    @if($type == "cpd")
    <h4 class="mb-sm-0">CPDs</h4>
    @else
    <h4 class="mb-sm-0">Events</h4>
    @endif

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            @if($type == "cpd")
            <li class="breadcrumb-item"><a href="{{ url('admin/cpds') }}">CPDs</a></li>
            @else
			<li class="breadcrumb-item"><a href="{{ url('admin/events') }}">Events</a></li>
            @endif
            <li class="breadcrumb-item active">Create Reminder</li>
        </ol>
    </div>
</div>
@endsection
@section('content')
<form class="card" method="POST" action="{{ url('admin/send_reminder') }}">
	@csrf
	<input type="hidden" name="type" value="{{ $type }}">
	<div class="card-header">
		<h5>Create New Reminder</h5>
	</div>
	<div class="card-body row">
		<div class="form-group mb-3">
			<label>Subject</label>
			<input type="text" class="form-control" name="subject">
		</div>
		<div class="form-group mb-3">
			<label>Message</label>
			<textarea class="ckeditor" name="message"></textarea>
		</div>
		<div class="col-md-6">
			@if($type == "cpd")
				<div class="form-group">
					<label>CPD</label>
					<select class="form-control" name="cpd" required>
						<option value="" selected disabled>Please select a cpd</option>
						@foreach($cpds as $cpd)
							<option value="{{ $cpd->id }}">{{ $cpd->topic }}</option>
						@endforeach
					</select>
				</div>
			@else
				<div class="form-group">
					<label>Event</label>
					<select class="form-control" name="event" required>
						<option value="" selected disabled>Please select a event</option>
						@foreach($events as $cpd)
							<option value="{{ $cpd->id }}">{{ $cpd->name }}</option>
						@endforeach
					</select>
				</div>
			@endif
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Status</label>
				<select class="form-control" name="status">
					<option value="Pending">Pending</option>
					<option value="Attended">Attended</option>
				</select>
			</div>
		</div>
	</div>
	<div class="card-footer text-end">
		<button class="btn btn-info" type="submit">Send Reminder</button>
	</div>
</form>
@endsection