@extends('layouts.app')
@section('content')
<div class="text-end mb-3">
	@if(\Auth::user()->user_type == "Admin")
	<a href="{{ url('communications/create') }}" class="btn btn-primary btn-sm">Create New Communication</a>
	@endif
</div>
<div class="row">
	@foreach($communications as $communication)
	<div class=" col-md-6">
		<div class="card">
			<div class="card-header">
				<a href="{{ url('communications/'.$communication->id) }}">{{ $communication->title }}</a>
			</div>
			<div class="card-body">
				<div class="text-truncate-two-lines mb-3">
					{!! $communication->message !!}
				</div>
			</div>
			@if($communication->user_id == \Auth::user()->id)
			<div class="card-footer text-end">
				<a href="{{ url('communications/'.$communication->id.'/edit') }}">Edit</a>

				<form action="{{route('communications.destroy', $communication->id)}}" method="POST" style="display: inline;" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">@lang('Delete')</button>
                                    </form>
			</div>
			@endif
		</div>
	</div>
	@endforeach
</div>
@endsection