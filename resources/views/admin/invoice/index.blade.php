@extends('layouts.app')
@section('page-title')
    {{__('Manage Invoices')}}
@endsection
@push('script-page')
    <script>
        function copyToClipboard(element) {

            var copyText = element.id;
            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        }
    </script>
@endpush


@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Invoice')}}</li> --}}

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Invoices</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item">{{__('Invoices')}}</li>
        </ol>
    </div>

</div>
@endsection

@section('action-btn')
    <div class="float-end">
        {{--        <a class="btn btn-sm btn-primary" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" data-bs-toggle="tooltip" title="{{__('Filter')}}">--}}
        {{--            <i class="ri-filter-2-line"></i>--}}
        {{--        </a>--}}

        <a href="{{ url('invoice.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}">
            <i class=" ri-download-cloud-2-fill"></i>
        </a>

        @can('create invoice')
            <a href="{{ url('invoice.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ri-add-fill"></i>
            </a>
        @endcan
    </div>
@endsection



@section('content')
 <div class="text-end mb-3">
        {{--        <a class="btn btn-sm btn-primary" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1" data-bs-toggle="tooltip" title="{{__('Filter')}}">--}}
        {{--            <i class="ri-filter-2-line"></i>--}}
        {{--        </a>--}}

        {{-- <a href="{{ url('invoice.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Export')}}">
            <i class=" ri-download-cloud-2-fill"></i>
        </a> --}}

        {{-- @can('create invoice') --}}
            <a href="{{ url('admin/invoices/create/0') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ri-add-fill"></i> Create New Invoice
            </a>
        {{-- @endcan --}}
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
{{--                        @if (!\Auth::guard('customer')->check())--}}
                            {{ Form::open(['url' => ['invoice.index'], 'method' => 'GET', 'id' => 'customer_submit']) }}
{{--                        @else--}}
{{--                            {{ Form::open(['url' => ['customer.invoice'], 'method' => 'GET', 'id' => 'customer_submit']) }}--}}
{{--                        @endif--}}
                        <div class="row d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    {{ Form::label('issue_date', __('Issue Date'),['class'=>'form-label'])}}
                                    {{ Form::date('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:'', array('class' => 'form-control month-btn','id'=>'pc-daterangepicker-1')) }}


                                </div>
                            </div>
{{--                            @if (!\Auth::guard('customer')->check())--}}
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                    <div class="btn-box">
                                        {{ Form::label('customer', __('Customer'),['class'=>'form-label'])}}
                                        {{ Form::select('customer', $customer, isset($_GET['customer']) ? $_GET['customer'] : '', ['class' => 'form-control select']) }}
                                    </div>
                                </div>
{{--                            @endif--}}
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('status', __('Status'),['class'=>'form-label'])}}

                                    <select class="form-control select" name="status">
                                        <option value="">Select Status</option>
                                        @foreach($status as $index => $st)
                                        <option value="{{ $index }}"{{ (isset($_GET['status'])? (($_GET['status'] == $index) ? 'selected' : ''):'') }}>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">

                                <a href="#" class="btn btn-sm btn-primary"
                                   onclick="document.getElementById('customer_submit').submit(); return false;"
                                   data-toggle="tooltip" title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="las la-search"></i></span>
                                </a>

{{--                                @if (!\Auth::guard('customer')->check())--}}
{{--                                    <a href="{{ url('invoice.index') }}" class="btn btn-sm btn-danger" data-toggle="tooltip"--}}
{{--                                       title="{{ __('Reset') }}">--}}
{{--                                        <span class="btn-inner--icon"><i class="las la-redo-alttext-white-off"></i></span>--}}
{{--                                    </a>--}}
{{--                                @else--}}
                                    <a href="{{ url('customer.index') }}" class="btn btn-sm btn-primary" data-toggle="tooltip"
                                       title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="las la-trash text-white-off"></i></span>
                                    </a>
{{--                                @endif--}}
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
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{ __('Invoice') }}</th>
{{--                                @if (!\Auth::guard('customer')->check())--}}
{{--                                    <th>{{ __('Customer') }}</th>--}}
{{--                                @endif--}}
                                <th>{{ __('Issue Date') }}</th>
                                <th>{{ __('Due Date') }}</th>
                                <th>{{ __('Due Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                {{-- @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')) --}}
                                    <th>{{ __('Action') }}</th>
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
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td class="Id">
{{--                                        @if (\Auth::guard('customer')->check())--}}
{{--                                            <a href="{{ url('customer.invoice.show', \Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-primary">{{ Auth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>--}}
{{--                                        @else--}}
                                            <a href="{{ url('admin/invoices', \Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-primary">{{ Auth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>
{{--                                        @endif--}}
                                    </td>
{{--                                    @if (!\Auth::guard('customer')->check())--}}
{{--                                        <td> {{ !empty($invoice->customer) ? $invoice->customer->name : '' }} </td>--}}
{{--                                    @endif--}}
                                    <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                    <td>
                                        @if ($invoice->due_date < date('Y-m-d'))
                                            <p class="text-danger">
                                                {{ \Auth::user()->dateFormat($invoice->due_date) }}</p>
                                        @else
                                            {{ \Auth::user()->dateFormat($invoice->due_date) }}
                                        @endif
                                    </td>
                                    <td>{{ \Auth::user()->priceFormat($invoice->getDue()) }}</td>
                                    <td>
                                        @if ($invoice->status == 0)
                                            <span
                                                class="status_badge badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span
                                                class="status_badge badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span
                                                class="status_badge badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span
                                                class="status_badge badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span
                                                class="status_badge badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                    {{-- @if (Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice')) --}}
                                        <td class="Action">
                                                <span>
                                                @php $invoiceID= Crypt::encrypt($invoice->id); @endphp

                                                    @can('copy invoice')
                                                        <div class="  ms-2">
                                                            <a href="#" id="{{ url('invoice.link.copy',[$invoiceID]) }}" class="mx-3 bg-warning btn btn-sm align-items-center"   onclick="copyToClipboard(this)" data-bs-toggle="tooltip" title="{{__('Click to copy')}}"><i class="las la-link text-white"></i></a>
                                                        </div>
                                                    @endcan
                                                    @can('duplicate invoice')
                                                        <div class=" ms-2">
                                                           {!! Form::open(['method' => 'get', 'url' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id,'class' => 'd-inline']) !!}

                                                            <a href="#" class="mx-3 btn bg-success btn-sm align-items-center bs-pass-para" data-toggle="tooltip"
                                                               title="{{ __('Duplicate') }}" data-bs-toggle="tooltip" title="Duplicate Invoice"
                                                               title="{{ __('Delete') }}"
                                                               data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back"
                                                               data-confirm-yes="document.getElementById('duplicate-form-{{ $invoice->id }}').submit();">
                                                                <i class="las la-copy text-white"></i>
                                                                {!! Form::open(['method' => 'get', 'url' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id,'class' => 'd-inline']) !!}
                                                                {!! Form::close() !!}
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    {{-- @can('show invoice') --}}
{{--                                                        @if (\Auth::guard('customer')->check())--}}
{{--                                                            <div class="action-btn bg-info ms-2">--}}
{{--                                                                    <a href="{{ url('customer.invoice.show', \Crypt::encrypt($invoice->id)) }}"--}}
{{--                                                                       class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Show "--}}
{{--                                                                       title="{{ __('Detail') }}">--}}
{{--                                                                        <i class="las la-eye text-white"></i>--}}
{{--                                                                    </a>--}}
{{--                                                                </div>--}}
{{--                                                        @else--}}
                                                            <div class="ms-2">
                                                                    <a href="{{ url('admin/invoices', \Crypt::encrypt($invoice->id)) }}"
                                                                       class="mx-3 bg-info btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Show "
                                                                       title="{{ __('Detail') }}">
                                                                        <i class="las la-eye text-white"></i>
                                                                    </a>
                                                                </div>
{{--                                                        @endif--}}
                                                    {{-- @endcan --}}
                                                    @can('edit invoice')
                                                        <div class=" ms-2">
                                                                <a href="{{ url('invoice.e', \Crypt::encrypt($invoice->id)) }}"
                                                                   class="mx-3 bg-primary btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Edit "
                                                                   title="{{ __('Edit') }}">
                                                                    <i class="las la-edit text-white"></i>
                                                                </a>
                                                            </div>
                                                    @endcan
                                                    @can('delete invoice')
                                                        <div class=" ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'url' => ['invoice.destroy', $invoice->id], 'id' => 'delete-form-' . $invoice->id]) !!}
                                                                    <a href="#" class="mx-3 bg-danger btn btn-sm align-items-center bs-pass-para " data-bs-toggle="tooltip" title="{{__('Delete')}}"
                                                                       title="{{ __('Delete') }}"
                                                                       data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                                       data-confirm-yes="document.getElementById('delete-form-{{ $invoice->id }}').submit();">
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
