<?php $__env->startSection('content'); ?>
<div class="row">
	<?php $__currentLoopData = $cpds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="col-sm-6 col-xl-4">
	<!-- Simple card -->
	<div class="card">
		<img class="card-img-top img-fluid image" src="<?php echo e(asset('storage/banners/'.$event->banner)); ?>" alt="<?php echo e($event->topic); ?>" onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">
		<div class="card-body">
			<h4 class="card-title mb-2">
				<a href="<?php echo e(url('cpd_details/'.$event->id)); ?>"><?php echo e($event->topic); ?></a>
			</h4>
			<div class="card-text">
				<div>
					<div class="bg-light p-2"> 
						<span class="text-primary "><?php echo e(number_format($event->attendences->count())); ?> Attendees</span>
						<span class="mx-1"> -</span>
						<span class="text-danger font-weight-bold fw-medium"><?php echo e(number_format($event->member_rate)); ?></span>
						<span class="text-primary ml-1 font-weight-bold fw-medium">
							(<?php echo e(date('F j, Y, g:i a',strtotime($event->start_date)).' - '.date('F j, Y, g:i a',strtotime($event->end_date))); ?>)
						</span>
					</div>
				</div>
			</div>
		
		</div>
	</div><!-- end card -->
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/staging.ippu.org/resources/views/members/cpds/index.blade.php ENDPATH**/ ?>