
<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <?php if($type == "cpd"): ?>
    <h4 class="mb-sm-0">CPDs</h4>
    <?php else: ?>
    <h4 class="mb-sm-0">Events</h4>
    <?php endif; ?>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <?php if($type == "cpd"): ?>
            <li class="breadcrumb-item"><a href="<?php echo e(url('admin/cpds')); ?>">CPDs</a></li>
            <?php else: ?>
			<li class="breadcrumb-item"><a href="<?php echo e(url('admin/events')); ?>">Events</a></li>
            <?php endif; ?>
            <li class="breadcrumb-item active">Create Reminder</li>
        </ol>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<form class="card" method="POST" action="<?php echo e(url('admin/send_reminder')); ?>">
	<?php echo csrf_field(); ?>
	<input type="hidden" name="type" value="<?php echo e($type); ?>">
	<div class="card-header">
		<h5>Create New Reminder</h5>
	</div>
	<div class="card-body row">
		<div class="form-group mb-3">
			<label>Subject</label>
			<input type="text" class="form-control" name="subject">
		</div>
		<div class="form-group mb-3">
			<label>Message</label>
			<textarea class="ckeditor" name="message"></textarea>
		</div>
		<div class="col-md-6">
			<?php if($type == "cpd"): ?>
				<div class="form-group">
					<label>CPD</label>
					<select class="form-control" name="cpd" required>
						<option value="" selected disabled>Please select a cpd</option>
						<?php $__currentLoopData = $cpds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cpd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($cpd->id); ?>"><?php echo e($cpd->topic); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
			<?php else: ?>
				<div class="form-group">
					<label>Event</label>
					<select class="form-control" name="event" required>
						<option value="" selected disabled>Please select a event</option>
						<?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cpd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($cpd->id); ?>"><?php echo e($cpd->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
			<?php endif; ?>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label>Status</label>
				<select class="form-control" name="status">
					<option value="Pending">Pending</option>
					<option value="Attended">Attended</option>
				</select>
			</div>
		</div>
	</div>
	<div class="card-footer text-end">
		<button class="btn btn-info" type="submit">Send Reminder</button>
	</div>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/reminders/create.blade.php ENDPATH**/ ?>