@extends('layouts.app')
@section('content')
@if(sizeof($experiences) < 1)
<div class="text-center alert alert-danger">Please add Education Background</div>
@endif

<div>
	<div class="text-end mb-3">
		<button class="btn btn-primary" data-action="New" data-id="2323" data-title="Add New Background" ajax-load="true" data-url="{{ url('education') }}">Add Education Background</button>
	</div>

	<div class="row">
		@foreach($experiences as $experience)
		<div class="col-md-6 col-lg-6">
			<div class="cad">
				<div class="card ribbon-box border shadow-none">
					<div class="card-body">
						<div class="ribbon ribbon-primary round-shape">{{ $experience->title }}</div>

						<h5 class="fs-14 text-end">{{ date('M Y',strtotime($experience->start_date)) }} - {{ date('M Y',strtotime($experience->end_date)) }}</h5>

						<h5>{{ $experience->field }} {{ ($experience->points) ? "(".$experience->points.")" : '' }}</h5>
						<div class="ribbon-content mt-4 text-muted">
							{!! $experience->description !!}
						</div>
					</div>
					<div class="text-end card-footer">
						<a href="javascript:void(0);" data-action="Update" data-id="{{ $experience->id }}" data-title="Edit Background" ajax-load="true" data-url="{{ url('education') }}" class="text-primary mr-3">Edit</a>
						{{-- <a href="" class="text-danger ml-2">Delete</a> --}}
						<form action="{{url('education', $experience->id)}}" method="POST" id="form_delete_{{ $experience->id  }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <a href="javascript:void(0)" type="submit" onclick="$('#form_delete_{{ $experience->id  }}').submit()" class="text-danger ml-2">Delete</a>
                                    </form>
					</div>
				</div>
			</div>
			
		</div>
		
		@endforeach
	</div>
</div>
@endsection