<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
                <div class="d-flex flex-row justify-content-center">
            <h5 class="card-title">Edit Profile</h5>
            
            <?php if($user->latestMembership!=null): ?>
            <a href="<?php echo e(url('membership_certificate')); ?>" class="btn btn-outline-primary ms-auto">Download Membership Certificate</a>
            <?php endif; ?>        
        </div>
    </div>
    <form method="POST" class="needs-validation" action="<?php echo e(url('update_profile')); ?>" enctype="multipart/form-data" novalidate>
        <?php echo csrf_field(); ?>
        <div class="card-body row">
            <h5 class="fs-15 mt-3 text-primary">Personal Details</h5>
            <hr>
            <div class="mb-3 col-md-6">
                <span>Name</span>
                <input type="text" class="form-control" name="name" value="<?php echo e($user->name); ?>" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="mb-3 col-md-6">
                <span>Organisation</span>
                <input type="text" class="form-control" name="organisation" value="<?php echo e($user->organisation); ?>">
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>Gender</span>
                <select class="form-select" name="gender" required>
                    <option value="" selected disabled>Please Select Gender</option>
                    <option value="Male" <?php echo e(($user->gender == "Male") ? 'selected' : ''); ?>>Male</option>
                    <option value="Female" <?php echo e(($user->gender == "Female") ? 'selected' : ''); ?>>Female</option>
                </select>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>DOB</span>
                <input type="date" class="form-control" name="dob" max="<?php echo e(date('Y-m-d',strtotime("-18 year"))); ?>" value="<?php echo e($user->dob); ?>" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>Membership Number</span>
                <input type="text" class="form-control" name="membership_number" value="<?php echo e($user->membership_number); ?>">
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>Address</span>
                <input type="text" class="form-control" name="address" value="<?php echo e($user->address); ?>" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-4 mb-3">
                <span>Phone no.</span>
                <input type="text" class="form-control" name="phone_no" value="<?php echo e($user->phone_no); ?>" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-4 mb-3">
                <span>Alt Phone no.</span>
                <input type="text" class="form-control" name="alt_phone_no" value="<?php echo e($user->alt_phone_no); ?>">
            </div>
             <div class="col-md-4 mb-3">
                <span>Profile Pic</span>
                <input type="file" class="form-control" name="profile_pic">
            </div>
             <div class="col-md-6 mb-3">
                <span>Curriculum Vitae</span>
                <input type="file" class="form-control" name="nok_phone_no" value="<?php echo e($user->nok_phone_no); ?>" >
                <div class="invalid-feedback">* Required</div>
            </div>
            <h5 class="fs-15 mt-3 text-primary">Next Of Kin (NOK) Details</h5>
            <hr>
            <div class="col-md-6 mb-3">
                <span>NOK Name</span>
                <input type="text" class="form-control" name="nok_name" value="<?php echo e($user->nok_name); ?>" required>
                <div class="invalid-feedback">* Required</div>
            </div>
            <div class="col-md-6 mb-3">
                <span>NOK Email</span>
                <input type="text" class="form-control" name="nok_email" value="<?php echo e($user->nok_address); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <span>NOK Phone no.</span>
                <input type="text" class="form-control" name="nok_phone_no" value="<?php echo e($user->nok_phone_no); ?>" required>
                <div class="invalid-feedback">* Required</div>
            </div>
        </div>
        <div class="card-footer text-end">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/staging.ippu.org/resources/views/profile/edit.blade.php ENDPATH**/ ?>