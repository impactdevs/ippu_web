@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Events</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/events') }}">Events</a></li>
            <li class="breadcrumb-item active">{{ $event->name }}</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<div class="container">
    {{-- <div class="card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h5 class="card-title">{{ $event->name }}</h5>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th scope="row">Start Date</th>
                        <th scope="row">End Date</th>
                        <th scope="row">Rate:</th>
                        <th scope="row">Member Rate:</th>
                    </tr>
                    <tr>

                        <td>{{ $event->start_date ?: "(blank)" }}</td>
                        <td>{{ $event->end_date ?: "(blank)" }}</td>
                        <td>{{ $event->rate ?: "(blank)" }}</td>
                        <td>{{ $event->member_rate ?: "(blank)" }}</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">
            <a href="{{ asset('storage/attachments/'.$event->attachment_name) }}" class="btn btn-info text-nowrap me-1" download>Download Resource</a>
            <a href="{{ asset('storage/banners/'.$event->banner_name) }}" class="btn btn-primary text-nowrap me-1" download>Download Banner</a>
        </div>
    </div>
    --}}
    <div class="card">
        <div class="card-body">
            <h4 class="">{{ $event->name }}</h4>
            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-justified mb-3 bg-light" role="tablist">
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
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
                                        <span>{{ date('F j, Y, g:i a',strtotime($event->start_date)) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
                                        <span>{{ date('F j, Y, g:i a',strtotime($event->end_date)) }}</span>
                                    </div>
                                    <div>
                                        <h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
                                        <span>{{ ($event->member_rate) ? number_format($event->member_rate) : 'Free'}}</span>
                                    </div>
                                    @if($event->points)
                                    <div>
                                        <h6 class="text-warning font-weight-bold fw-medium">Points</h6>
                                        <span>{{ $event->points }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <a href="{{ asset('storage/attachments/'.$event->attachment_name) }}" class="btn btn-warning btn-sm" download>Download Resource</a>
                        </div>
                        <div class="col-md-5">
                            <img class="card-img-top img-fluid image" src="{{ asset('storage/banners/'.$event->banner_name) }}" alt="{{ $event->name }}" onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">
                            
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
                                @foreach($event->pending_confimation as $attendence)
                                <tr>
                                    <td>{{ $attendence->user->name }}</td>
                                    <td>{{ $attendence->user->phone_no }}</td>
                                    <td>{{ $attendence->user->email }}</td>
                                    <td>
                                        <a href="{{ url('admin/events/attendence/'.$attendence->id.'/Confirmed') }}" class="btn btn-sm btn-primary">
                                            Book Attendence
                                        </a>

                                        <a href="{{ url('admin/events/attendence/'.$attendence->id.'/Attended') }}" class="btn btn-sm btn-danger">
                                            Confirm Attendence
                                        </a>
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
                                @foreach($event->confirmed as $attendence)
                                <tr>
                                    <td>{{ $attendence->user->name }}</td>
                                    <td>{{ $attendence->user->phone_no }}</td>
                                    <td>{{ $attendence->user->email }}</td>
                                    <td>
                                        <a href="{{ url('admin/events/attendence/'.$attendence->id.'/Attended') }}" class="btn btn-sm btn-primary">
                                            Confirm Attendence
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="pill-justified-settings-1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable">
                            <thead>
                                <th>Name</th>
                                <th>Contacts</th>
                                <th>Email</th>
                            </thead>
                            <tbody>
                                @foreach($event->attended_event as $attendence)
                                <tr>
                                    <td>{{ $attendence->user->name }}</td>
                                    <td>{{ $attendence->user->phone_no }}</td>
                                    <td>{{ $attendence->user->email }}</td>
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
