@extends('layouts.app')
@section('customcss')
<style>
        #charCount {
            position: absolute;
            bottom: 10px;
            right: 10px;
            color: #888;
        }
    </style>
@endsection
@section('content')
<form class="card" action="{{ url('admin/sms') }}" method="POST">
	@csrf
	<div class="card-header">
		<h6>Create new communication</h6>
	</div>
	<div class="card-body row">
		<div class="form-group mb-3 col-md-12">
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
			<textarea class="form-control" id="message" maxlength="200" name="message"></textarea>
			<div id="charCount">0 / 200</div>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary">Publish Communication</button>
	</div>
</form>
@endsection
@section('customjs')
 <script>
        document.addEventListener('DOMContentLoaded', function () {
            var textarea = document.getElementById('message');
            var charCount = document.getElementById('charCount');

            textarea.addEventListener('input', function () {
                var count = textarea.value.length;
                charCount.textContent = count+" / 200";
            });
        });
    </script>
@endsection