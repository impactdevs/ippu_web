<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Events</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(url('admin/events')); ?>">Events</a></li>
            <li class="breadcrumb-item active"><?php echo e($event->name); ?> - Edit</li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h5 class="card-title">Edit Event Details</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('events.update', compact('event'))); ?>" method="POST" class="m-0 p-0" enctype="multipart/form-data">
                <?php echo method_field('PUT'); ?>
                <?php echo csrf_field(); ?>
                <div class="card-body row">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo e(@old('name', $event->name)); ?>" />
                        <?php if($errors->has('name')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('name')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="<?php echo e(@old('start_date', $event->start_date)); ?>" />
                        <?php if($errors->has('start_date')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('start_date')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="<?php echo e(@old('end_date', $event->end_date)); ?>" />
                        <?php if($errors->has('end_date')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('end_date')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="rate" class="form-label">Rate:</label>
                        <input type="text" name="rate" id="rate" class="form-control number_format" value="<?php echo e(@old('rate', ($event->rate))); ?>" />
                        <?php if($errors->has('rate')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('rate')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="member_rate" class="form-label">Member Rate:</label>
                        <input type="text" name="member_rate" id="member_rate" class="form-control number_format" value="<?php echo e(@old('member_rate', ($event->member_rate))); ?>" />
                        <?php if($errors->has('member_rate')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('member_rate')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="points" class="form-label">CPD Points:</label>
                        <input type="number" name="points" id="points" class="form-control" value="<?php echo e(@old('points', ($event->points))); ?>" />
                        <?php if($errors->has('points')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('points')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="attachment_name" class="form-label">Attachment:</label>
                        <input type="file" name="attachment_name" id="attachment_name" class="form-control" value="<?php echo e(@old('attachment_name', $event->attachment_name)); ?>" />
                        <?php if($errors->has('attachment_name')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('attachment_name')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="banner_name" class="form-label">Banner:</label>
                        <input type="file" name="banner_name" id="banner_name" class="form-control" value="<?php echo e(@old('banner_name', $event->banner_name)); ?>" />
                        <?php if($errors->has('banner_name')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('banner_name')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-lg-12">
                        <label for="banner_name" class="form-label">Details:</label>
                        <textarea class="ckeditor" name="details"><?php echo e(@old('details',$event->details)); ?></textarea>
                        <?php if($errors->has('details')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('details')); ?></div>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="card-footer">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <a href="<?php echo e(route('events.index', [])); ?>" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Update Event'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/staging.ippu.org/resources/views/admin/events/edit.blade.php ENDPATH**/ ?>