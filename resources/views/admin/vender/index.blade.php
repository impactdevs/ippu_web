@extends('layouts.app')
@php
$profile = asset(Storage::url('uploads/avatar/'));
@endphp
@push('script-page')
    <script>
        $(document).on('click', '#billing_data', function() {
            $("[name='shipping_name']").val($("[name='billing_name']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_phone']").val($("[name='billing_phone']").val());
            $("[name='shipping_zip']").val($("[name='billing_zip']").val());
            $("[name='shipping_address']").val($("[name='billing_address']").val());
        })
    </script>
@endpush
@section('page-title')
    {{ __('Manage Vendors') }}
@endsection
@section('breadcrumb')
  {{--   <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Vendor')}}</li>
 --}}
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Vendors</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item">Vendors</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<div class="text-end mb-3">
       {{--  <a href="#" class="btn btn-sm btn-primary" data-url="{{ url('vender.file.import') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
           title="{{ __('Import') }}">
            <i class="ri-upload-cloud-fill"></i>
        </a>

        <a href="{{ url('vender.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Export') }}">
            <i class=" ri-download-cloud-2-fill"></i>
        </a> --}}
        {{-- @can('create vender') --}}
            <a href="#" data-size="lg" data-url="{{ url('admin/vendors/create') }}" data-ajax-popup="true" data-title="{{__('Create New Vendor')}}" data-bs-toggle="tooltip" title="{{ __('Create') }}" class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i> Create New Vendor
            </a>
        {{-- @endcan --}}

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                    {{-- <th>{{ __('Last Login At') }}</th> --}}
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($venders as $k => $Vender)
                                    <tr class="cust_tr" id="vend_detail">
                                        <td class="Id">
                                            @can('show vender')
                                                <a href="{{ url('vender.show', \Crypt::encrypt($Vender['id'])) }}" class="btn btn-outline-primary">
                                                    {{ sprintf('%04d',$Vender['vender_id']) }}
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-outline-primary"> {{ sprintf('%04d',$Vender['vender_id']) }}
                                                </a>
                                            @endcan
                                        </td>
                                        <td>{{ $Vender['name'] }}</td>
                                        <td>{{ $Vender['contact'] }}</td>
                                        <td>{{ $Vender['email'] }}</td>
                                        <td>{{ \Auth::user()->priceFormat($Vender['balance']) }}</td>
                                        {{-- <td>
                                            {{ !empty($Vender->last_login_at) ? $Vender->last_login_at : '-' }}
                                        </td> --}}
                                        <td class="Action">
                                            <span>
                                                @if ($Vender['is_active'] == 0)
                                                    <i class="fa fa-lock" title="Inactive"></i>
                                                @else
                                                    {{-- @can('show vender') --}}
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="{{ url('admin/vendors/'. \Crypt::encrypt($Vender['id'])) }}"
                                                                class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                                title="{{ __('View') }}">
                                                                <i class="las la-eye text-white text-white"></i>
                                                            </a>
                                                        </div>
                                                    {{-- @endcan --}}
                                                    {{-- @can('edit vender') --}}
                                                            <div class="action-btn bg-primary ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm align-items-center" data-size="lg"
                                                                data-title="{{__('Edit Vendor')}}"
                                                                    data-url="{{ url('admin/vendors/'. $Vender['id'].'/edit') }}"
                                                                    data-ajax-popup="true" title="{{ __('Edit') }}"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}">
                                                                    <i class="las la-edit text-white"></i>
                                                                </a>
                                                            </div>
                                                    {{-- @endcan --}}
                                                    @can('delete vender')
                                                            <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'url' => ['vender.destroy', $Vender['id']], 'id' => 'delete-form-' . $Vender['id']]) !!}

                                                            <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip"
                                                                   title="{{ __('Delete') }}" title="{{ __('Delete') }}"
                                                                   data-confirm="{{ __('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?') }}"
                                                                   data-confirm-yes="document.getElementById('delete-form-{{ $Vender['id'] }}').submit();">
                                                                <i class="las la-trash text-white text-white"></i>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                    @endcan
                                                @endif
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
