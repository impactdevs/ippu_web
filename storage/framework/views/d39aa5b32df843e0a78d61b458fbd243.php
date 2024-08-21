<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">CPDs</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">CPDs</li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <h5 class="card-title">CPD</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped table-responsive table-hover dataTable">
                <thead role="rowgroup">
                    <tr role="row">
                        <th role='columnheader'>Code</th>
                        <th role='columnheader'>Topic</th>
                        <th role='columnheader'>Points</th>
                        <th role='columnheader'>Dates</th>
                        <th role='columnheader'>Rates</th>
                        <th role='columnheader'>Status</th>
                        <th role='columnheader'>Type</th>
                        <th scope="col" data-label="Actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $cpds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cpd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td data-label="Code"><?php echo e($cpd->code ?: "(blank)"); ?></td>
                        <td data-label="Topic"><?php echo e($cpd->topic ?: "(blank)"); ?></td>
                        <td data-label="Hours"><?php echo e($cpd->points ?: "(blank)"); ?></td>
                        <td data-label="Start Date"><?php echo e(date('F j, Y, g:i a',strtotime($cpd->start_date)) ?: "(blank)"); ?> - <?php echo e(date('F j, Y, g:i a',strtotime($cpd->end_date)) ?: "(blank)"); ?></td>
                        <td data-label="Rate"><?php echo e((($cpd->rate) ? number_format($cpd->rate) : '') ?: "Free"); ?></td>
                        <td data-label="Member Rate"><?php echo e((($cpd->member_rate) ? number_format($cpd->member_rate) : '') ?: "Free"); ?></td>
                        <td data-label="Type"><?php echo e($cpd->type ?: "(blank)"); ?></td>

                        <td data-label="Actions:" class="text-nowrap">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('show CPD')): ?>
                         <a href="<?php echo e(route('cpds.show', compact('cpd'))); ?>" type="button" class="btn btn-primary btn-sm me-1"><?php echo app('translator')->get('Show'); ?></a>
                         <?php endif; ?>
                         <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-cog"></i></button>
                            <ul class="dropdown-menu">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update CPD')): ?>
                                <li><a class="dropdown-item" href="<?php echo e(route('cpds.edit', compact('cpd'))); ?>"><?php echo app('translator')->get('Edit'); ?></a></li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete CPD')): ?>
                                <li>
                                    <form action="<?php echo e(route('cpds.destroy', compact('cpd'))); ?>" method="POST" style="display: inline;" class="m-0 p-0">
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
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create CPD')): ?>
        <a href="<?php echo e(route('cpds.create', [])); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> <?php echo app('translator')->get('Create new Cpd'); ?></a>
        <?php endif; ?>
    </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/admin/cpds/index.blade.php ENDPATH**/ ?>