@extends('layouts.app')
@section('page-title')
    {{__('Manage Tax Rate')}}
@endsection
@section('breadcrumb')
    
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Taxes</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
           <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Taxes')}}</li>
        </ol>
    </div>

</div>
@endsection
@section('action-btn')
    <div class="float-end">
        @can('create constant tax')
            <a href="#" data-url="{{ route('taxes.create') }}" data-ajax-popup="true" data-title="{{__('Create Tax Rate')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
<div class="text-end mb-3">
        {{-- @can('create constant tax') --}}
            <a href="#" data-url="{{ url('admin/taxes/create') }}" data-ajax-popup="true" data-title="{{__('Create Tax Rate')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i> Create Tax Rate
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
                        <table class="table dataTable">
                            <thead>
                            <tr>
                                <th> {{__('Tax Name')}}</th>
                                <th> {{__('Rate %')}}</th>
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($taxes as $taxe)
                                <tr class="font-style">
                                    <td>{{ $taxe->name }}</td>
                                    <td>{{ $taxe->rate }}</td>
                                    <td class="Action">
                                        <span>
                                        {{-- @can('edit constant tax') --}}
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ url('admin/taxes/'.$taxe->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Tax Rate')}}" data-bs-toggle="tooltip" title="{{__('Edit')}}" title="{{__('Edit')}}">
                                                    <i class="las la-edit text-white"></i>
                                                </a>
                                                </div>
                                            {{-- @endcan --}}
                                            @can('delete constant tax')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['taxes.destroy', $taxe->id],'id'=>'delete-form-'.$taxe->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$taxe->id}}').submit();">
                                                <i class="las la-trash text-white"></i>
                                            </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
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
