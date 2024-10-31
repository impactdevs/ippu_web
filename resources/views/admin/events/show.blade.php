@extends('layouts.app')
@section('breadcrumb')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Events</h4>

        <div class="page-title-right">
            <ol class="m-0 breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('admin/events') }}">Events</a></li>
                <li class="breadcrumb-item active">{{ $event->name }}</li>
            </ol>
        </div>

    </div>
@endsection
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class="">{{ $event->name }}</h4>
                <!-- Nav tabs -->
                <ul class="mb-3 nav nav-pills nav-justified bg-light" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab">
                            Details
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab">
                            Pending Confirmation ({{ $event->pending_confimation->count() }})
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-messages-1" role="tab">
                            Confirmed ({{ $event->confirmed->count() }})
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-settings-1" role="tab">
                            Attended ({{ $event->attended_event->count() }})
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="pill-justified-home-1" role="tabpanel">
                        <div class="row">
                            <div class="col-md-7">
                                {!! $event->details !!}
                                <div class="p-1 mb-2 bg-light">
                                    <div class="flex-row d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
                                            <span>{{ date('F j, Y, g:i a', strtotime($event->start_date)) }}</span>
                                        </div>
                                        <div>
                                            <h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
                                            <span>{{ date('F j, Y, g:i a', strtotime($event->end_date)) }}</span>
                                        </div>
                                        <div>
                                            <h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
                                            <span>{{ $event->member_rate ? number_format($event->member_rate) : 'Free' }}</span>
                                        </div>
                                        @if ($event->points)
                                            <div>
                                                <h6 class="text-warning font-weight-bold fw-medium">Points</h6>
                                                <span>{{ $event->points }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ asset('storage/attachments/' . $event->attachment_name) }}"
                                    class="btn btn-warning btn-sm" download>Download Resource</a>
                                <a href="{{ url('generate_qr/event/' . $event->id) }}"
                                    class="btn btn-danger btn-sm ms-4">Generate QR Code</a>
                            </div>
                            <div class="col-md-5">
                                <img class="card-img-top img-fluid image"
                                    src="{{ asset('storage/banners/' . $event->banner_name) }}" alt="{{ $event->name }}"
                                    onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">

                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="pill-justified-profile-1" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped dataTable">
                                <thead>
                                    <th>Name</th>
                                    <th>Contacts</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($event->pending_confimation as $attendence)
                                        <tr>
                                            <td>{{ $attendence?->user?->name }}</td>
                                            <td>{{ $attendence?->user?->phone_no }}</td>
                                            <td>{{ $attendence?->user?->email }}</td>
                                            <td>
                                                @can('approve event attendence')
                                                    <a href="{{ url('admin/events/attendence/' . $attendence->id . '/Confirmed') }}"
                                                        class="btn btn-sm btn-primary">
                                                        Book Attendence
                                                    </a>


                                                    <a href="{{ url('admin/events/attendence/' . $attendence->id . '/Attended') }}"
                                                        class="btn btn-sm btn-danger">
                                                        Confirm Attendence
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="pill-justified-messages-1" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped dataTable">
                                <thead>
                                    <th>Name</th>
                                    <th>Contacts</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($event->confirmed as $attendence)
                                        <tr>
                                            <td>{{ $attendence?->user?->name }}</td>
                                            <td>{{ $attendence?->user?->phone_no }}</td>
                                            <td>{{ $attendence?->user?->email }}</td>
                                            <td>
                                                @can('approve event attendence')
                                                    <a href="{{ url('admin/events/attendence/' . $attendence->id . '/Attended') }}"
                                                        class="btn btn-sm btn-primary">
                                                        Confirm Attendence
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="pill-justified-settings-1" role="tabpanel">

                        <form action="{{ route('events.bulkDownload') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <button type="submit" class="mb-3 btn btn-primary">Download Bulk Certificates</button>
                        </form>

                            <form action="{{ route('events.bulkEmail') }}" method="POST">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            <button type="submit" class="mb-3 btn btn-primary">Bulk Email Cerificates</button>
                        </form>
                        <!-- Add New Attendee Button -->
                        <div class="mb-3 d-flex justify-content-end">
                            <button id="addNewAttendeeBtn" class="btn btn-success btn-sm">
                                Add New Attendee
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped dataTable">
                                <thead>
                                    <th>Name</th>
                                    <th>Contacts</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                    @foreach ($event->attended_event as $attendence)
                                        <tr>
                                            <td>{{ $attendence?->user?->name }}</td>
                                            <td>{{ $attendence?->user?->phone_no }}</td>
                                            <td>{{ $attendence?->user?->email }}</td>
                                            <td>
                                                <a href="{{ url('admin/events/attendence-email/' . $event->id . '/' . optional($attendence->user)->id) }}"
                                                    class="mb-2 mr-2 btn btn-sm btn-primary">
                                                    Email Certificate
                                                </a>
                                                <a href="{{ url('admin/events/download_certificate/' . $event->id . '/' . $attendence->user->id) }}"
                                                    class="mb-2 btn btn-sm btn-warning">
                                                    Download Certificate
                                                </a>
                                                <!-- Edit Email Button -->
                                                <button class="mb-2 btn btn-sm btn-info edit-email-btn"
                                                    data-id="{{ $attendence->id }}"
                                                    data-email="{{ $attendence?->user?->email }}"
                                                    data-name="{{ $attendence?->user?->name }}">

                                                    Edit Details
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div><!-- end card-body -->
        </div>
    </div>
@endsection


@section('customjs')
    <script>
        $('#addNewAttendeeBtn').on('click', function() {
            Swal.fire({
                title: 'Add New Attendee',
                html: `
            <form id="addAttendeeForm">
                <div class="mb-3">
                    <label for="attendeeName" class="form-label">Attendee Name</label>
                    <input type="text" id="attendeeName" class="form-control" required placeholder="Attendee Name">
                </div>
                <div class="mb-3">
                    <label for="attendeeEmail" class="form-label">Attendee Email</label>
                    <input type="email" id="attendeeEmail" class="form-control" required placeholder="Attendee Email">
                </div>
                <div class="mb-3">
                    <label for="membershipNumber" class="form-label">Membership Number (Optional)</label>
                    <input type="text" id="membershipNumber" class="form-control" placeholder="Membership Number">
                </div>
            </form>
        `,
                showCancelButton: true,
                confirmButtonText: 'Register Attendee',
                preConfirm: function() {
                    var name = $('#attendeeName').val();
                    var email = $('#attendeeEmail').val();
                    var membershipNumber = $('#membershipNumber').val();

                    if (!name || !email) {
                        Swal.showValidationMessage('Please enter both name and email');
                        return false;
                    }

                    return {
                        name: name,
                        email: email,
                        membershipNumber: membershipNumber
                    };
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    var data = result.value;
                    // Send the data to the server to register the attendee
                    registerAttendee(data.name, data.email, data.membershipNumber);
                }
            });
        });

        function registerAttendee(name, email, membershipNumber) {
            // Use jQuery AJAX to send the data to the server
            $.ajax({
                url: '{{ route('events.attendence.store') }}',
                type: 'POST',
                data: {
                    event_id: {{ $event->id }},
                    name: name,
                    email: email,
                    membership_number: membershipNumber
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                },
                beforeSend: function() {
                    //show swal loading
                    Swal.fire({
                        title: 'Registering Attendee...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {

                    if (response.success) {
                        Swal.fire('Success', 'Attendee has been registered!', 'success').then(function() {
                            location.reload(); // Reload the page to see the changes
                        });
                    } else {
                        Swal.fire('Error', response.message || 'There was an error registering the attendee.',
                            'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to register attendee. Please try again later.', 'error');
                }
            });
        }
    </script>

<script>
    $(document).on('click', '.edit-email-btn', function() {
        var attendenceId = $(this).data('id');
        var currentEmail = $(this).data('email');
        var currentName = $(this).data('name'); // Retrieve the current name

        Swal.fire({
            title: 'Edit Attendee Details',
            html: `
            <form id="editEmailForm">
                <div class="mb-3">
                    <label for="attendeeName" class="form-label">Attendee Name</label>
                    <input type="text" id="attendeeName" class="form-control" required placeholder="New Name" value="${currentName}">
                </div>
                <div class="mb-3">
                    <label for="attendeeEmail" class="form-label">New Email</label>
                    <input type="email" id="attendeeEmail" class="form-control" required placeholder="New Email" value="${currentEmail}">
                </div>
            </form>
        `,
            showCancelButton: true,
            confirmButtonText: 'Update Details',
            preConfirm: function() {
                var newEmail = $('#attendeeEmail').val();
                var newName = $('#attendeeName').val(); // Get the new name

                if (!newEmail) {
                    Swal.showValidationMessage('Please enter a new email');
                    return false;
                }

                if (!newName) {
                    Swal.showValidationMessage('Please enter a new name');
                    return false;
                }

                return {
                    newEmail: newEmail,
                    newName: newName
                };
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                var data = result.value;
                // Send the data to the server to update the email and name
                updateAttendeeDetails(attendenceId, data.newEmail, data.newName);
            }
        });
    });

    function updateAttendeeDetails(attendenceId, newEmail, newName) {
        // Use jQuery AJAX to send the data to the server
        $.ajax({
            url: '{{ route('events.attendence.updateEmail') }}', // Ensure this route is correct
            type: 'POST',
            data: {
                attendence_id: attendenceId,
                email: newEmail,
                name: newName // Send the name along with the email
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Updating Details...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', 'Details have been updated!', 'success').then(function() {
                        location.reload(); // Reload the page to see the changes
                    });
                } else {
                    Swal.fire('Error', response.message || 'There was an error updating the details.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to update details. Please try again later.', 'error');
            }
        });
    }
</script>

@endsection
