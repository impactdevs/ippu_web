@extends('layouts.app')


@push('script-page')
    <script>
        $(document).on('click', '#billing_data', function () {
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

@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Customers</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item">{{__('Customer')}}</li>
        </ol>
    </div>

</div>
@endsection

@section('content')
<div class="text-end mb-3">
        {{-- <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ url('customer.file.import') }}" data-ajax-popup="true" data-title="{{__('Import customer CSV file')}}" class="btn btn-sm btn-primary">
            <i class="ri-upload-cloud-fill"></i>
        </a>
        <a href="{{url('customer.export')}}" data-bs-toggle="tooltip" title="{{__('Export')}}" class="btn btn-sm btn-primary">
            <i class=" ri-download-cloud-2-fill"></i>
        </a>
 --}}
        <a href="#" data-size="lg" data-url="{{ url('admin/customers/create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Customer')}}" class="btn btn-sm btn-primary">
            <i class="ri-add-fill"></i> Create Customer
        </a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Contact')}}</th>
                                <th> {{__('Email')}}</th>
                                <th> {{__('Balance')}}</th>
                                <th> {{__('Last Login')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($customers as $k=>$customer)
                                <tr class="cust_tr" id="cust_detail" data-url="{{url('customer.show',$customer['id'])}}" data-id="{{$customer['id']}}">
                                    <td class="Id">
                                        {{-- @can('show customer') --}}
                                            <a href="{{ url('customer.show',\Crypt::encrypt($customer['id'])) }}" class="btn btn-outline-primary">
                                                {{ Auth::user()->customerNumberFormat($customer['customer_id']) }}
                                            </a>
                                        {{-- @else --}}
                                            {{-- <a href="#" class="btn btn-outline-primary">
                                                {{ $customer['customer_id'] }}
                                            </a> --}}
                                        {{-- @endcan --}}
                                    </td>
                                    <td class="font-style">{{$customer['name']}}</td>
                                    <td>{{$customer['contact']}}</td>
                                    <td>{{$customer['email']}}</td>
                                    <td>{{\Auth::user()->priceFormat($customer['balance'])}}</td>
                                    <td>
                                        {{ (!empty($customer->last_login_at)) ? $customer->last_login_at : '-' }}
                                    </td>
                                    <td class="Action">
                                        <span>
                                        @if($customer['is_active']==0)
                                                <i class="ti ti-lock" title="Inactive"></i>
                                            @else
                                                {{-- @can('show customer') --}}
                                                <div class="action-btn  ms-2">
                                                    <a href="{{ url('admin/customers',\Crypt::encrypt($customer['id'])) }}" class="mx-3 bg-info btn btn-sm align-items-center"
                                                       data-bs-toggle="tooltip" title="{{__('View')}}">
                                                        <i class="las la-eye text-white text-white"></i>
                                                    </a>
                                                </div>
                                                {{-- @endcan --}}
                                                {{-- @can('edit customer') --}}
                                                    <div class="ms-2">
                                                        <a href="#" class="mx-3 bg-primary  btn btn-sm  align-items-center" data-url="{{ url('admin/customers/'.$customer['id'].'/edit') }}" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Edit Customer')}}">
                                                            <i class="las la-edit text-white"></i>
                                                        </a>
                                                    </div>

                                                {{-- @endcan --}}



                                                @can('delete customer')
                                                    <div class=" ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'url' => ['customer.destroy', $customer['id']],'id'=>'delete-form-'.$customer['id']]) !!}
                                                        <a href="#" class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="las la-trash text-white text-white"></i></a>
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
