@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">CPDs</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/cpds') }}">CPDs</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h5 class="card-title">Create New CPD</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('cpds.store', []) }}" method="POST" class="m-0 p-0" enctype="multipart/form-data">
                <div class="card-body row">
                    @csrf
                    <div class="mb-3 col-lg-3">
                        <label for="code" class="form-label">Code:</label>
                        <input type="text" name="code" id="code" class="form-control" value="{{@old('code')}}" required/>
                        @if($errors->has('code'))
                        <div class='error small text-danger'>{{$errors->first('code')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-9">
                        <label for="topic" class="form-label">Topic:</label>
                        <input type="text" name="topic" id="topic" class="form-control" value="{{@old('topic')}}" required/>
                        @if($errors->has('topic'))
                        <div class='error small text-danger'>{{$errors->first('topic')}}</div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content:</label>
                        <textarea name="content" id="content" class="form-control ckeditor">{{@old('content')}}</textarea>
                        @if($errors->has('content'))
                        <div class='error small text-danger'>{{$errors->first('content')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-3">
                        <label for="hours" class="form-label">Hours:</label>
                        <input type="text" name="hours" id="hours" class="form-control" value="{{@old('hours')}}" required/>
                        @if($errors->has('hours'))
                        <div class='error small text-danger'>{{$errors->first('hours')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-3">
                        <label for="points" class="form-label">Points:</label>
                        <input type="text" name="points" id="points" class="form-control" value="{{@old('points')}}" required/>
                        @if($errors->has('points'))
                        <div class='error small text-danger'>{{$errors->first('points')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-3">
                        <label for="target_group" class="form-label">Target Group:</label>
                        <input type="text" name="target_group" id="target_group" class="form-control" value="{{@old('target_group')}}" required/>
                        @if($errors->has('target_group'))
                        <div class='error small text-danger'>{{$errors->first('target_group')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-3">
                        <label for="location" class="form-label">Location:</label>
                        <input type="text" name="location" id="location" class="form-control" value="{{@old('location')}}" required/>
                        @if($errors->has('location'))
                        <div class='error small text-danger'>{{$errors->first('location')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="{{@old('start_date')}}" required/>
                        @if($errors->has('start_date'))
                        <div class='error small text-danger'>{{$errors->first('start_date')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="{{@old('end_date')}}" required/>
                        @if($errors->has('end_date'))
                        <div class='error small text-danger'>{{$errors->first('end_date')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="normal_rate" class="form-label">Normal Rate:</label>
                        <input type="text" name="normal_rate" id="normal_rate" class="form-control number_format" value="{{@old('normal_rate')}}"/>
                        @if($errors->has('normal_rate'))
                        <div class='error small text-danger'>{{$errors->first('normal_rate')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="members_rate" class="form-label">Members Rate:</label>
                        <input type="text" name="members_rate" id="members_rate" class="form-control number_format" value="{{@old('members_rate')}}"/>
                        @if($errors->has('members_rate'))
                        <div class='error small text-danger'>{{$errors->first('members_rate')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="resource" class="form-label">Banner:</label>
                        <input type="file" name="banner" id="banner" class="form-control" value="{{@old('banner')}}" required/>
                        @if($errors->has('resource'))
                        <div class='error small text-danger'>{{$errors->first('banner')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="resource" class="form-label">Resource:</label>
                        <input type="file" name="resource" id="resource" class="form-control" value="{{@old('resource')}}" required/>
                        @if($errors->has('resource'))
                        <div class='error small text-danger'>{{$errors->first('resource')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="statu1s" class="form-label">Status:</label>
                        <select name="status" id="statu1s" class="form-control form-select" required>
                            <option value="" selected disabled>Select Status</option>
                            @foreach(["Active" => "Active", "Inactive" => "Inactive"] as $value => $label )
                            <option value="{{ $value }}" {{ @old('status') == $value ? "selected" : "" }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('status'))
                        <div class='error small text-danger'>{{$errors->first('status')}}</div>
                        @endif
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="type" class="form-label">Type:</label>
                        <select name="type" id="type" class="form-control form-select" >
                            <option value="" selected disabled>Select Type</option>
                            @foreach(["Free" => "Free", "Paid" => "Paid"] as $value => $label )
                            <option value="{{ $value }}" {{ @old('type') == $value ? "selected" : "" }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('type'))
                        <div class='error small text-danger'>{{$errors->first('type')}}</div>
                        @endif
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <a href="{{ route('cpds.index', []) }}" class="btn btn-light">@lang('Cancel')</a>
                        <button type="submit" class="btn btn-primary">@lang('Create new Cpd')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
