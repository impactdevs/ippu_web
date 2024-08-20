@extends('layouts.app')
@section('content')
<div class="col-md-6 mx-auto">
	<div class="card">
		<div class="card-header">
			<h5>Reminders</h5>
		</div>
		<div class="card-body">
			@foreach($reminders as $reminder)
				<div id="notification_{{ $reminder->id }}">
					<div class="text-reset notification-item d-block dropdown-item">
                                  <div class="d-flex">
                                            <img src="{{ asset('storage/profiles/'.$reminder?->member?->profile_pic) }}" onerror="this.onerror=null;this.src='{{ asset('assets/images/users/user-dummy-img.jpg') }}';" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                            <div class="flex-1">
                                                <a href="{{ url('admin/members/'.$reminder->member_id) }}" class="stretched-link">
                                                    <h6 class="mt-0 mb-1 fs-13 fw-semibold">{{ $reminder?->member?->name }}</h6>
                                                </a>
                                                <div class="fs-13 text-muted">
                                                    <p class="mb-1">{{ $reminder->title }}</p>
                                                </div>
                                                <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                    <span><i class="mdi mdi-clock-outline"></i> {{ $reminder->created_at->diffForHumans() }} ago</span>
                                                </p>
                                            </div>
                                            <div class="px-2 fs-15">
                                                <div class="form-check form-switch form-switch-success">
												    <input class="form-check-input read_notification" type="checkbox" role="switch" id="SwitchCheck3" value="{{ $reminder->id }}">
												    <label class="form-check-label" for="SwitchCheck3">Mark As Read</label>
												</div>
                                            </div> 
                                        </div>
                                    </div>
				</div>
			@endforeach
		</div>
	</div>
</div>
@endsection
@section('customjs')
<script type="text/javascript">
	$(document).ready(function(){
		 $('.read_notification').change(function(){
		 	var id = $(this).val();
	        if(this.checked) {
	            $.ajax({
	            	url: '{{ url('admin/read_notification') }}',
	            	type: 'post',
	            	data:'id='+id,
	            	dataType: 'json',

	            	success: function(data){
	            		$("#notification_"+id).slideUp(); 
	            	}
	            })
	        }
	    });
	})
</script>
@endsection