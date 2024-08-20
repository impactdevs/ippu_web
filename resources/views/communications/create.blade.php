@extends('layouts.app')
@section('content')
<form class="card" action="{{ url('communications') }}" method="POST">
	@csrf
	<div class="card-header">
		<h6>Create new communication</h6>
	</div>
	<div class="card-body row">
		<div class="form-group mb-3 col-md-6">
			<label>Title</label>
			<input type="text" class="form-control" name="title">
		</div>
		<div class="form-group mb-3 col-md-6">
			<label>Target</label>
			<select name="target" class="form-control">
				<option value="*">All Members</option>
				@foreach($accountTypes as $accountType)
					<option value="{{ $accountType->id }}">{{ $accountType->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group mb-3">
			<label>Message</label>
			<textarea class="ckeditor" name="message"></textarea>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary">Publish Communication</button>
	</div>
</form>
@endsection