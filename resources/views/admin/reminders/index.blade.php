@extends('layouts.app')
@section('content')
<div class="col-md-12 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5>Reminders Test</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped dataTable table-responsive table-hover">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reminders as $reminder)
                    <tr id="notification_{{ $reminder->id }}">
                        <td>
                            @if($reminder->member)
                            <div class="d-flex">
                                <img src="{{ asset('storage/profiles/'.$reminder->member->profile_pic) }}" onerror="this.onerror=null;this.src='{{ asset('assets/images/users/user-dummy-img.jpg') }}';" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-1">
                                    <a href="{{ url('admin/members/'.$reminder->member_id) }}">
                                        <h6 class="mt-0 mb-1 fs-13 fw-semibold">{{ $reminder->member->name }}</h6>
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="d-flex">
                                <img src="{{ asset('assets/images/users/user-dummy-img.jpg') }}" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-1">
                                    <h6 class="mt-0 mb-1 fs-13 fw-semibold text-muted">Unknown Member</h6>
                                </div>
                            </div>
                            @endif
                        </td>
                        <td>{{ $reminder->title }}</td>
                        <td>{{ $reminder->created_at->diffForHumans() }}</td>
                        <td>
                            <div class="form-check form-switch form-switch-success">
                                <input class="form-check-input read_notification" type="checkbox" role="switch" id="SwitchCheck3" value="{{ $reminder->id }}">
                                <label class="form-check-label" for="SwitchCheck3">Mark As Read</label>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('customjs')
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize the DataTable
        $('.dataTable').DataTable();

        // Handle notification read functionality
        $('.read_notification').change(function(){
            var id = $(this).val();
            if(this.checked) {
                $.ajax({
                    url: '{{ url('admin/read_notification') }}',
                    type: 'post',
                    data: 'id=' + id,
                    dataType: 'json',
                    success: function(data) {
                        $("#notification_" + id).slideUp();
                    }
                })
            }
        });
    });
</script>
@endsection
