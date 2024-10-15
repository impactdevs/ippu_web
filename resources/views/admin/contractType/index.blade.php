@extends('layouts.app')
@section('page-title')
    {{__('Manage Contract Type')}}
@endsection
@push('script-page')
@endpush

@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Contract Types</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Contract Types</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
    <div class="rowz">
        <div class="colz-3">
            {{-- @include('layouts.crm_setup') --}}
            <div class="text-end mb-3">
                <a href="#" data-size="md" data-action="New" data-id="2323" data-title="Create New Contract Type" ajax-load="true" data-url="{{ url('admin/contract_types') }}" data-bs-toggle="tooltip" title="{{__('Create New Contract Type')}}" class="btn btn-sm btn-primary">
                    <i class="ri-add-fill"></i> Create New Contract Type
                </a>
            </div>
        </div>
        <div class="col-9z">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                                {{-- @if(\Auth::user()->type=='company') --}}
                                    <th class="text-end ">{{__('Action')}}</th>

                                {{-- @endif --}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($types as $type)

                                <tr class="font-style">
                                    <td>{{ $type->name }}</td>

                                    {{-- @if(\Auth::user()->type=='company') --}}
                                        <td class="action text-end">
                                            {{-- <div class="action-btn bg-info ms-2"> --}}
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-action="Update" data-id="{{ $type->id }}" data-title="Edit Type" ajax-load="true" data-url="{{ url('admin/contract_types') }}" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Type')}}">
                                                    <i class="las la-edit text-white"></i> Edit
                                                </a>
                                            {{-- </div> 
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['contractType.destroy', $type->id]]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="las la-trash text-white"></i></a>
                                                {!! Form::close() !!}
                                            </div>--}}
                                        </td>
                                    {{-- @endif --}}
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

