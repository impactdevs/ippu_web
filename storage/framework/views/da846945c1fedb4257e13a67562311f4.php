<?php $__env->startSection('content'); ?>
<div class="card">
	<div class="card-header">
		<h5>Users</h5>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover dataTable">
				<thead>
					<th>Name</th>
					<th>Email</th>
					<th>Action(s)</th>
				</thead>
				<tbody>
					<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td><?php echo e($user->name); ?></td>
						<td><?php echo e($user->email); ?></td>
						<td>
							<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('make admin')): ?>
								<a href="<?php echo e(url('admin/change_account_type/Member/'.$user->id)); ?>" class="btn btn-danger btn-sm">Make Member</a>
							<?php endif; ?>
							<a href="javascript:void(0)" class="btn btn-primary btn-sm ms-2" data-url="<?php echo e(url('admin/edit_user/'.$user->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Edit User')); ?>" data-size="lg" title="<?php echo e(__('Edit User Details')); ?>" data-title="<?php echo e(__('Edit User Details')); ?>" >Edit</a>
						</td>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/admin/users/index.blade.php ENDPATH**/ ?>