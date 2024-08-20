@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Account Types</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Account Types</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <ol class="breadcrumb m-0 p-0 flex-grow-1 mb-2 mb-md-0">
                <li class="breadcrumb-item"><a href="{{ implode('/', ['','account_types']) }}"> Account Types</a></li>
            </ol>

            <form action="{{ route('account_types.index', []) }}" method="GET" class="m-0 p-0">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm me-2" name="search" placeholder="Search Account Types..." value="{{ request()->search }}">
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-sm" type="submit"><i class="fa fa-search"></i> @lang('Go!')</button>
                    </span>
                </div>
            </form>
        </div>
        <div class="card-body">
            <table class="table table-striped table-responsive table-hover">
                <thead role="rowgroup">
                    <tr role="row">
                        <th role='columnheader'>Name</th>
                        <th role='columnheader'>Rate</th>
                        <th role='columnheader'>Is Active</th>
                        <th scope="col" data-label="Actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accountTypes as $accountType)
                    <tr>
                        <td data-label="Name">{{ $accountType->name ?: "(blank)" }}</td>
                        <td data-label="Name">{{ $accountType->rate ?: "(blank)" }}</td>
                        <td data-label="Is Active">{{ $accountType->is_active ? "Yes" : "No" }}</td>

                        <td data-label="Actions:" class="text-nowrap">
                            <a href="{{ url('admin/account_types', compact('accountType'))}}" type="button" class="btn btn-primary btn-sm me-1">@lang('Show')</a>
                         <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:void(0);" class="btn btn-primary" data-action="Update" data-id="{{ $accountType->id }}" data-title="Create new class" ajax-load="true" data-url="{{ url('admin/account_types') }}">@lang('Edit')</a></li>
                                <li>
                                    <form action="{{url('admin/account_types', compact('accountType'))}}" method="POST" style="display: inline;" class="m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item">@lang('Delete')</button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $accountTypes->withQueryString()->links() }}
    </div>
    <div class="text-center my-2">
        <a href="javascript:void(0);" class="btn btn-primary" data-action="New" data-id="2323" data-title="Create new Account type" ajax-load="true" data-url="{{ url('admin/account_types') }}"><i class="fa fa-plus"></i> @lang('Create new Account Type')</a>
    </div>
</div>
</div>
@endsection
