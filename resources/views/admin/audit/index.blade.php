@extends('layouts.app')

@section('content')
@forelse ($activityLogs as $activityLog)
<div class="card">
    <div class="card-body">
        <b>{{ $activityLog->causer->name }}</b> {{ $activityLog->description }}
    </div>
</div>
@empty
<div class="card">
    <div class="card-body">No activity logs found</div>
</div>
@endforelse
@endsection
