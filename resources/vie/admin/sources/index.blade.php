@extends('layouts.app')
@section('page-title')
    {{__('Manage Sources')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Sources</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Sources</li>
        </ol>
    </div>

</div>
@endsection

@section('content')
    <div class="rw">
        <div class="cl-3">
            <div class="text-end mb-3">
                <a href="#" data-size="md" data-action="New" data-id="2323" data-title="Create New Sources" ajax-load="true" data-url="{{ url('admin/sources') }}" data-bs-toggle="tooltip" title="{{__('Create New Sources')}}" class="btn btn-sm btn-primary">
                    <i class="ri-add-fill"></i> Create New Sources
                </a>
            </div>
        </div>
        <div class="cl-9">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Source')}}</th>
                                <th width="250px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($sources as $source)
                                <tr>
                                    <td>{{ $source->name }}</td>
                                    <td class="Active">

                                        {{-- @can('edit source') --}}
                                            {{-- <div class="action-btn bg-info ms-2"> --}}
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"  data-action="Update" data-id="{{ $source->id }}" data-title="Update Sources" ajax-load="true" data-url="{{ url('admin/sources') }}" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Source')}}">
                                                    <i class="las la-edit text-white"></i> Edit
                                                </a>
                                            {{-- </div> --}}
                                        {{-- @endcan --}}
                                        @can('delete source')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['sources.destroy', $source->id]]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="las la-trash text-white"></i></a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endcan
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
