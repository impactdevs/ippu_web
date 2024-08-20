
<?php $__env->startSection('content'); ?>
<form class="card" action="<?php echo e(url('communications')); ?>" method="POST">
	<?php echo csrf_field(); ?>
	<div class="card-header">
		<h6>Create new communication</h6>
	</div>
	<div class="card-body row">
		<div class="form-group mb-3 col-md-6">
			<label>Title</label>
			<input type="text" class="form-control" name="title">
		</div>
		<div class="form-group mb-3 col-md-6">
			<label>Target</label>
			<select name="target" class="form-control">
				<option value="*">All Members</option>
				<?php $__currentLoopData = $accountTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accountType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($accountType->id); ?>"><?php echo e($accountType->name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
		<div class="form-group mb-3">
			<label>Message</label>
			<textarea class="ckeditor" name="message"></textarea>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary">Publish Communication</button>
	</div>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/communications/create.blade.php ENDPATH**/ ?>