
<?php $__env->startSection('content'); ?>
	<div class="card">
		<div class="card-header">
			<?php echo e($communication->title); ?>

		</div>
		<div class="card-body">
			<?php echo $communication->message; ?>

		</div>
		<div class="card-footer">
			Created by: <?php echo e($communication->user->name); ?>

		</div>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/communications/details.blade.php ENDPATH**/ ?>