<?php $__env->startSection('page-title'); ?>
<?php echo e(__('Manage Pipelines')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Pipelines</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Pipelines</li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="text-end col-md-12 mb-3">
        <a href="#" data-size="md" data-action="New" data-id="2323" data-title="Create new Pipeline" ajax-load="true" data-url="<?php echo e(url('admin/pipelines')); ?>" title="<?php echo e(__('Create New Pipeline')); ?>" class="btn btn-sm btn-primary">
            <i class="ri-add-fill"></i> Add New Pipeline
        </a>
    </div>
    <div class="">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Pipeline')); ?></th>
                                <th width="250px"><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pipelines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pipeline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($pipeline->name); ?></td>
                                <td class="Action">
                                    <span>
                                        <?php if(count($pipelines) > 1): ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete pipeline')): ?>
                                        <div class="action-btn bg-danger ms-2">
                                            <?php echo Form::open(['method' => 'DELETE', 'route' => ['pipelines.destroy', $pipeline->id]]); ?>

                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"><i class="las la-trash text-white"></i></a>
                                            <?php echo Form::close(); ?>

                                        </div>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                        
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-action="Update" data-id="<?php echo e($pipeline->id); ?>" data-title="Update Pipeline" ajax-load="true" data-url="<?php echo e(url('admin/pipelines')); ?>" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" data-title="<?php echo e(__('Edit Pipeline')); ?>">
                                                <i class="las la-edit text-white"></i> Edit
                                            </a>
                                        

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/pipelines/index.blade.php ENDPATH**/ ?>