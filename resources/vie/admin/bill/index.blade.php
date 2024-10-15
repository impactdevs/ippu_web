@extends('layouts.app')
@section('page-title')
    {{__('Manage Bills')}}
@endsection
@push('script-page')
    <script>
        $('.copy_link').click(function (e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        });
    </script>
@endpush
@section('breadcrumb')
    

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Bills</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
           <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Bills')}}</li>
        </ol>
    </div>

</div>
@endsection

@section('action-btn')
    
@endsection


@section('content')
<div class="text-end mb-3">
       {{--  <a href="{{ url('bill.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}">
            <i class=" ri-download-cloud-2-fill"></i>
        </a> --}}

        {{-- @can('create bill') --}}
            <a href="{{ url('admin/bills/create/0') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ri-add-fill"></i>
            </a>
        {{-- @endcan --}}
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('url' => array('bill.index'),'method' => 'GET','id'=>'frm_submit')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-3"></div>
                                    <div class="col-3"></div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 month">
                                        <div class="btn-box">
                                            {{Form::label('bill_date',__('Bill Date'),['class'=>'form-label'])}}
                                            {{ Form::text('bill_date', isset($_GET['bill_date'])?$_GET['bill_date']:null, array('class' => 'form-control month-btn','id'=>'pc-daterangepicker-1','readonly')) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('status', __('Status'),['class'=>'form-label'])}}
                                            {{ Form::select('status', [''=>'Select Status'] + $status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select','id'=>'ddd')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('frm_submit').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" title="{{__('apply')}}">
                                            <span class="btn-inner--icon"><i class="las la-search"></i></span>
                                        </a>
                                        <a href="{{url('bill.index')}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Reset') }}" title="{{__('Reset')}}">
                                            <span class="btn-inner--icon"><i class="las la-redo-alttext-white-off "></i></span>
                                        </a>
                                    </div>
                                </div>
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
                                <th> {{__('Bill')}}</th>
                                <th> {{__('Category')}}</th>
                                <th> {{__('Bill Date')}}</th>
                                <th> {{__('Due Date')}}</th>
                                <th>{{__('Status')}}</th>
                                @if(Gate::check('edit bill') || Gate::check('delete bill') || Gate::check('show bill'))
                                    <th width="10%"> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($bills as $bill)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ url('bill.show',\Crypt::encrypt($bill->id)) }}" class="btn btn-outline-primary">{{ AUth::user()->billNumberFormat($bill->bill_id) }}</a>
                                    </td>
                                    <td>{{ !empty($bill->category)?$bill->category->name:''}}</td>
                                    <td>{{ Auth::user()->dateFormat($bill->bill_date) }}</td>
                                    <td>{{ Auth::user()->dateFormat($bill->due_date) }}</td>
                                    <td>
                                        @if($bill->status == 0)
                                            <span class="status_badge badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 1)
                                            <span class="status_badge badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 2)
                                            <span class="status_badge badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 3)
                                            <span class="status_badge badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 4)
                                            <span class="status_badge badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Gate::check('edit bill') || Gate::check('delete bill') || Gate::check('show bill'))
                                        <td class="Action">
                                            <span>
                                                @can('duplicate bill')
                                                    <div class="action-btn bg-success ms-2">
                                                        {!! Form::open(['method' => 'get', 'url' => ['bill.duplicate', $bill->id],'id'=>'duplicate-form-'.$bill->id]) !!}

                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para " data-bs-toggle="tooltip" title="{{__('Duplicate')}}" data-bs-toggle="tooltip" title="{{__('Duplicate Bill')}}" title="{{__('Delete')}}" data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('duplicate-form-{{$bill->id}}').submit();">
                                                        <i class="las la-copy text-white"></i>
                                                            {!! Form::close() !!}
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('show bill')
                                                    <div class="action-btn bg-info ms-2">
                                                            <a href="{{ url('bill.show',\Crypt::encrypt($bill->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" title="{{__('Detail')}}">
                                                                <i class="las la-eye text-white"></i>
                                                            </a>
                                                        </div>
                                                @endcan
                                                @can('edit bill')
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="{{ url('bill.edit',\Crypt::encrypt($bill->id)) }}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Edit" title="{{__('Edit')}}">
                                                            <i class="las la-edit text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('delete bill')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'url' => ['bill.destroy', $bill->id],'class'=>'delete-form-btn','id'=>'delete-form-'.$bill->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$bill->id}}').submit();">
                                                            <i class="las la-trash text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            </span>
                                        </td>
                                    @endif
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

