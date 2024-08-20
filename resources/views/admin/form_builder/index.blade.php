@extends('layouts.app')
@section('page-title')
    {{__('Manage Form Builder')}}
@endsection
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Form Builders</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Form Builders</li>
        </ol>
    </div>

</div>
@endsection

@section('content')
    <div class="row">
         <div class="text-end mb-3">
        <a href="#" data-size="md" data-url="{{ url('admin/form_builders/create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Form')}}" class="btn btn-sm btn-primary">
            <i class="ri-add-fill"></i> Create New Form
        </a>
    </div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Response')}}</th>
                                {{-- @if(\Auth::user()->type=='company') --}}
                                    <th class="text-end" width="200px">{{__('Action')}}</th>
                                {{-- @endif --}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($forms as $form)
                                <tr>
                                    <td>{{ $form->name }}</td>
                                    <td>
                                        {{ $form->response->count() }}
                                    </td>
                                    {{-- @if(\Auth::user()->type=='company') --}}
                                        <td class="text-end">


                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center cp_link" data-link="<iframe src='{{url('/form/'.$form->code)}}' title='{{ $form->name }}'></iframe>" data-bs-toggle="tooltip" title="{{__('Click to copy iframe link')}}"><i class="ti ti-frame text-white"></i></a>
                                            </div>

                                            <div class="action-btn bg-success ms-2">
                                                <a href="{{ url('generate_form_qr?form_id='.$form->id.'&url='.url('/form/'.$form->code)) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Generate the qr code')}}"><i class="las la-qrcode"></i></a>
                                            </div>

                                            <div class="action-btn bg-secondary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ url('admin/form_field',$form->id) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Convert into Lead Setting')}}" data-title="{{__('Convert into Lead Setting')}}">
                                                    <i class="las la-exchange-alt text-white"></i>
                                                </a>
                                            </div>


                                            <div class="action-btn bg-primary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center cp_link" data-link="{{url('/form/'.$form->code)}}" data-bs-toggle="tooltip" title="{{__('Click to copy link')}}"><i class="las la-copy text-white"></i></a>
                                            </div>

                                            {{-- @can('manage form field') --}}
                                                <div class="action-btn bg-secondary ms-2">
                                                    <a href="{{url('admin/form_builders/'.$form->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Form field')}}"><i class="ri-grid-fill text-white"></i></a>
                                                </div>
                                            {{-- @endcan --}}

                                            {{-- @can('view form response') --}}
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{url('admin/form_response/'.$form->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('View Response')}}"><i class="las la-eye text-white"></i></a>
                                                </div>
                                            {{-- @endcan --}}
                                            {{-- @can('edit form builder') --}}
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ url('admin/form_builders/'.$form->id.'/edit') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Form Builder Edit')}}">
                                                        <i class="las la-edit text-white"></i>
                                                    </a>
                                                </div>
                                            {{-- @endcan --}}
                                            @can('delete form builder')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['form_builder.destroy', $form->id],'id'=>'delete-form-'.$form->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="las la-trash text-white"></i></a>
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
@section('customjs')
<script>
        $(document).ready(function () {
            $('.cp_link').on('click', function () {
                var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toast("Link Copy on Clipboard","bg-success")
            });
        });

        $(document).ready(function () {
            $('.iframe_link').on('click', function () {
                var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toast("Link Copy on Clipboard","bg-success")
            });
        });

    </script>
@endsection
