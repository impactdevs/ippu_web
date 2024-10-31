@extends('layouts.app')
@section('page-title')
    {{ $formBuilder->name.__("'s Form Field") }}
@endsection
@push('script-page')

@endpush
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Form Builders</h4>

    <div class="page-title-right">
        <ol class="m-0 breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{url('admin/form_builders')}}">{{__('Form Builder')}}</a></li>
    <li class="breadcrumb-item">{{__('Add Field')}}</li>
        </ol>
    </div>

</div>
@endsection
@section('action-btn')
    @can('create form field')
        <div class="float-end">
            <a href="#" data-size="md" data-url="{{ route('form.field.create',$formBuilder->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Filed')}}" class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i>
            </a>
        </div>
    @endcan
@endsection

@section('content')
    <div class="row">
        <div class="mb-3 text-end">
            <a href="#" data-size="md" data-url="{{ url('admin/form_builder/'.$formBuilder->id.'/field') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create New Filed')}}" class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i>
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
                                <th>{{__('Type')}}</th>
                                <th class="text-end" width="200px">{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($formBuilder->form_field->count())
                                @foreach ($formBuilder->form_field as $field)
                                    <tr>
                                        <td>{{ $field->name }}</td>
                                        <td>{{ ucfirst($field->type) }}</td>
                                        <td class="text-end">
                                            {{-- @can('edit Form builder') --}}
                                                <div class="action-btn bg-info ms-2">
                                                    {{-- <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route("form.field.edit",[$formBuilder->id,$field->id]) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-title="{{__('Form Builder Edit')}}">
                                                        <i class="text-white las la-edit"></i>
                                                    </a> --}}
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center edit-form-field-btn"
                                                    data-id="{{ $field->id }}"
                                                    data-name="{{ $field->name }}"
                                                    data-type="{{ $field->type }}">

                                                    <i class="text-white las la-edit"></i>
                                                </a>
                                                </div>
                                            {{-- @endcan --}}
                                            {{-- @can('delete Form builder') --}}
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['form.field.destroy', [$formBuilder->id,$field->id]]]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}"><i class="text-white las la-trash"></i></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            {{-- @endcan --}}

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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
   // SweetAlert for Edit with Dropdown for Field Type
   $(document).on('click', '.edit-form-field-btn', function() {
        var fieldId = $(this).data('id');
        var currentName = $(this).data('name');
        var currentType = $(this).data('type');

        Swal.fire({
            title: 'Edit Form Field',
            html: `
            <form id="editFormFieldForm">
                <div class="mb-3">
                    <label for="fieldName" class="form-label">Field Name</label>
                    <input type="text" id="fieldName" class="form-control" required placeholder="New Name" value="${currentName}">
                </div>
                <div class="mb-3">
                    <label for="fieldType" class="form-label">Field Type</label>
                    <select id="fieldType" class="form-control" required>
                        <option value="Text" ${currentType === 'Text' ? 'selected' : ''}>Text</option>
                        <option value="Number" ${currentType === 'Number' ? 'selected' : ''}>Number</option>
                        <option value="Email" ${currentType === 'Email' ? 'selected' : ''}>Email</option>
                        <option value="Date" ${currentType === 'Date' ? 'selected' : ''}>Date</option>
                    </select>
                </div>
            </form>
        `,
            showCancelButton: true,
            confirmButtonText: 'Update Details',
            confirmButtonColor: '#3085d6',
            preConfirm: function() {
                var newName = $('#fieldName').val();
                var newType = $('#fieldType').val();

                if (!newName) {
                    Swal.showValidationMessage('Please enter a new name');
                    return false;
                }

                if (!newType) {
                    Swal.showValidationMessage('Please select a field type');
                    return false;
                }

                return {
                    newName: newName,
                    newType: newType
                };
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                var data = result.value;
                updateFieldDetails(fieldId, data.newName, data.newType);
            }
        });
    });

   // SweetAlert for Delete
   $(document).on('click', '.bs-pass-para', function(event) {
        event.preventDefault();
        var form = $(this).closest("form");

        Swal.fire({
            title: "{{ __('Are you sure?') }}",
            text: "{{ __('You won\'t be able to revert this!') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{ __('Yes, delete it!') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

   // Update field details function (replace with actual AJAX/submit functionality as needed)
   function updateFieldDetails(fieldId, newName, newType) {
     console.log(fieldId, newName, newType);
      var builderId = '{{ $formBuilder->id }}';
        $.ajax({
             url: `/admin/form_field/${builderId}/edit/${fieldId}`,
           method: 'POST',
           data: {
               name: newName,
               type: newType,
               _token: '{{ csrf_token() }}'
           },
           success: function(response) {
               Swal.fire(
                   'Updated!',
                   'The form field has been updated successfully.',
                   'success'
               ).then(() => location.reload());
           },
           error: function() {
               Swal.fire(
                   'Error!',
                   'There was an error updating the form field. Please try again.',
                   'error'
               );
           }
       });
   }
</script>

@endsection
