@extends('layouts.app')
@section('page-title')
    {{__('Manage Proposals')}}
@endsection
@section('breadcrumb')
   {{--  <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Proposal')}}</li> --}}

<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Proposals</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item">Proposals</li>
        </ol>
    </div>

</div>
@endsection

@section('action-btn')
    <div class="float-end">

        <a href="{{url('proposal.export')}}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}">
            <i class=" ri-download-cloud-2-fill"></i>
        </a>

        @can('create proposal')
            <a href="{{ url('proposal.create',0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ri-add-fill"></i>
            </a>
        @endcan
    </div>

@endsection
@push('css-page')

@endpush
@push('script-page')

@endpush
@section('content')
    <div class="text-end mb-3">

        {{-- <a href="{{url('proposal.export')}}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}">
            <i class=" ri-download-cloud-2-fill"></i>
        </a> --}}

        {{-- @can('create proposal') --}}
            <a href="{{ url('admin/proposals/create',0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ri-add-fill"></i> Create Proposal
            </a>
        {{-- @endcan --}}
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
{{--                        @if(!\Auth::guard('customer')->check())--}}
                            {{ Form::open(array('url' => array('admin/proposals'),'method' => 'GET','id'=>'frm_submit')) }}
{{--                        @else--}}
{{--                            {{ Form::open(array('url' => array('customer.proposal'),'method' => 'GET','id'=>'frm_submit')) }}--}}
{{--                        @endif--}}
                        <div class="d-flex align-items-center justify-content-end">
{{--                            @if(!\Auth::guard('customer')->check())--}}
{{--                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">--}}
{{--                                    <div class="btn-box">--}}
{{--                                        {{ Form::label('customer', __('Customer'),['class'=>'form-label']) }}--}}
{{--                                        {{ Form::select('customer',$customer,isset($_GET['customer'])?$_GET['customer']:'', array('class' => 'form-control select')) }}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endif--}}
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                <div class="btn-box">
                                    {{ Form::label('issue_date', __('Date'),['class'=>'form-label']) }}
                                    {{ Form::date('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:null, array('class' => 'form-control month-btn','id'=>'pc-daterangepicker-1')) }}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 ">
                                <div class="btn-box">
                                    {{ Form::label('status', __('Status'),['class'=>'form-label']) }}
                                    {{ Form::select('status', [ ''=>'Select Status'] + $status,isset($_GET['status'])?$_GET['status']:'', ['class' => 'form-control select','id'=>'stat']) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">

                                <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="document.getElementById('frm_submit').submit(); return false;" data-bs-toggle="tooltip" title="{{__('apply')}}">
                                    <span class="btn-inner--icon">Search
                                    </span>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                   title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon">Reset</span>
                                </a>
                            </div>

                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Proposal')}}</th>
{{--                                @if(!\Auth::guard('customer')->check())--}}
{{--                                    <th> {{__('Customer')}}</th>--}}
{{--                                @endif--}}
                                <th> {{__('Category')}}</th>
                                <th> {{__('Issue Date')}}</th>
                                <th> {{__('Status')}}</th>
                                {{-- @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal')) --}}
                                    <th width="10%"> {{__('Action')}}</th>
                                {{-- @endif --}}
                                {{-- <th>
                                    <td class="barcode">
                                        {!! DNS1D::getBarcodeHTML($invoice->sku, "C128",1.4,22) !!}
                                        <p class="pid">{{$invoice->sku}}</p>
                                    </td>
                                </th> --}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($proposals as $proposal)
                                <tr class="font-style">
                                    <td class="Id">
                                        <a href="{{ url('admin/proposals',\Crypt::encrypt($proposal->id)) }}" class="btn btn-outline-primary">{{ AUth::user()->proposalNumberFormat($proposal->proposal_id) }}
                                        </a>
                                    </td>

                                    <td>{{ !empty($proposal->category)?$proposal->category->name:''}}</td>
                                    <td>{{ Auth::user()->dateFormat($proposal->issue_date) }}</td>
                                    <td>
                                        @if($proposal->status == 0)
                                            <span class="status_badge badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 1)
                                            <span class="status_badge badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 2)
                                            <span class="status_badge badge bg-success p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 3)
                                            <span class="status_badge badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 4)
                                            <span class="status_badge badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @endif
                                    </td>
                                    {{-- @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal')) --}}
                                        <td class="Action">
                                            @if($proposal->is_convert==0)
                                                {{-- @can('convert invoice') --}}
                                                    {{-- <div class="action-btn bg-warning ms-2">
                                                        {!! Form::open(['method' => 'get', 'url' => ['proposal.convert', $proposal->id],'id'=>'proposal-form-'.$proposal->id]) !!}

                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip"
                                                           title="{{__('Convert Invoice')}}" title="{{__('Convert to Invoice')}}" title="{{__('Delete')}}" data-confirm="{{__('You want to confirm convert to invoice. Press Yes to continue or Cancel to go back')}}" data-confirm-yes="document.getElementById('proposal-form-{{$proposal->id}}').submit();">
                                                            <i class="las la-exchange-alt text-white"></i>
                                                            {!! Form::close() !!}
                                                        </a>
                                                    </div> --}}
                                                {{-- @endcan --}}
                                            @else
                                                {{-- @can('show invoice') --}}
                                                   {{--  <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ url('invoice.show',\Crypt::encrypt($proposal->converted_invoice_id)) }}"
                                                           class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Already convert to Invoice')}}" title="{{__('Already convert to Invoice')}}" >
                                                            <i class="ti ti-file text-white"></i>
                                                        </a>
                                                    </div> --}}
                                                {{-- @endcan --}}
                                            @endif
                                            {{-- @can('duplicate proposal') --}}
                                               {{--  <div class="action-btn bg-success ms-2">
                                                    {!! Form::open(['method' => 'get', 'url' => ['proposal.duplicate', $proposal->id],'id'=>'duplicate-form-'.$proposal->id]) !!}

                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Duplicate')}}" title="{{__('Duplicate')}}" title="{{__('Delete')}}" data-confirm="{{__('You want to confirm duplicate this invoice. Press Yes to continue or Cancel to go back')}}" data-confirm-yes="document.getElementById('duplicate-form-{{$proposal->id}}').submit();">
                                                        <i class="las la-copy text-white text-white"></i>
                                                        {!! Form::close() !!}
                                                    </a>
                                                </div> --}}
                                            {{-- @endcan --}}
                                            {{-- @can('show proposal') --}}

                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="{{ url('admin/proposals',\Crypt::encrypt($proposal->id)) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" title="{{__('Detail')}}">
                                                            <i class="las la-eye text-white text-white"></i>
                                                        </a>
                                                    </div>
                                            {{-- @endcan --}}
                                            {{-- @can('edit proposal') --}}
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="{{ url('admin/proposals/'.\Crypt::encrypt($proposal->id.'/edit')) }}" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" title="{{__('Edit')}}">
                                                        <i class="las la-edit text-white"></i>
                                                    </a>
                                                </div>
                                            {{-- @endcan --}}

                                            @can('delete proposal')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'url' => ['proposal.destroy', $proposal->id],'id'=>'delete-form-'.$proposal->id]) !!}

                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$proposal->id}}').submit();">
                                                        <i class="las la-trash text-white text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endcan
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
