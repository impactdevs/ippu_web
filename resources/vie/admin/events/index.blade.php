@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Events</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Events</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <h5 class="card-title">Events</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped dataTable table-responsive table-hover">
                <thead role="rowgroup">
                    <tr role="row">
                        <th role='columnheader'>Name</th>
                        <th role='columnheader'>Start Date</th>
                        <th role='columnheader'>End Date</th>
                        <th role='columnheader'>Rate</th>
                        <th role='columnheader'>Member Rate</th>
                        <th scope="col" data-label="Actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td data-label="Name">{{ $event->name ?: "(blank)" }}</td>
                        <td data-label="Start Date">{{ $event->start_date ? (date('F j, Y, g:i a',strtotime($event->start_date))) : "(blank)" }}</td>
                        <td data-label="End Date">{{ $event->end_date ? (date('F j, Y, g:i a',strtotime($event->end_date))): "(blank)" }}</td>
                        <td data-label="Rate">{{ (($event->rate) ? number_format($event->rate) : '') ?: "Free" }}</td>
                        <td data-label="Member Rate">{{ (($event->member_rate) ? number_format($event->member_rate) : '') ?: "Free" }}</td>

                        <td data-label="Actions:" class="text-nowrap">
                         <a href="{{route('events.show', compact('event'))}}" type="button" class="btn btn-primary btn-sm me-1">@lang('Show')</a>
                         <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('events.edit', compact('event'))}}">@lang('Edit')</a></li>
                                <li>
                                    <form action="{{route('events.destroy', compact('event'))}}" method="POST" style="display: inline;" class="m-0 p-0">
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

        {{-- {{ $events->withQueryString()->links() }} --}}
    </div>
    <div class="text-center my-2">
        <a href="{{ route('events.create', []) }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('Create new Event')</a>
    </div>
</div>
</div>
@endsection
