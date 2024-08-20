<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Custom Field')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Custom Fields</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
            <li class="breadcrumb-item"><?php echo e(__('Custom Field')); ?></li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<div class="text-end mb-3">
        
            <a href="#" data-url="<?php echo e(url('admin/custom-fields/create')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Custom Field')); ?>" class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i> Create New Custom Field
            </a>
        
    </div>
    <div class="row">
        <div class="col-3">
            
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Custom Field')); ?></th>
                                <th> <?php echo e(__('Type')); ?></th>
                                <th> <?php echo e(__('Module')); ?></th>
                                <th width="10%"> <?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $custom_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($field->name); ?></td>
                                    <td><?php echo e($field->type); ?></td>
                                    <td><?php echo e($field->module); ?></td>
                                    
                                        <td class="Action">
                                            <span>
                                            
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="<?php echo e(url('admin/custom-fields/'.$field->id.'/edit')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Custom Field')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" title="<?php echo e(__('Edit')); ?>">
                                                    <i class="las la-edit text-white"></i>
                                                </a>
                                                    </div>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete constant custom field')): ?>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <?php echo Form::open(['method' => 'DELETE', 'url' => ['custom-field.destroy', $field->id],'id'=>'delete-form-'.$field->id]); ?>

                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($field->id); ?>').submit();">
                                                        <i class="las la-trash text-white"></i>
                                                    </a>
                                                        <?php echo Form::close(); ?>

                                                    </div>
                                                <?php endif; ?>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/customFields/index.blade.php ENDPATH**/ ?>