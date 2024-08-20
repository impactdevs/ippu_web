@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">CPDs</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">CPDs</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <h5 class="card-title">CPD</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-responsive table-hover dataTable">
                <thead role="rowgroup">
                    <tr role="row">
                        <th role='columnheader'>Code</th>
                        <th role='columnheader'>Topic</th>
                        <th role='columnheader'>Points</th>
                        <th role='columnheader'>Dates</th>
                        <th role='columnheader'>Rates</th>
                        <th role='columnheader'>Status</th>
                        <th role='columnheader'>Type</th>
                        <th scope="col" data-label="Actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cpds as $cpd)
                    <tr>
                        <td data-label="Code">{{ $cpd->code ?: "(blank)" }}</td>
                        <td data-label="Topic">{{ $cpd->topic ?: "(blank)" }}</td>
                        <td data-label="Hours">{{ $cpd->points ?: "(blank)" }}</td>
                        <td data-label="Start Date">{{ date('F j, Y, g:i a',strtotime($cpd->start_date)) ?: "(blank)" }} - {{ date('F j, Y, g:i a',strtotime($cpd->end_date)) ?: "(blank)" }}</td>
                        <td data-label="Rate">{{ (($cpd->rate) ? number_format($cpd->rate) : '') ?: "Free" }}</td>
                        <td data-label="Member Rate">{{ (($cpd->member_rate) ? number_format($cpd->member_rate) : '') ?: "Free" }}</td>
                        <td data-label="Type">{{ $cpd->type ?: "(blank)" }}</td>

                        <td data-label="Actions:" class="text-nowrap">
                            @can('show CPD')
                         <a href="{{route('cpds.show', compact('cpd'))}}" type="button" class="btn btn-primary btn-sm me-1">@lang('Show')</a>
                         @endcan
                         <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
                            <ul class="dropdown-menu">
                                @can('update CPD')
                                <li><a class="dropdown-item" href="{{route('cpds.edit', compact('cpd'))}}">@lang('Edit')</a></li>
                                @endcan
                                @can('delete CPD')
                                <li>
                                    <form action="{{route('cpds.destroy', compact('cpd'))}}" method="POST" style="display: inline;" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">@lang('Delete')</button>
                                    </form>
                                </li>
                                @endcan
                            </ul>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- {{ $cpds->withQueryString()->links() }} --}}
    </div>
    <div class="text-center my-2">
        @can('create CPD')
        <a href="{{ route('cpds.create', []) }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('Create new Cpd')</a>
        @endcan
    </div>
</div>
</div>
@endsection
