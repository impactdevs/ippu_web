<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Jobs</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(url('admin/jobs')); ?>">Jobs</a></li>
            <li class="breadcrumb-item active"><?php echo e($job->title); ?></li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(implode('/', ['','jobs'])); ?>"> Jobs</a></li>
                    <li class="breadcrumb-item"><?php echo app('translator')->get('Job'); ?> #<?php echo e($job->id); ?></li>
                </ol>

                <a href="<?php echo e(route('jobs.index', [])); ?>" class="btn btn-light"><i class="fa fa-caret-left"></i> Back</a>
            </div>

            <div class="card-body">
                <table class="table table-striped">
    <tbody>
    <tr>
        <th scope="row">ID:</th>
        <td><?php echo e($job->id); ?></td>
    </tr>
            <tr>
            <th scope="row">Title:</th>
            <td><?php echo e($job->title ?: "(blank)"); ?></td>
        </tr>
            <tr>
            <th scope="row">Description:</th>
            <td><?php echo $job->description ?: "(blank)"; ?></td>
        </tr>
            <tr>
            <th scope="row">Visible From:</th>
            <td><?php echo e($job->visible_from ?: "(blank)"); ?></td>
        </tr>
            <tr>
            <th scope="row">Visible To:</th>
            <td><?php echo e($job->visible_to ?: "(blank)"); ?></td>
        </tr>
            <tr>
            <th scope="row">Deadline:</th>
            <td><?php echo e($job->deadline ?: "(blank)"); ?></td>
        </tr>
            </tbody>
</table>

            </div>

            <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">
                <a href="<?php echo e(route('jobs.edit', compact('job'))); ?>" class="btn btn-info text-nowrap me-1"><i class="fa fa-edit"></i> <?php echo app('translator')->get('Edit'); ?></a>
                <form action="<?php echo e(route('jobs.destroy', compact('job'))); ?>" method="POST" class="m-0 p-0">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger text-nowrap"><i class="fa fa-trash"></i> <?php echo app('translator')->get('Delete'); ?></button>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/jobs/show.blade.php ENDPATH**/ ?>