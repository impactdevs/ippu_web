<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">CPDs</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(url('admin/cpds')); ?>">CPDs</a></li>
            <li class="breadcrumb-item active"><?php echo e($cpd->topic); ?></li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="card">
    <div class="card-body">
        <p class="text-muted"><?php echo e($cpd->topic); ?></p>
        <!-- Nav tabs -->
        <ul class="nav nav-pills nav-justified mb-3 bg-light" role="tablist">
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link active" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab">
                    Details
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab">
                    Pending Confirmation (<?php echo e($cpd?->pending_confimation?->count()); ?>)
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-messages-1" role="tab">
                    Confirmed (<?php echo e($cpd?->confirmed?->count()); ?>)
                </a>
            </li>
            <li class="nav-item waves-effect waves-light">
                <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-settings-1" role="tab">
                    Attended (<?php echo e($cpd?->attended_event?->count()); ?>)
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content text-muted">
            <div class="tab-pane active" id="pill-justified-home-1" role="tabpanel">
                <div class="row">
                        <div class="col-md-7">
                            <?php echo $cpd->content; ?>

                            <div class="p-1 mb-2 bg-light">
                                <div class="mb-3"><?php echo e($cpd->target_group); ?></div>
                                <div class="mb-3 d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">Location</h6>
                                        <span><?php echo e($cpd->location); ?></span>
                                    </div>
                                    <?php if($cpd->points): ?>
                                    <div>
                                        <h6 class="text-warning font-weight-bold fw-medium">Points</h6>
                                        <span><?php echo e($cpd->points); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
                                        <span><?php echo e(date('F j, Y, g:i a',strtotime($cpd->start_date))); ?></span>
                                    </div>
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
                                        <span><?php echo e(date('F j, Y, g:i a',strtotime($cpd->end_date))); ?></span>
                                    </div>
                                    <div>
                                        <h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
                                        <span><?php echo e(($cpd->member_rate) ? number_format($cpd->member_rate) : 'Free'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <a href="<?php echo e(asset('storage/attachments/'.$cpd->resource)); ?>" class="btn btn-warning btn-sm" download>Download Resource</a>
                            <a href="<?php echo e(url('generate_qr/cpd/'.$cpd->id)); ?>" class="btn btn-danger btn-sm ms-4">Generate QR Code</a>
                        </div>
                        <div class="col-md-5">
                            <img class="card-img-top img-fluid image" src="<?php echo e(asset('storage/banners/'.$cpd->banner)); ?>" alt="<?php echo e($cpd->topic); ?>" onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">
                            
                        </div>
                    </div>
            </div>
            <div class="tab-pane" id="pill-justified-profile-1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable">
                            <thead>
                                <th>Name</th>
                                <th>Contacts</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cpd->pending_confimation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($attendence->user->name); ?></td>
                                    <td><?php echo e($attendence->user->phone_no); ?></td>
                                    <td><?php echo e($attendence->user->email); ?></td>
                                    <td>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve CPD attendence')): ?>
                                        <a href="<?php echo e(url('admin/cpds/attendence/'.$attendence->id.'/Confirmed')); ?>" class="btn btn-sm btn-primary">
                                            Book Attendence
                                        </a>

                                        <a href="<?php echo e(url('admin/cpds/attendence/'.$attendence->id.'/Attended')); ?>" class="btn btn-sm btn-danger">
                                            Confirm Attendence
                                        </a>

                                        <?php if($attendence->payment_proof): ?>
                                        <a href="javascript:void(0);" class="btn-sm btn btn-warning" data-url="<?php echo e(url('admin/view_payment_proof/'.$attendence->payment_proof)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Payment Proof')); ?>" class="btn btn-primary" data-size="lg">View Payment Proof</a>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="pill-justified-messages-1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable">
                            <thead>
                                <th>Name</th>
                                <th>Contacts</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cpd->confirmed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($attendence->user->name); ?></td>
                                    <td><?php echo e($attendence->user->phone_no); ?></td>
                                    <td><?php echo e($attendence->user->email); ?></td>
                                    <td>
                                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve CPD attendence')): ?>
                                        <a href="<?php echo e(url('admin/cpds/attendence/'.$attendence->id.'/Attended')); ?>" class="btn btn-sm btn-primary">
                                            Confirm Attendence
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="pill-justified-settings-1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable">
                            <thead>
                                <th>Name</th>
                                <th>Contacts</th>
                                <th>Email</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cpd?->attended_event; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($attendence->user->name); ?></td>
                                    <td><?php echo e($attendence->user->phone_no); ?></td>
                                    <td><?php echo e($attendence->user->email); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div><!-- end card-body -->
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/cpds/show.blade.php ENDPATH**/ ?>