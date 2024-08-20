<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Account Types</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(url('admin/account_types')); ?>">Account Types</a></li>
            <li class="breadcrumb-item active"><?php echo e($accountType->name); ?></li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h5>Account Type Details</h5>

            <a href="<?php echo e(route('account_types.index', [])); ?>" class="btn btn-light"><i class="fa fa-caret-left"></i> Back</a>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th scope="row">Name:</th>
                        <td><?php echo e($accountType->name ?: "(blank)"); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Is Active:</th>
                        <td><?php echo e($accountType->is_active ? "Yes" : "No"); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Created at</th>
                        <td><?php echo e(Carbon\Carbon::parse($accountType->created_at)->format('d/m/Y H:i:s')); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Updated at</th>
                        <td><?php echo e(Carbon\Carbon::parse($accountType->updated_at)->format('d/m/Y H:i:s')); ?></td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div class="card-footer d-flex flex-column flex-md-row align-items-center justify-content-end">
            <a href="<?php echo e(url('admin/account_types', compact('accountType'))); ?>" class="btn btn-info text-nowrap me-1"><i class="fa fa-edit"></i> <?php echo app('translator')->get('Edit'); ?></a>
            <form action="<?php echo e(url('admin/account_types', compact('accountType'))); ?>" method="POST" class="m-0 p-0">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger text-nowrap"><i class="fa fa-trash"></i> <?php echo app('translator')->get('Delete'); ?></button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/account_types/show.blade.php ENDPATH**/ ?>