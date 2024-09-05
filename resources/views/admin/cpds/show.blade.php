@extends('layouts.app')

@section('breadcrumb')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">CPDs</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ url('admin/cpds') }}">CPDs</a></li>
                <li class="breadcrumb-item active">{{ $cpd->topic }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <p class="text-muted">{{ $cpd->topic }}</p>
            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-justified mb-3 bg-light" role="tablist">
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab">Details</a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab">
                        Pending Confirmation ({{ $cpd?->pending_confimation?->count() }})
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-messages-1" role="tab">
                        Confirmed ({{ $cpd?->confirmed?->count() }})
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-settings-1" role="tab">
                        Attended ({{ $cpd?->attended_event?->count() }})
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content text-muted">
                <div class="tab-pane active" id="pill-justified-home-1" role="tabpanel">
                    <div class="row">
                        <div class="col-md-7">
                            {!! $cpd->content !!}
                            <div class="p-1 mb-2 bg-light">
                                <div class="mb-3">{{ $cpd->target_group }}</div>
                                <div class="mb-3 d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">Location</h6>
                                        <span>{{ $cpd->location }}</span>
                                    </div>
                                    @if ($cpd->points)
                                        <div>
                                            <h6 class="text-warning font-weight-bold fw-medium">Points</h6>
                                            <span>{{ number_format($cpd->points) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
                                        <span>{{ date('F j, Y, g:i a', strtotime($cpd->start_date)) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
                                        <span>{{ date('F j, Y, g:i a', strtotime($cpd->end_date)) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
                                        <span>{{ $cpd->member_rate ? number_format($cpd->member_rate) : 'Free' }}</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ asset('storage/attachments/' . $cpd->resource) }}" class="btn btn-warning btn-sm"
                                download>Download Resource</a>
                            <a href="{{ url('generate_qr/cpd/' . $cpd->id) }}" class="btn btn-danger btn-sm ms-4">Generate QR
                                Code</a>
                        </div>
                        <div class="col-md-5">
                            <img class="card-img-top img-fluid image" src="{{ asset('storage/banners/' . $cpd->banner) }}"
                                alt="{{ $cpd->topic }}"
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
                                @foreach ($cpd->pending_confimation as $attendence)
                                    <tr>
                                        <td>{{ $attendence->user->name }}</td>
                                        <td>{{ $attendence->user->phone_no }}</td>
                                        <td>{{ $attendence->user->email }}</td>
                                        <td>
                                            @can('approve CPD attendence')
                                                <a href="{{ url('admin/cpds/attendence/' . $attendence->id . '/Confirmed') }}"
                                                    class="btn btn-sm btn-primary">
                                                    Book Attendance
                                                </a>
                                                <a href="{{ url('admin/cpds/attendence/' . $attendence->id . '/Attended') }}"
                                                    class="btn btn-sm btn-danger">
                                                    Confirm Attendance
                                                </a>
                                                @if ($attendence->payment_proof)
                                                    <a href="javascript:void(0);" class="btn-sm btn btn-warning"
                                                        data-url="{{ url('admin/view_payment_proof/' . $attendence->payment_proof) }}"
                                                        data-ajax-popup="true" data-bs-toggle="tooltip"
                                                        title="{{ __('Payment Proof') }}" class="btn btn-primary"
                                                        data-size="lg">View Payment Proof</a>
                                                @endif
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
                                @foreach ($cpd->confirmed as $attendence)
                                    <tr>
                                        <td>{{ $attendence->user->name }}</td>
                                        <td>{{ $attendence->user->phone_no }}</td>
                                        <td>{{ $attendence->user->email }}</td>
                                        <td>
                                            @can('approve CPD attendence')
                                                <a href="{{ url('admin/cpds/attendence/' . $attendence->id . '/Attended') }}"
                                                    class="btn btn-sm btn-primary">
                                                    Confirm Attendance
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
                 <!-- Add New Attendee Button -->
                        <div class="d-flex justify-content-end mb-3">
                            <button id="addNewAttendeeBtn" class="btn btn-success btn-sm">
                                Add New Attendee
                            </button>
                        </div>

                    <div class="table-responsive">
                        
                        <form action="{{ route('cpds.bulkDownload') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cpd_id" value="{{ $cpd->id }}">
                            <input type="hidden" name="attendees[]" value="user1">
                            <input type="hidden" name="attendees[]" value="user2">
                            <!-- Add more hidden inputs for other user IDs as needed -->
                            <button type="submit" class="btn btn-primary mb-3">Download Bulk Certificates</button>
                        </form>

                        <table class="table table-striped dataTable">
                            <thead>
                                <th>
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>Name</th>
                                <th>Contacts</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($cpd->attended_event as $attendence)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="attendees[]"
                                                value="{{ $attendence->user->id }}">
                                        </td>
                                        <td>{{ $attendence->user->name }}</td>
                                        <td>{{ $attendence->user->phone_no }}</td>
                                        <td>{{ $attendence->user->email }}</td>
                                        <td>
                                            <a href="{{ url('admin/cpds/attendence/' . $cpd->id . '/' . $attendence->user->id) }}"
                                                class="btn btn-sm btn-primary mr-2 mb-2">
                                                Email Certificate
                                            </a>
                                            <a href="{{ url('admin/cpds/download_certificate/' . $cpd->id . '/' . $attendence->user->id) }}"
                                                class="btn btn-sm btn-warning mb-2">
                                                Download Certificate
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkboxes
            document.getElementById('select-all').addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('input[name="attendees[]"]').forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });
            });
        });
    </script>
@endsection


@section('customjs')
<script>
$('#addNewAttendeeBtn').on('click', function () {
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
        preConfirm: function () {
            var name = $('#attendeeName').val();
            var email = $('#attendeeEmail').val();
            var membershipNumber = $('#membershipNumber').val();

            if (!name || !email) {
                Swal.showValidationMessage('Please enter both name and email');
                return false;
            }

            return { name: name, email: email, membershipNumber: membershipNumber };
        }
    }).then(function (result) {
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
        url: '{{ route('cpds.attendence.store') }}', // Make sure this route is correct
        type: 'POST',
        data: {
            event_id: {{ $cpd->id }},
            name: name,
            email: email,
            membership_number: membershipNumber
        },
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
        },
        beforeSend: function () {
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
        success: function (response) {

            if (response.success) {
                Swal.fire('Success', 'Attendee has been registered!', 'success').then(function () {
                    location.reload(); // Reload the page to see the changes
                });
            } else {
                Swal.fire('Error', response.message || 'There was an error registering the attendee.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Failed to register attendee. Please try again later.', 'error');
        }
    });
}
</script>
@endsection