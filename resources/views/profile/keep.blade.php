@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex flex-row justify-content-center">
            <h5 class="card-title">Edit Profile</h5>
            {{-- add a download membership certificate button --}}
            @if($user->latestMembership != null)
            <a href="{{ url('membership_certificate') }}" class="btn btn-outline-primary ms-auto">Download Membership Certificate</a>
            @endif
        </div>
    </div>
    <form id="profileForm" method="POST" class="needs-validation" action="{{ url('update_profile') }}" enctype="multipart/form-data" novalidate>
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
                <input type="text" class="form-control" name="membership_number" value="{{ $user->membership_number }}" pattern="^[A-Z0-9]{5,10}$" required>
                <div class="invalid-feedback">* Membership number must be 5-10 alphanumeric characters in uppercase.</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>Address</span>
                <input type="text" class="form-control" name="address" value="{{ $user->address }}" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-4 mb-3">
                <span>Phone no.</span>
                <input type="text" class="form-control" name="phone_no" value="{{ $user->phone_no }}" pattern="^\+?[0-9]{10,15}$" required>
                <div class="invalid-feedback">* Phone number must be between 10-15 digits and may start with a +.</div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>
</div>

<script>
    (function () {
        'use strict';
        // Fetch the form we want to apply custom validation to
        var form = document.getElementById('profileForm');
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    })();
</script>
@endsection
