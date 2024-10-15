@extends('layouts.app')
@section('page-title')
    {{__('Manage Product-Service & Income-Expense Category')}}
@endsection
@section('breadcrumb')
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Categories</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Categories</li>
        </ol>
    </div>
</div>
@endsection

@section('action-btn')
    <div class="float-end">
        @can('create constant category')
            <a href="#" data-url="{{ route('product-category.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" title="{{__('Create')}}" data-title="{{__('Create New Category')}}"  class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i>
            </a>
        @endcan
    </div>
@endsection

@section('content')
    <div class="text-end mb-3">
        {{-- @can('create constant category') --}}
            <a href="#" data-url="{{ url('admin/product-category/create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" title="{{__('Create')}}" data-title="{{__('Create New Category')}}"  class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i> Create New Category
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
                                <th> {{__('Category')}}</th>
                                <th> {{__('Type')}}</th>
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="font-style">{{ $category->name }}</td>
                                    <td class="font-style">
                                        {{ __(\App\Models\ProductServiceCategory::$categoryType[$category->type]) }}
                                    </td>
                                    <td class="Action">
                                        <span>
                                        {{-- @can('edit constant category') --}}
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ url('admin/product-category/'.$category->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Product Category')}}" data-bs-toggle="tooltip" title="{{__('Create')}}" title="{{__('Edit')}}">
                                                        <i class="las la-edit text-white"></i>
                                                    </a>
                                                </div>
                                            {{-- @endcan --}}
                                            @can('delete constant category')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['product-category.destroy', $category->id],'id'=>'delete-form-'.$category->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$category->id}}').submit();">
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
