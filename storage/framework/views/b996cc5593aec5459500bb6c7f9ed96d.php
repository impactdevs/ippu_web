
<?php $__env->startSection('content'); ?>
<div class="card">
				<div class="card-header d-flex flex-row align-items-center justify-content-between">
					<h5><?php echo e($job->title); ?></h5>
					
				</div>
				<div class="card-body">
					<?php echo $job->description; ?>

				</div>
				<div class="card-footer">
					Deadline: <?php echo e(date('d M, Y',strtotime($job->deadline))); ?>

				</div>
			</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/members/jobs/details.blade.php ENDPATH**/ ?>