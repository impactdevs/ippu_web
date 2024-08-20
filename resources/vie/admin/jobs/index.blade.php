@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Jobs</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Jobs</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <ol class="breadcrumb m-0 p-0 flex-grow-1 mb-2 mb-md-0">
                    <li class="breadcrumb-item"><a href="{{ implode('/', ['','jobs']) }}"> Jobs</a></li>
                </ol>

                <form action="{{ route('jobs.index', []) }}" method="GET" class="m-0 p-0">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm me-2" name="search" placeholder="Search Jobs..." value="{{ request()->search }}">
                        <span class="input-group-btn">
                            <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-search"></i> @lang('Go!')</button>
                        </span>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table class="table table-striped table-responsive dataTable table-hover">
    <thead role="rowgroup">
    <tr role="row">
                    <th role='columnheader'>Title</th>
                    <th role='columnheader'>Visible From</th>
                    <th role='columnheader'>Visible To</th>
                    <th role='columnheader'>Deadline</th>
                <th scope="col" data-label="Actions">Actions</th>
    </tr>
    </thead>
    <tbody>
    @foreach($jobs as $job)
        <tr>
                            <td data-label="Title">{{ $job->title ?: "(blank)" }}</td>
                            <td data-label="Visible From">{{ date('d M, y',strtotime($job->visible_from)) ?: "(blank)" }}</td>
                            <td data-label="Visible To">{{ date('d M, y',strtotime($job->visible_to)) ?: "(blank)" }}</td>
                            <td data-label="Deadline">{{ date('d M, y',strtotime($job->deadline)) ?: "(blank)" }}</td>

            <td data-label="Actions:" class="text-nowrap">
                                   <a href="{{route('jobs.show', compact('job'))}}" type="button" class="btn btn-primary btn-sm me-1">@lang('Show')</a>
<div class="btn-group btn-group-sm">
    <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{route('jobs.edit', compact('job'))}}">@lang('Edit')</a></li>
        <li>
            <form action="{{route('jobs.destroy', compact('job'))}}" method="POST" style="display: inline;" class="m-0 p-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="dropdown-item">@lang('Delete')</button>
            </form>
        </li>
    </ul>
</div>

                            </td>
        </tr>
    @endforeach
    </tbody>
</table>

                {{-- {{ $jobs->withQueryString()->links() }} --}}
            </div>
            <div class="text-center my-2">
                <a href="{{ route('jobs.create', []) }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('Create new Job')</a>
            </div>
        </div>
    </div>
@endsection
