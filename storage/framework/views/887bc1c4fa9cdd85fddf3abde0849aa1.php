<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Events</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(url('admin/events')); ?>">Events</a></li>
            <li class="breadcrumb-item active"><?php echo e($event->name); ?></li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    
    <div class="card">
        <div class="card-body">
            <h4 class=""><?php echo e($event->name); ?></h4>
            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-justified mb-3 bg-light" role="tablist">
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab">
                        Details
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab">
                        Pending Confirmation (<?php echo e($event->pending_confimation->count()); ?>)
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-messages-1" role="tab">
                        Confirmed (<?php echo e($event->confirmed->count()); ?>)
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-settings-1" role="tab">
                        Attended (<?php echo e($event->attended_event->count()); ?>)
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content text-muted">
                <div class="tab-pane active" id="pill-justified-home-1" role="tabpanel">
                    <div class="row">
                        <div class="col-md-7">
                            <?php echo $event->details; ?>

                            <div class="p-1 mb-2 bg-light">
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
                                        <span><?php echo e(date('F j, Y, g:i a',strtotime($event->start_date))); ?></span>
                                    </div>
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
                                        <span><?php echo e(date('F j, Y, g:i a',strtotime($event->end_date))); ?></span>
                                    </div>
                                    <div>
                                        <h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
                                        <span><?php echo e(($event->member_rate) ? number_format($event->member_rate) : 'Free'); ?></span>
                                    </div>
                                    <?php if($event->points): ?>
                                    <div>
                                        <h6 class="text-warning font-weight-bold fw-medium">Points</h6>
                                        <span><?php echo e($event->points); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="<?php echo e(asset('storage/attachments/'.$event->attachment_name)); ?>" class="btn btn-warning btn-sm" download>Download Resource</a>
                             <a href="<?php echo e(url('generate_qr/event/'.$event->id)); ?>" class="btn btn-danger btn-sm ms-4">Generate QR Code</a>
                        </div>
                        <div class="col-md-5">
                            <img class="card-img-top img-fluid image" src="<?php echo e(asset('storage/banners/'.$event->banner_name)); ?>" alt="<?php echo e($event->name); ?>" onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">
                            
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
                                <?php $__currentLoopData = $event->pending_confimation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($attendence?->user?->name); ?></td>
                                    <td><?php echo e($attendence?->user?->phone_no); ?></td>
                                    <td><?php echo e($attendence?->user?->email); ?></td>
                                    <td>
                                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve event attendence')): ?>
                                        <a href="<?php echo e(url('admin/events/attendence/'.$attendence->id.'/Confirmed')); ?>" class="btn btn-sm btn-primary">
                                            Book Attendence
                                        </a>

                                        <a href="<?php echo e(url('admin/events/attendence/'.$attendence->id.'/Attended')); ?>" class="btn btn-sm btn-danger">
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
                                <?php $__currentLoopData = $event->confirmed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($attendence?->user?->name); ?></td>
                                    <td><?php echo e($attendence?->user?->phone_no); ?></td>
                                    <td><?php echo e($attendence?->user?->email); ?></td>
                                    <td>
                                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve event attendence')): ?>
                                        <a href="<?php echo e(url('admin/events/attendence/'.$attendence->id.'/Attended')); ?>" class="btn btn-sm btn-primary">
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
                                <?php $__currentLoopData = $event->attended_event; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($attendence?->user?->name); ?></td>
                                    <td><?php echo e($attendence?->user?->phone_no); ?></td>
                                    <td><?php echo e($attendence?->user?->email); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- end card-body -->
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/staging.ippu.org/resources/views/admin/events/show.blade.php ENDPATH**/ ?>