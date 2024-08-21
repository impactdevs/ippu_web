
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-md-8 col-lg-8 col-xl-8">
		<div class="card">
			<div class="card-header">
				<h5 class="card-title"><?php echo e($event->topic); ?></h5>
			</div>
			<div class="card-body">
				<div>
					<h6>Description</h6>
					<?php echo $event->content; ?>

				</div>
				<div>
					<h6>Target Group</h6>
					<?php echo $event->target_group; ?>

				</div>
			</div>
			<div class="card-footer bg-light">
				<div class="d-flex flex-row align-items-center justify-content-between">
					<div>
						<h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
						<span><?php echo e(date('F j, Y, g:i a',strtotime($event->start_date))); ?></span>
					</div>
					<div>
						<h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
						<span><?php echo e(date('F j, Y, g:i a',strtotime($event->end_date))); ?></span>
					</div>
					<div>
						<h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
						<span><?php echo e(($event->member_rate) ? number_format($event->member_rate) : 'Free'); ?></span>
					</div>
					<div>
						<h6 class="text-warning font-weight-bold fw-medium">Location</h6>
						<span><?php echo e($event->location); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 col-md-4 col-xl-4">
	<!-- Simple card -->
	<div class="">
		<img class="card-img-top img-fluid image" src="<?php echo e(asset('storage/banners/'.$event->banner)); ?>" alt="<?php echo e($event->name); ?>" onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">
		<div class="card-body">
			<div class="text-end mt-2">
				<?php if((($event->start_date >= date('Y-m-d')) || ($event->end_date <= date('Y-m-d'))) && is_null($event->attended)): ?>
				<a href="#"  data-url="<?php echo e(url('attend_cpd/'.$event->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Attend CPD')); ?>" class="btn btn-primary" data-size="lg">Attend</a>
				<?php elseif(!is_null($event->attended)): ?>
				
				<?php if($event->attended->status != "Pending"): ?>
					<a href="<?php echo e(url('cpd_certificate/'.$event->id)); ?>" class="btn btn-primary btn-sm" target="_blank">Certificate</a>
					<a href="<?php echo e(asset('storage/attachments/'.$event->resource_name)); ?>" class="btn btn-warning btn-sm" download>Download Resource</a>
				<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</div><!-- end card -->
</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/members/cpds/details.blade.php ENDPATH**/ ?>