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
            <form action="{{ route('events.store', []) }}" method="POST" class="m-0 p-0" enctype="multipart/form-data">
                <div class="card-body row">
                    @csrf
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
                        <label for="points" class="form-label">CPD Points</label>
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
                        <label for="banner_name" class="form-label">Details:</label>
                        <textarea class="ckeditor" name="details">{{ @old('details') }}</textarea>
                        @if($errors->has('details'))
                        <div class='error small text-danger'>{{$errors->first('details')}}</div>
                        @endif
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <a href="{{ route('events.index', []) }}" class="btn btn-light">@lang('Cancel')</a>
                        <button type="submit" class="btn btn-primary">@lang('Create new Event')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
