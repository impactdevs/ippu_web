<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Events</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(url('admin/events')); ?>">Events</a></li>
            <li class="breadcrumb-item active">Create Event</li>
        </ol>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h5 class="card-title">Create New Event</h5>
        </div>

        <div class="card-body">
            <form action="<?php echo e(route('events.store')); ?>" method="POST" class="m-0 p-0" enctype="multipart/form-data">
                <div class="card-body row">
                    <?php echo csrf_field(); ?>

                    <!-- Event Type Selection -->
                    <div class="mb-3 col-lg-12">
                        <label for="event_type" class="form-label">Event Type:</label>
                        <select id="event_type" name="event_type" class="form-control">
                            <option value="Normal" selected>Normal</option>
                            <option value="Annual">Annual</option>
                        </select>
                        <?php if($errors->has('event_type')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('event_type')); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Common Fields (Visible by Default) -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo e(@old('name')); ?>" />
                        <?php if($errors->has('name')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('name')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="<?php echo e(@old('start_date')); ?>" />
                        <?php if($errors->has('start_date')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('start_date')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="<?php echo e(@old('end_date')); ?>" />
                        <?php if($errors->has('end_date')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('end_date')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-lg-4">
                        <label for="rate" class="form-label">Rate:</label>
                        <input type="text" name="rate" id="rate" class="form-control number_format" value="<?php echo e(@old('rate')); ?>" />
                        <?php if($errors->has('rate')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('rate')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-lg-4">
                        <label for="member_rate" class="form-label">Member Rate:</label>
                        <input type="text" name="member_rate" id="member_rate" class="form-control number_format" value="<?php echo e(@old('member_rate')); ?>" />
                        <?php if($errors->has('member_rate')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('member_rate')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-lg-4">
                        <label for="points" class="form-label">CPD Points</label>
                        <input type="number" name="points" id="points" class="form-control" value="<?php echo e(@old('points')); ?>" />
                        <?php if($errors->has('points')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('points')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="attachment_name" class="form-label">Attachment:</label>
                        <input type="file" name="attachment_name" id="attachment_name" class="form-control" value="<?php echo e(@old('attachment_name')); ?>" />
                        <?php if($errors->has('attachment_name')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('attachment_name')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-lg-6">
                        <label for="banner_name" class="form-label">Banner:</label>
                        <input type="file" name="banner_name" id="banner_name" class="form-control" value="<?php echo e(@old('banner_name')); ?>" />
                        <?php if($errors->has('banner_name')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('banner_name')); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 col-lg-12">
                        <label for="details" class="form-label">Details:</label>
                        <textarea class="ckeditor" name="details"><?php echo e(@old('details')); ?></textarea>
                        <?php if($errors->has('details')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('details')); ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Fields for Annual Events (Hidden by Default) -->
                    <div id="annual_event_fields" style="display: none;">
                        <div class="mb-3 col-lg-12">
                            <label for="theme" class="form-label">Theme:</label>
                            <input type="text" name="theme" id="theme" class="form-control" value="<?php echo e(@old('theme')); ?>" />
                            <?php if($errors->has('theme')): ?>
                            <div class='error small text-danger'><?php echo e($errors->first('theme')); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3 col-lg-12">
                            <label for="organizing_committee" class="form-label">Organizing Committee:</label>
                            <input type="text" name="organizing_committee" id="organizing_committee" class="form-control" value="<?php echo e(@old('organizing_committee')); ?>" />
                            <?php if($errors->has('organizing_committee')): ?>
                            <div class='error small text-danger'><?php echo e($errors->first('organizing_committee')); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3 col-lg-12">
                            <label for="annual_event_date" class="form-label">Date:</label>
                            <input type="date" name="annual_event_date" id="annual_event_date" class="form-control" value="<?php echo e(@old('annual_event_date')); ?>" />
                            <?php if($errors->has('annual_event_date')): ?>
                            <div class='error small text-danger'><?php echo e($errors->first('annual_event_date')); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3 col-lg-12">
                            <label for="place" class="form-label">Place:</label>
                            <input type="text" name="place" id="place" class="form-control" value="<?php echo e(@old('place')); ?>" />
                            <?php if($errors->has('place')): ?>
                            <div class='error small text-danger'><?php echo e($errors->first('place')); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <a href="<?php echo e(route('events.index')); ?>" class="btn btn-light"><?php echo app('translator')->get('Cancel'); ?></a>
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Create new Event'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startSection('customjs'); ?>
<script>
    // Show/Hide fields based on event type selection
    document.getElementById('event_type').addEventListener('change', function() {
        var eventType = this.value;
        var annualFields = document.getElementById('annual_event_fields');

        if (eventType === 'Annual') {
            annualFields.style.display = 'block';
        } else {
            annualFields.style.display = 'none';
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/katendenicholas/Desktop/laravel/ippu_web/resources/views/admin/events/create.blade.php ENDPATH**/ ?>