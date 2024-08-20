@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Jobs</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/jobs') }}">Jobs</a></li>
            <li class="breadcrumb-item active">{{ $job->title }}</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ implode('/', ['','jobs']) }}"> Jobs</a></li>
                    <li class="breadcrumb-item">@lang('Job') #{{$job->id}}</li>
                </ol>

                <a href="{{ route('jobs.index', []) }}" class="btn btn-light"><i class="fa fa-caret-left"></i> Back</a>
            </div>

            <div class="card-body">
                <table class="table table-striped">
    <tbody>
    <tr>
        <th scope="row">ID:</th>
        <td>{{$job->id}}</td>
    </tr>
            <tr>
            <th scope="row">Title:</th>
            <td>{{ $job->title ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Description:</th>
            <td>{!! $job->description ?: "(blank)" !!}</td>
        </tr>
            <tr>
            <th scope="row">Visible From:</th>
            <td>{{ $job->visible_from ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Visible To:</th>
            <td>{{ $job->visible_to ?: "(blank)" }}</td>
        </tr>
            <tr>
            <th scope="row">Deadline:</th>
            <td>{{ $job->deadline ?: "(blank)" }}</td>
        </tr>
            </tbody>
</table>

            </div>

            <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">
                <a href="{{ route('jobs.edit', compact('job')) }}" class="btn btn-info text-nowrap me-1"><i class="fa fa-edit"></i> @lang('Edit')</a>
                <form action="{{ route('jobs.destroy', compact('job')) }}" method="POST" class="m-0 p-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger text-nowrap"><i class="fa fa-trash"></i> @lang('Delete')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
