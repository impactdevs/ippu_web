@extends('layouts.app')
@section('content')
<form class="card" action="{{ url('communications',$communication->id) }}" method="POST">
	@csrf
	<input type="hidden" name="_method" value="PUT">
	<div class="card-header">
		<h6>Update communication</h6>
	</div>
	<div class="card-body row">
		<div class="form-group mb-3 col-md-6">
			<label>Title</label>
			<input type="text" class="form-control" name="title">
		</div>
		<div class="form-group mb-3 col-md-6">
			<label>Target</label>
			<select name="target" class="form-control">
				<option value="*" {{ $communication->target == '*' ? 'selected' : ''}}>All Members</option>
				@foreach($accountTypes as $accountType)
					<option value="{{ $accountType->id }}" {{ $communication->target == $accountType->id ? 'selected' : ''}}>{{ $accountType->name }}</option>
				@endforeach
			</select>
		</div>
		<div class="form-group mb-3">
			<label>Message</label>
			<textarea class="ckeditor" name="message">{{ $communication->message }}</textarea>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary">Update Communication</button>
	</div>
</form>
@endsection