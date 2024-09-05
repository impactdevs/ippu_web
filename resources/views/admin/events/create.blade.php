@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Events</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/events') }}">Events</a></li>
            <li class="breadcrumb-item active">Create Event</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h5 class="card-title">Create New Event</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('events.store') }}" method="POST" class="m-0 p-0" enctype="multipart/form-data">
                <div class="card-body row">
                    @csrf

                    <!-- Event Type Selection -->
                    <!-- Event Type Selection -->
<div class="mb-3 col-lg-12">
    <label for="event_type" class="form-label">Event Type:</label>
    <div>
        <input type="radio" id="normal_event" name="event_type" value="Normal" {{ old('event_type', 'Normal') == 'Normal' ? 'checked' : '' }}>
        <label for="normal_event">Normal</label>

        <input type="radio" id="annual_event" name="event_type" value="Annual" {{ old('event_type') == 'Annual' ? 'checked' : '' }}>
        <label for="annual_event">Annual</label>
    </div>
    @if($errors->has('event_type'))
        <div class='error small text-danger'>{{ $errors->first('event_type') }}</div>
    @endif
</div>


                    <!-- Common Fields (Visible by Default) -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{@old('name')}}" />
                        @if($errors->has('name'))
                        <div class='error small text-danger'>{{$errors->first('name')}}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{@old('start_date')}}" />
                        @if($errors->has('start_date'))
                        <div class='error small text-danger'>{{$errors->first('start_date')}}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{@old('end_date')}}" />
                        @if($errors->has('end_date'))
                        <div class='error small text-danger'>{{$errors->first('end_date')}}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-lg-4">
                        <label for="rate" class="form-label">Rate:</label>
                        <input type="text" name="rate" id="rate" class="form-control number_format" value="{{@old('rate')}}" />
                        @if($errors->has('rate'))
                        <div class='error small text-danger'>{{$errors->first('rate')}}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-lg-4">
                        <label for="member_rate" class="form-label">Member Rate:</label>
                        <input type="text" name="member_rate" id="member_rate" class="form-control number_format" value="{{@old('member_rate')}}" />
                        @if($errors->has('member_rate'))
                        <div class='error small text-danger'>{{$errors->first('member_rate')}}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-lg-4">
                        <label for="points" class="form-label">Event Points</label>
                        <input type="number" name="points" id="points" class="form-control" value="{{@old('points')}}" />
                        @if($errors->has('points'))
                        <div class='error small text-danger'>{{$errors->first('points')}}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="attachment_name" class="form-label">Attachment:</label>
                        <input type="file" name="attachment_name" id="attachment_name" class="form-control" value="{{@old('attachment_name')}}" />
                        @if($errors->has('attachment_name'))
                        <div class='error small text-danger'>{{$errors->first('attachment_name')}}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="banner_name" class="form-label">Banner:</label>
                        <input type="file" name="banner_name" id="banner_name" class="form-control" value="{{@old('banner_name')}}" />
                        @if($errors->has('banner_name'))
                        <div class='error small text-danger'>{{$errors->first('banner_name')}}</div>
                        @endif
                    </div>

                    <div class="mb-3 col-lg-12">
                        <label for="details" class="form-label">Details:</label>
                        <textarea class="ckeditor" name="details">{{ @old('details') }}</textarea>
                        @if($errors->has('details'))
                        <div class='error small text-danger'>{{$errors->first('details')}}</div>
                        @endif
                    </div>

                    <!-- Fields for Annual Events (Hidden by Default) -->
                    <div id="annual_event_fields" style="display: none;">
                        <div class="mb-3 col-lg-12">
                            <label for="theme" class="form-label">Theme:</label>
                            <input type="text" name="theme" id="theme" class="form-control" value="{{@old('theme')}}" />
                            @if($errors->has('theme'))
                            <div class='error small text-danger'>{{$errors->first('theme')}}</div>
                            @endif
                        </div>

                        <div class="mb-3 col-lg-12">
                            <label for="organizing_committee" class="form-label">Organizing Committee:</label>
                            <input type="text" name="organizing_committee" id="organizing_committee" class="form-control" value="{{@old('organizing_committee')}}" />
                            @if($errors->has('organizing_committee'))
                            <div class='error small text-danger'>{{$errors->first('organizing_committee')}}</div>
                            @endif
                        </div>

                        <div class="mb-3 col-lg-12">
                            <label for="annual_event_date" class="form-label">Date:</label>
                            <input type="date" name="annual_event_date" id="annual_event_date" class="form-control" value="{{@old('annual_event_date')}}" />
                            @if($errors->has('annual_event_date'))
                            <div class='error small text-danger'>{{$errors->first('annual_event_date')}}</div>
                            @endif
                        </div>

                        <div class="mb-3 col-lg-12">
                            <label for="place" class="form-label">Place:</label>
                            <input type="text" name="place" id="place" class="form-control" value="{{@old('place')}}" />
                            @if($errors->has('place'))
                            <div class='error small text-danger'>{{$errors->first('place')}}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <a href="{{ route('events.index') }}" class="btn btn-light">@lang('Cancel')</a>
                        <button type="submit" class="btn btn-primary">@lang('Create new Event')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@section('customjs')
<script>
    // Show/Hide fields based on event type selection
    document.querySelectorAll('input[name="event_type"]').forEach((elem) => {
        elem.addEventListener('change', function() {
            var eventType = this.value;
            var annualFields = document.getElementById('annual_event_fields');

            if (eventType === 'Annual') {
                annualFields.style.display = 'block';
            } else {
                annualFields.style.display = 'none';
            }
        });
    });

    // Initial check on page load
    document.addEventListener('DOMContentLoaded', function() {
        var initialEventType = document.querySelector('input[name="event_type"]:checked').value;
        var annualFields = document.getElementById('annual_event_fields');

        if (initialEventType === 'Annual') {
            annualFields.style.display = 'block';
        } else {
            annualFields.style.display = 'none';
        }
    });
</script>
@endsection

@endsection
