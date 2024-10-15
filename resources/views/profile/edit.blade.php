@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
                <div class="d-flex flex-row justify-content-center">
            <h5 class="card-title">Edit Profile</h5>
            {{-- add a download membership certificate button --}}
            @if($user->latestMembership!=null)
            <a href="{{ url('membership_certificate') }}" class="btn btn-outline-primary ms-auto">Download Membership Certificate</a>
            @endif        
        </div>
    </div>
    <form method="POST" class="needs-validation" action="{{ url('update_profile') }}" enctype="multipart/form-data" novalidate>
        @csrf
        <div class="card-body row">
            <h5 class="fs-15 mt-3 text-primary">Personal Details</h5>
            <hr>
            <div class="mb-3 col-md-6">
                <span>Name</span>
                <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="mb-3 col-md-6">
                <span>Organisation</span>
                <input type="text" class="form-control" name="organisation" value="{{ $user->organisation }}">
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>Gender</span>
                <select class="form-select" name="gender" required>
                    <option value="" selected disabled>Please Select Gender</option>
                    <option value="Male" {{ ($user->gender == "Male") ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ ($user->gender == "Female") ? 'selected' : '' }}>Female</option>
                </select>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>DOB</span>
                <input type="date" class="form-control" name="dob" max="{{ date('Y-m-d',strtotime("-18 year")) }}" value="{{ $user->dob }}" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>Membership Number</span>
                <input type="text" class="form-control" name="membership_number" value="{{ $user->membership_number }}">
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>Address</span>
                <input type="text" class="form-control" name="address" value="{{ $user->address }}" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-4 mb-3">
                <span>Phone no.</span>
                <input type="text" class="form-control" name="phone_no" value="{{ $user->phone_no }}" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-4 mb-3">
                <span>Alt Phone no.</span>
                <input type="text" class="form-control" name="alt_phone_no" value="{{ $user->alt_phone_no }}">
            </div>
             <div class="col-md-4 mb-3">
                <span>Profile Pic</span>
                <input type="file" class="form-control" name="profile_pic">
            </div>
             <div class="col-md-6 mb-3">
                <span>Curriculum Vitae</span>
                <input type="file" class="form-control" name="nok_phone_no" value="{{ $user->nok_phone_no }}" >
                <div class="invalid-feedback">* Required</div>
            </div>
            <h5 class="fs-15 mt-3 text-primary">Next Of Kin (NOK) Details</h5>
            <hr>
            <div class="col-md-6 mb-3">
                <span>NOK Name</span>
                <input type="text" class="form-control" name="nok_name" value="{{ $user->nok_name }}" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>NOK Email</span>
                <input type="text" class="form-control" name="nok_email" value="{{ $user->nok_address }}">
            </div>
            <div class="col-md-6 mb-3">
                <span>NOK Phone no.</span>
                <input type="text" class="form-control" name="nok_phone_no" value="{{ $user->nok_phone_no }}" required>
                <div class="invalid-feedback">* Required</div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>
</div>
@endsection