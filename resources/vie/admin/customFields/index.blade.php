@extends('layouts.app')
@section('page-title')
    {{__('Manage Custom Field')}}
@endsection
@section('breadcrumb')
    

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Custom Fields</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item">{{__('Custom Field')}}</li>
        </ol>
    </div>

</div>
@endsection


@section('content')
<div class="text-end mb-3">
        {{-- @can('create constant custom field') --}}
            <a href="#" data-url="{{ url('admin/custom-fields/create') }}" data-bs-toggle="tooltip" title="{{__('Create')}}" data-ajax-popup="true" data-title="{{__('Create New Custom Field')}}" class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i> Create New Custom Field
            </a>
        {{-- @endcan --}}
    </div>
    <div class="row">
        <div class="col-3">
            {{-- @include('layouts.account_setup') --}}
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Custom Field')}}</th>
                                <th> {{__('Type')}}</th>
                                <th> {{__('Module')}}</th>
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($custom_fields as $field)
                                <tr>
                                    <td>{{ $field->name}}</td>
                                    <td>{{ $field->type}}</td>
                                    <td>{{ $field->module}}</td>
                                    {{-- @if(Gate::check('edit constant custom field') || Gate::check('delete constant custom field')) --}}
                                        <td class="Action">
                                            <span>
                                            {{-- @can('edit constant custom field') --}}
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ url('admin/custom-fields/'.$field->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Custom Field')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" title="{{__('Edit')}}">
                                                    <i class="las la-edit text-white"></i>
                                                </a>
                                                    </div>
                                                {{-- @endcan --}}
                                                @can('delete constant custom field')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'url' => ['custom-field.destroy', $field->id],'id'=>'delete-form-'.$field->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$field->id}}').submit();">
                                                        <i class="las la-trash text-white"></i>
                                                    </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </span>
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
