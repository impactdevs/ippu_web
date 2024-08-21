<?php $__env->startSection('content'); ?>
<?php if(sizeof($experiences) < 1): ?>
<div class="text-center alert alert-danger">Please add Education Background</div>
<?php endif; ?>

<div>
	<div class="text-end mb-3">
		<button class="btn btn-primary" data-action="New" data-id="2323" data-title="Add New Background" ajax-load="true" data-url="<?php echo e(url('education')); ?>">Add Education Background</button>
	</div>

	<div class="row">
		<?php $__currentLoopData = $experiences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $experience): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<div class="col-md-6 col-lg-6">
			<div class="cad">
				<div class="card ribbon-box border shadow-none">
					<div class="card-body">
						<div class="ribbon ribbon-primary round-shape"><?php echo e($experience->title); ?></div>

						<h5 class="fs-14 text-end"><?php echo e(date('M Y',strtotime($experience->start_date))); ?> - <?php echo e(date('M Y',strtotime($experience->end_date))); ?></h5>

						<h5><?php echo e($experience->field); ?> <?php echo e(($experience->points) ? "(".$experience->points.")" : ''); ?></h5>
						<div class="ribbon-content mt-4 text-muted">
							<?php echo $experience->description; ?>

						</div>
					</div>
					<div class="text-end card-footer">
						<a href="javascript:void(0);" data-action="Update" data-id="<?php echo e($experience->id); ?>" data-title="Edit Background" ajax-load="true" data-url="<?php echo e(url('education')); ?>" class="text-primary mr-3">Edit</a>
						
						<form action="<?php echo e(url('education', $experience->id)); ?>" method="POST" id="form_delete_<?php echo e($experience->id); ?>" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <a href="javascript:void(0)" type="submit" onclick="$('#form_delete_<?php echo e($experience->id); ?>').submit()" class="text-danger ml-2">Delete</a>
                                    </form>
					</div>
				</div>
			</div>
			
		</div>
		
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/staging.ippu.org/resources/views/members/education/index.blade.php ENDPATH**/ ?>