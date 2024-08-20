@extends('layouts.app')
@section('content')

@if((!\Auth::user()->latestMembership))
<div class="card p-1">
	<div class="card-body text-center">
		Please subcribe to the membership package to activate account. <a href="{{ url('subscribe') }}" class="btn btn-warning">Subscribe Now</a>
	</div>
</div>
@else
@if(\Auth::user()->latestMembership->status == "Pending")
<div class="alert alert-warning text-center">Your membership is pending approval</div>
@elseif(\Auth::user()->latestMembership->status == "Denied")
<div class="card p-1">
  <div class="card-body text-center">
     Your membership subscription request was rejected due to the following reason<br><br>
     {{ \Auth::user()->latestMembership->comment }}<br><br>
     <a href="{{ url('subscribe') }}" class="btn btn-warning">Subscribe Again</a>
 </div>
</div>
@elseif((\Auth::user()->latestMembership->expiry_date > date('Y-m-d')))
<div class="card p-1">
  <div class="card-body text-center">
     Your membership subscription is expired. <a href="{{ url('subscribe') }}" class="btn btn-danger">Subscribe Now</a>
 </div>
</div>
@endif
@endif

<div class="row">
    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> My Points</p>
                    </div>
                    <div class="flex-shrink-0">
                        <h5 class="text-success fs-14 mb-0">
                            {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ \Auth::user()->points }}">0</span> </h4>
                        {{-- <a href="#" class="text-decoration-underline">View net earnings</a> --}}
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="bx bx-dollar-circle text-success"></i>
                        </span>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-3 col-md-6">
        <!-- card -->
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                       <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Attended CPDs</p>
                   </div>
                   <div class="flex-shrink-0">
                    <h5 class="text-danger fs-14 mb-0">
                        {{-- <i class="ri-arrow-right-down-line fs-13 align-middle"></i> -3.57 % --}}
                    </h5>
                </div>
            </div>
            <div class="d-flex align-items-end justify-content-between mt-4">
                <div>
                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ \Auth::user()->cpd_attendences->count() }}">0</span></h4>
                    {{-- <a href="#" class="text-decoration-underline">View all orders</a> --}}
                </div>
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-soft-info rounded fs-3">
                        <i class="bx bx-shopping-bag text-info"></i>
                    </span>
                </div>
            </div>
        </div><!-- end card body -->
    </div><!-- end card -->
</div><!-- end col -->

<div class="col-xl-3 col-md-6">
    <!-- card -->
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 overflow-hidden">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Attended Events</p>
                </div>
                <div class="flex-shrink-0">
                    <h5 class="text-success fs-14 mb-0">
                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +29.08 % --}}
                    </h5>
                </div>
            </div>
            <div class="d-flex align-items-end justify-content-between mt-4">
                <div>
                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ \Auth::user()->event_attendences->count() }}">0</span> </h4>
                    {{-- <a href="#" class="text-decoration-underline">See details</a> --}}
                </div>
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-soft-warning rounded fs-3">
                        <i class="bx bx-user-circle text-warning"></i>
                    </span>
                </div>
            </div>
        </div><!-- end card body -->
    </div><!-- end card -->
</div><!-- end col -->

<div class="col-xl-3 col-md-6">
    <!-- card -->
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1 overflow-hidden">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Jobs Available</p>
                </div>
                <div class="flex-shrink-0">
                    <h5 class="text-muted fs-14 mb-0">
                        {{-- +0.00 % --}}
                    </h5>
                </div>
            </div>
            <div class="d-flex align-items-end justify-content-between mt-4">
                <div>
                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ rand(1,10) }}">0</span> </h4>
                    {{-- <a href="#" class="text-decoration-underline">Withdraw money</a> --}}
                </div>
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-soft-primary rounded fs-3">
                        <i class="bx bx-wallet text-primary"></i>
                    </span>
                </div>
            </div>
        </div><!-- end card body -->
    </div><!-- end card -->
    </div><!-- end col -->
</div> 
{{-- <div class="card">
	<div class="card-header">
		<h5>My Profile</h5>
	</div>
	<div class="card-body row">
        <div class="col-md-12 mb-3">
            <span>Name</span>
            <input type="text" class="form-control" name="name" value="{{ \Auth::user()->name }}" disabled>
        </div>
        <div class="col-md-6 mb-3">
            <span>Gender</span>
            <select class="form-select" name="gender" disabled>
                <option value="" selected disabled>Please Select Gender</option>
                <option value="Male" {{ (\Auth::user()->gender == "Male") ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ (\Auth::user()->gender == "Female") ? 'selected' : '' }}>Female</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <span>DOB</span>
            <input type="date" class="form-control" name="dob" value="{{ \Auth::user()->dob }}" disabled>
        </div>
        <div class="col-md-6 mb-3">
            <span>Membership Number</span>
            <input type="text" class="form-control" name="membership_number" value="{{ \Auth::user()->membership_number }}" disabled>
        </div>
        <div class="col-md-6 mb-3">
            <span>Address</span>
            <input type="text" class="form-control" name="address" value="{{ \Auth::user()->address }}" disabled>
        </div>
        <div class="col-md-6 mb-3">
            <span>Phone no.</span>
            <input type="text" class="form-control" name="phone_no" value="{{ \Auth::user()->phone_no }}" disabled>
        </div>
        <div class="col-md-6 mb-3">
            <span>Alt Phone no.</span>
            <input type="text" class="form-control" name="alt_phone_no" value="{{ \Auth::user()->alt_phone_no }}" disabled>
        </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ url('profile') }}" class="btn btn-primary">Edit Profile</a>
    </div>
</div> --}}
@endsection