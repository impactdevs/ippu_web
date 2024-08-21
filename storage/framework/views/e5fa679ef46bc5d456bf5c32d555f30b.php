
<?php $__env->startSection('content'); ?>
<div class="row">
	<?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<div class="col-md-6">
			<div class="card">
				<div class="card-header d-flex flex-row align-items-center justify-content-between">
					<h5><?php echo e($job->title); ?></h5>
					<a href="<?php echo e(url('jobs/'.$job->id)); ?>" class="badge bg-success">View</a>
				</div>
				<div class="card-body text-truncate-two-lines">
					<?php echo $job->description; ?>

				</div>
				<div class="card-footer">
					Deadline: <?php echo e(date('d M, Y',strtotime($job->deadline))); ?>

				</div>
			</div>
		</div>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/members/jobs/index.blade.php ENDPATH**/ ?>