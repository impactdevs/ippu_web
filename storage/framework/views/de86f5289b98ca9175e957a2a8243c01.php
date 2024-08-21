<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Events</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Events</li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <h5 class="card-title">Events</h5>
            <a href="<?php echo e(route('events.create', [])); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo app('translator')->get('Create new Event'); ?></a>
        </div>
        <div class="card-body">
            <table class="table table-striped dataTable table-responsive table-hover">
                <thead role="rowgroup">
                    <tr role="row">
                        <th role='columnheader'>Name</th>
                        <th role='columnheader'>Start Date</th>
                        <th role='columnheader'>End Date</th>
                        <th role='columnheader'>Rate</th>
                        <th role='columnheader'>Member Rate</th>
                        <th scope="col" data-label="Actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td data-label="Name"><?php echo e($event->name ?: "(blank)"); ?></td>
                        <td data-label="Start Date"><?php echo e($event->start_date ? (date('F j, Y, g:i a',strtotime($event->start_date))) : "(blank)"); ?></td>
                        <td data-label="End Date"><?php echo e($event->end_date ? (date('F j, Y, g:i a',strtotime($event->end_date))): "(blank)"); ?></td>
                        <td data-label="Rate"><?php echo e((($event->rate) ? number_format($event->rate) : '') ?: "Free"); ?></td>
                        <td data-label="Member Rate"><?php echo e((($event->member_rate) ? number_format($event->member_rate) : '') ?: "Free"); ?></td>

                        <td data-label="Actions:" class="text-nowrap">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show event')): ?>
                         <a href="<?php echo e(route('events.show', compact('event'))); ?>" type="button" class="btn btn-primary btn-sm me-1"><?php echo app('translator')->get('Show'); ?></a>
                         <?php endif; ?>
                         <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
                            <ul class="dropdown-menu">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update event')): ?>
                                <li><a class="dropdown-item" href="<?php echo e(route('events.edit', compact('event'))); ?>"><?php echo app('translator')->get('Edit'); ?></a></li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete event')): ?>
                                <li>
                                    <form action="<?php echo e(route('events.destroy', compact('event'))); ?>" method="POST" style="display: inline;" class="m-0 p-0">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="dropdown-item"><?php echo app('translator')->get('Delete'); ?></button>
                                    </form>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        
    </div>
    <div class="text-center my-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create event')): ?>
        
        <?php endif; ?>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/staging.ippu.org/resources/views/admin/events/index.blade.php ENDPATH**/ ?>