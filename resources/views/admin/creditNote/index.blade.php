@extends('layouts.app')
@section('page-title')
    {{__('Manage Credit Notes')}}
@endsection

@section('breadcrumb')
    

<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Credit Notes</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Credit Notes')}}</li>
        </ol>
    </div>

</div>
@endsection

@section('action-btn')
    
@endsection

@section('content')
<div class="text-end mb-3">
        {{-- @can('create credit note') --}}
            <a href="#" data-url="{{ url('admin/custom-credit-note') }}"data-bs-toggle="tooltip" title="{{__('Create')}}" data-ajax-popup="true" data-title="{{__('Create New Credit Note')}}" class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i> Create New Credit Note
            </a>
        {{-- @endcan --}}
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style mt-2">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Invoice')}}</th>
                                <th> {{__('Customer')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Description')}}</th>
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($invoices as $invoice)
                                @if(!empty($invoice->creditNote))
                                    @foreach ($invoice->creditNote as $creditNote)
                                        <tr>
                                            <td class="Id">
                                                <a href="{{ url('invoice.show',\Crypt::encrypt($creditNote->invoice)) }}" class="btn btn-outline-primary">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>
                                            </td>
                                            <td>{{ (!empty($invoice->customer)?$invoice->customer->name:'-') }}</td>
                                            <td>{{ Auth::user()->dateFormat($creditNote->date) }}</td>
                                            <td>{{ Auth::user()->priceFormat($creditNote->amount) }}</td>
                                            <td>{{!empty($creditNote->description)?$creditNote->description:'-'}}</td>
                                            <td>
                                                @can('edit credit note')
                                                    <div class=" ms-2">
                                                        <a data-url="{{ url('invoice.edit.credit.note',[$creditNote->invoice,$creditNote->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Credit Note')}}" href="#" class="mx-3 bg-primary btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" title="{{__('Edit')}}">
                                                            <i class="las la-edit text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('edit credit note')
                                                        <div class=" ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'url' => array('invoice.delete.credit.note', $creditNote->invoice,$creditNote->id),'class'=>'delete-form-btn','id'=>'delete-form-'.$creditNote->id]) !!}
                                                                <a href="#" class="mx-3 bg-danger btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$creditNote->id}}').submit();">
                                                                    <i class="las la-trash text-white"></i>
                                                                </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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
        $(document).on('change', '#invoice', function () {

            var id = $(this).val();
            var url = "{{url('admin/credit_note/invoice')}}";

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                data: {
                    'id': id,

                },
                success: function (data) {
                    $('#amount').val(data)
                },

            });

        })
    </script>
@endsection
