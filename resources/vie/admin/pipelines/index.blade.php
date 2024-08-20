@extends('layouts.app')
@section('page-title')
{{__('Manage Pipelines')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Pipelines</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Pipelines</li>
        </ol>
    </div>

</div>
@endsection

@section('content')

<div class="row">
    <div class="text-end col-md-12 mb-3">
        <a href="#" data-size="md" data-action="New" data-id="2323" data-title="Create new Pipeline" ajax-load="true" data-url="{{ url('admin/pipelines') }}" title="{{__('Create New Pipeline')}}" class="btn btn-sm btn-primary">
            <i class="ri-add-fill"></i> Add New Pipeline
        </a>
    </div>
    <div class="">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>{{__('Pipeline')}}</th>
                                <th width="250px">{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pipelines as $pipeline)
                            <tr>
                                <td>{{ $pipeline->name }}</td>
                                <td class="Action">
                                    <span>
                                        @if(count($pipelines) > 1)
                                        @can('delete pipeline')
                                        <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['pipelines.destroy', $pipeline->id]]) !!}
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="las la-trash text-white"></i></a>
                                            {!! Form::close() !!}
                                        </div>
                                        @endcan
                                        @endif
                                        {{-- @can('edit pipeline') --}}
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-action="Update" data-id="{{ $pipeline->id }}" data-title="Update Pipeline" ajax-load="true" data-url="{{ url('admin/pipelines') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Pipeline')}}">
                                                <i class="las la-edit text-white"></i> Edit
                                            </a>
                                        {{-- @endcan --}}

                                    </span>
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
