
<?php $__env->startSection('content'); ?>
<div class="text-end mb-3">
	<?php if(\Auth::user()->user_type == "Admin"): ?>
	<a href="<?php echo e(url('communications/create')); ?>" class="btn btn-primary btn-sm">Create New Communication</a>
	<?php endif; ?>
</div>
<div class="row">
	<?php $__currentLoopData = $communications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $communication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<div class=" col-md-6">
		<div class="card">
			<div class="card-header">
				<a href="<?php echo e(url('communications/'.$communication->id)); ?>"><?php echo e($communication->title); ?></a>
			</div>
			<div class="card-body">
				<div class="text-truncate-two-lines mb-3">
					<?php echo $communication->message; ?>

				</div>
			</div>
			<?php if($communication->user_id == \Auth::user()->id): ?>
			<div class="card-footer text-end">
				<a href="<?php echo e(url('communications/'.$communication->id.'/edit')); ?>">Edit</a>

				<form action="<?php echo e(route('communications.destroy', $communication->id)); ?>" method="POST" style="display: inline;" class="m-0 p-0">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="dropdown-item"><?php echo app('translator')->get('Delete'); ?></button>
                                    </form>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/communications/index.blade.php ENDPATH**/ ?>