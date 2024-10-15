@extends('layouts.app')

@section('page-title')
    {{__('Manage Labels')}}
@endsection
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Labels</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Labels</li>
        </ol>
    </div>

</div>
@endsection
@section('action-btn')
    @can('create label')
        
    @endcan
@endsection

@section('content')

    <div class="rowx">
        <div class="coxl-3">
            <div class="text-end mb-3">
            <a href="#" data-size="md" data-action="New" data-id="2323" data-title="Create New Label" ajax-load="true" data-url="{{ url('admin/labels') }}" data-bs-toggle="tooltip" title="{{__('Create Labels')}}" class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i> Create Labels
            </a>
        </div>
            {{-- @include('layouts.crm_setup') --}}
        </div>
        <div class="colsz-9">
            <div class="row justify-content-center">

                <div class="p-3 card">
                    <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                        @php($i=0)
                        @foreach($pipelines as $key => $pipeline)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if($i==0) active @endif" id="pills-user-tab-1" data-bs-toggle="pill"
                                        data-bs-target="#tab{{$key}}" type="button">{{$pipeline['name']}}
                                </button>
                            </li>
                            @php($i++)
                        @endforeach
                    </ul>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content" id="pills-tabContent">
                            @php($i=0)
                            @foreach($pipelines as $key => $pipeline)
                                <div class="tab-pane fade show @if($i==0) active @endif" id="tab{{$key}}" role="tabpanel" aria-labelledby="pills-user-tab-1">
                                    <ul class="list-group sortable">
                                        @foreach ($pipeline['labels'] as $label)
                                            <li class="list-group-item" data-id="{{$label->id}}">
                                                <span class="text-sm text-dark">{{$label->name}}</span>
                                                <span class="float-end">

                                                {{-- @can('edit label') --}}
                                                        {{-- <div class="action-btn bg-info ms-2"> --}}
                                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-action="Update" data-id="{{ $label->id }}" data-title="Update Label" ajax-load="true" data-url="{{ url('admin/labels') }}" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Edit Labels')}}">
                                                            <i class="las la-edit text-white"></i> Edit
                                                        </a>
                                                    {{-- </div> --}}
                                                    {{-- @endcan --}}
                                                    @if(count($pipeline['labels']))
                                                        @can('delete label')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['labels.destroy', $label->id]]) !!}
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="las la-trash text-white"></i></a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        @endcan
                                                    @endif
                                            </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @php($i++)
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
