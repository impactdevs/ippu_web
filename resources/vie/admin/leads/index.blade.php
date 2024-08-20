@extends('layouts.app')
@section('page-title')
    {{__('Manage Leads')}} @if($pipeline) - {{$pipeline->name}} @endif
@endsection

@section('customcss')
    <link rel="stylesheet" href="{{asset('css/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/dragula.min.css') }}" id="main-style-link">
@endsection
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Leads</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Leads</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<div class="text-end mb-3">


            {{ Form::open(array('url' => 'admin/deals_change_pipeline','id'=>'change-pipeline','class'=>'btn btn-sm ')) }}
            {{ Form::select('default_pipeline_id', $pipelines,$pipeline->id, array('class' => 'form-control select','id'=>'default_pipeline_id')) }}
            {{ Form::close() }}


            <a href="{{ url('admin/leads_list') }}" data-size="lg" data-bs-toggle="tooltip" title="{{__('List View')}}" class="btn btn-sm btn-primary">
                <i class="ri-list-check"></i>
            </a>
            <a href="#" data-size="lg" data-action="New" data-id="2323" data-title="Create New Lead" ajax-load="true" data-url="{{ url('admin/leads') }}" data-bs-toggle="tooltip" title="{{__('Create New Lead')}}" class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i>
            </a>

    </div>
    <div class="row">
        <div class="col-sm-12">
            @php
                $lead_stages = $pipeline->leadStages;
                $json = [];
                foreach ($lead_stages as $lead_stage){
                    $json[] = 'task-list-'.$lead_stage->id;
                }
            @endphp
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='{!! json_encode($json) !!}' data-plugin="dragula">
                @foreach($lead_stages as $lead_stage)
                    @php($leads = $lead_stage->lead())
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <span class="btn btn-sm btn-primary btn-icon count">
                                        {{count($leads)}}
                                    </span>
                                </div>
                                <h4 class="mb-0">{{$lead_stage->name}}</h4>
                            </div>
                            <div class="card-body kanban-box" id="task-list-{{$lead_stage->id}}" data-id="{{$lead_stage->id}}">
                                @foreach($leads as $lead)
                                    <div class="card" data-id="{{$lead->id}}">
                                        <div class="pt-3 ps-3">
                                            @php($labels = $lead->labels())
                                            @if($labels)
                                                @foreach($labels as $label)
                                                    <div class="badge-xs badge bg-{{$label->color}} p-2 px-3 rounded">{{$label->name}}</div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="card-header border-0 pb-0  d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                            <h5><a href="{{ url('admin/leads/'.$lead->id) }}">{{$lead->name}}</a></h5>
                                            <div class="card-header-right">
                                                @if(Auth::user()->type != 'Client')
                                                    <div class="btn-group card-option">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="las la-menu"></i></button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            {{-- @can('edit lead') --}}
                                                                <a href="#!" data-size="md" data-url="{{ URL::to('admin/leads/'.$lead->id.'/labels') }}" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="{{__('Labels')}}">
                                                                    <i class="ti ti-bookmark"></i>
                                                                    <span>{{__('Labels')}}</span>
                                                                </a>

                                                                <a href="#!" data-size="lg" data-action="Update" data-id="{{ $lead->id }}" data-title="Edit Lead" ajax-load="true" data-url="{{ url('admin/leads') }}" class="dropdown-item" data-bs-original-title="{{__('Edit Lead')}}">
                                                                    {{-- <i class="las la-edit"></i> --}}
                                                                    <span>{{__('Edit')}}</span>
                                                                </a>

                                                                <a href="#!" data-size="lg" data-url="{{ URL::to('admin/leads/'.$lead->id.'/edit') }}" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="{{__('Edit Lead')}}">
                                                                    <i class="las la-edit"></i>
                                                                    <span>{{__('Edit')}}</span>
                                                                </a>
                                                            {{-- @endcan --}}
                                                            @can('delete lead')
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['leads.destroy', $lead->id],'id'=>'delete-form-'.$lead->id]) !!}
                                                                <a href="#!" class="dropdown-item bs-pass-para">
                                                                    <i class="ti ti-archive"></i>
                                                                    <span> {{__('Delete')}} </span>
                                                                </a>
                                                                {!! Form::close() !!}
                                                            @endcan


                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <?php
                                        $products = $lead->products();
                                        $sources = $lead->sources();
                                        ?>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <ul class="list-inline mb-0">

                                                    <li class="list-inline-item d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Product')}}">
                                                        <i class="f-16 text-primary ti ti-shopping-cart"></i> {{count($products)}}
                                                    </li>

                                                    <li class="list-inline-item d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{__('Source')}}">
                                                        <i class="f-16 text-primary ti ti-social"></i>{{count($sources)}}
                                                    </li>
                                                </ul>
                                                <div class="user-group">
                                                    @foreach($lead->users as $user)
                                                    <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="{{$user->name}}" data-bs-toggle="tooltip" title="{{$user->name}}">
                                                        {{-- <img src="@if($user->avatar) {{asset('storage/uploads/avatar/'.$user->avatar)}} @else {{asset('storage/uploads/avatar/avatar.png')}} @endif" alt="image" data-bs-toggle="tooltip" title="{{$user->name}}"> --}}
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('customjs')
<script src="{{asset('css/summernote/summernote-bs4.js')}}"></script>
    <script src="{{ asset('assets/js/dragula.min.js') }}"></script>
    <script>
        !function (a) {
            "use strict";
            var t = function () {
                this.$body = a("body")
            };
            t.prototype.init = function () {
                a('[data-plugin="dragula"]').each(function () {
                    var t = a(this).data("containers"), n = [];
                    if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                    var r = a(this).data("handleclass");
                    r ? dragula(n, {
                        moves: function (a, t, n) {
                            return n.classList.contains(r)
                        }
                    }) : dragula(n).on('drop', function (el, target, source, sibling) {

                        var order = [];
                        $("#" + target.id + " > div").each(function () {
                            order[$(this).index()] = $(this).attr('data-id');
                        });

                        var id = $(el).attr('data-id');

                        var old_status = $("#" + source.id).data('status');
                        var new_status = $("#" + target.id).data('status');
                        var stage_id = $(target).attr('data-id');
                        var pipeline_id = '{{$pipeline->id}}';

                        $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                        $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);
                        $.ajax({
                            url: '{{ url('admin/leads_order') }}',
                            type: 'POST',
                            data: {lead_id: id, stage_id: stage_id, order: order, new_status: new_status, old_status: old_status, pipeline_id: pipeline_id, "_token": $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                show_toastr('error', data.error, 'error')
                            }
                        });
                    });
                })
            }, a.Dragula = new t, a.Dragula.Constructor = t
        }(window.jQuery), function (a) {
            "use strict";

            a.Dragula.init()

        }(window.jQuery);


    </script>
    <script>
        $(document).on("change", "#default_pipeline_id", function () {
            $('#change-pipeline').submit();
        });
    </script>
@endsection
