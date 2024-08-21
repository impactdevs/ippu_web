
<?php $__env->startSection('content'); ?>

<?php if((!\Auth::user()->latestMembership)): ?>
<div class="card p-1">
	<div class="card-body text-center">
		Please subcribe to the membership package to activate account. <a href="<?php echo e(url('subscribe')); ?>" class="btn btn-warning">Subscribe Now</a>
	</div>
</div>
<?php else: ?>
<?php if(\Auth::user()->latestMembership->status == "Pending"): ?>
<div class="alert alert-warning text-center">Your membership is pending approval</div>
<?php elseif(\Auth::user()->latestMembership->status == "Denied"): ?>
<div class="card p-1">
  <div class="card-body text-center">
     Your membership subscription request was rejected due to the following reason<br><br>
     <?php echo e(\Auth::user()->latestMembership->comment); ?><br><br>
     <a href="<?php echo e(url('subscribe')); ?>" class="btn btn-warning">Subscribe Again</a>
 </div>
</div>
<?php elseif((\Auth::user()->latestMembership->expiry_date > date('Y-m-d'))): ?>
<div class="card p-1">
  <div class="card-body text-center">
     Your membership subscription is expired. <a href="<?php echo e(url('subscribe')); ?>" class="btn btn-danger">Subscribe Now</a>
 </div>
</div>
<?php endif; ?>
<?php endif; ?>

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
                            
                        </h5>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo e(\Auth::user()->points); ?>">0</span> </h4>
                        
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
                        
                    </h5>
                </div>
            </div>
            <div class="d-flex align-items-end justify-content-between mt-4">
                <div>
                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo e(\Auth::user()->cpd_attendences->count()); ?>">0</span></h4>
                    
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
                        
                    </h5>
                </div>
            </div>
            <div class="d-flex align-items-end justify-content-between mt-4">
                <div>
                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo e(\Auth::user()->event_attendences->count()); ?>">0</span> </h4>
                    
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
                        
                    </h5>
                </div>
            </div>
            <div class="d-flex align-items-end justify-content-between mt-4">
                <div>
                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="<?php echo e(rand(1,10)); ?>">0</span> </h4>
                    
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/members/dashboard.blade.php ENDPATH**/ ?>