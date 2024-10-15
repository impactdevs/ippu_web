@extends('layouts.app')
@section('page-title')
    {{__('Manage Product & Services')}}
@endsection
@push('script-page')
@endpush
@section('breadcrumb')
    

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Products / Services</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Product & Services')}}</li>
        </ol>
    </div>

</div>
@endsection

@section('content')
<div class="text-end mb-3">
       {{--  <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ url('productservice.file.import') }}" data-ajax-popup="true" data-title="{{__('Import product CSV file')}}" class="btn btn-sm btn-primary">
            <i class="ri-upload-cloud-fill"></i>
        </a>
        <a href="{{url('productservice.export')}}" data-bs-toggle="tooltip" title="{{__('Export')}}" class="btn btn-sm btn-primary">
            <i class=" ri-download-cloud-2-fill"></i>
        </a> --}}

        <a href="#" data-size="lg" data-url="{{ url('admin/productservice/create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Product')}}" class="btn btn-sm btn-primary">
            <i class="ri-add-fill"></i> Create New Product
        </a>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 {{isset($_GET['category'])?'show':''}}" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['url' => ['productservice.index'], 'method' => 'GET', 'id' => 'product_service']) }}
                        <div class="d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    {{ Form::label('category', __('Category'),['class'=>'form-label']) }}
                                    {{ Form::select('category', $category, null, ['class' => 'form-control select','id'=>'choices-multiple', 'required' => 'required']) }}
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                   onclick="document.getElementById('product_service').submit(); return false;"
                                   data-bs-toggle="tooltip" title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="las la-search"></i></span>
                                </a>
                                <a href="{{ url('productservice.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                   title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="las la-redo-alt"></i></span>
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
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Sku')}}</th>
                                <th>{{__('Sale Price')}}</th>
                                <th>{{__('Purchase Price')}}</th>
                                <th>{{__('Tax')}}</th>
                                <th>{{__('Category')}}</th>
                                <th>{{__('Unit')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Type')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($productServices as $productService)
                                <tr class="font-style">
                                    <td>{{ $productService->name}}</td>
                                    <td>{{ $productService->sku }}</td>
                                    <td>{{ \Auth::user()->priceFormat($productService->sale_price) }}</td>
                                    <td>{{  \Auth::user()->priceFormat($productService->purchase_price )}}</td>
                                    <td>
                                        @if(!empty($productService->tax_id))
                                            @php
                                                // $taxes=\App\Models\Utility::tax();
                                                $taxArr = explode(',', $productService->tax_id);
                                                $taxes  = [];
                                                foreach($taxArr as $tax)
                                                {
                                                    $taxes[] = \App\Models\Tax::find($tax);
                                                }
                                            @endphp

                                            @foreach($taxes as $tax)
                                                <span class="">{{$tax->name .' ('.$tax->rate .'%)'}}</span><br>
                                            @endforeach
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ !empty($productService->category)?$productService->category->name:'' }}</td>
                                    <td>{{ !empty($productService->unit())?$productService->unit()->name:'' }}</td>
                                    @if($productService->type == 'product')
                                        <td>{{$productService->quantity}}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    <td>{{ $productService->type }}</td>

                                    {{-- @if(Gate::check('edit product & service') || Gate::check('delete product & service')) --}}
                                        <td class="Action">

                                            {{-- <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ url('admin/productservice/'.$productService->id.'/detail') }}"
                                                   data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Warehouse Details')}}" data-title="{{__('Warehouse Details')}}">
                                                    <i class="las la-eye text-white"></i>
                                                </a>
                                            </div> --}}

                                            {{-- @can('edit product & service') --}}
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ url('admin/productservice/'.$productService->id.'/edit') }}" data-ajax-popup="true"  data-size="lg " data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Product Edit')}}">
                                                        <i class="las la-edit text-white"></i>
                                                    </a>
                                                </div>
                                            {{-- @endcan --}}
                                            @can('delete product & service')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'url' => ['productservice.destroy', $productService->id],'id'=>'delete-form-'.$productService->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="las la-trash text-white"></i></a>
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

