@extends('layouts.app')
@section('breadcrumb')
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Account Types</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ url('admin/account_types') }}">Account Types</a></li>
            <li class="breadcrumb-item active">{{ $accountType->name }}</li>
        </ol>
    </div>

</div>
@endsection
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h5>Account Type Details</h5>

            <a href="{{ route('account_types.index', []) }}" class="btn btn-light"><i class="fa fa-caret-left"></i> Back</a>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th scope="row">Name:</th>
                        <td>{{ $accountType->name ?: "(blank)" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Is Active:</th>
                        <td>{{ $accountType->is_active ? "Yes" : "No" }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Created at</th>
                        <td>{{Carbon\Carbon::parse($accountType->created_at)->format('d/m/Y H:i:s')}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Updated at</th>
                        <td>{{Carbon\Carbon::parse($accountType->updated_at)->format('d/m/Y H:i:s')}}</td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">
            <a href="{{ url('admin/account_types', compact('accountType')) }}" class="btn btn-info text-nowrap me-1"><i class="fa fa-edit"></i> @lang('Edit')</a>
            <form action="{{ url('admin/account_types', compact('accountType')) }}" method="POST" class="m-0 p-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger text-nowrap"><i class="fa fa-trash"></i> @lang('Delete')</button>
            </form>
        </div>
    </div>
</div>
@endsection
