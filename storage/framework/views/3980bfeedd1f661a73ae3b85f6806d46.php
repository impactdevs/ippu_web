
<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Members</h4>
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Members</li>
        </ol>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
	
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover dataTable">
				<thead>
					<th>Membership No.</th>
					<th>Name</th>
					<th>Type</th>
					<th>Contacts</th>
					<th>Status</th>
					<th>Actions</th>
				</thead>
				<tbody>
					<?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($member->membership_number); ?></td>
							<td><?php echo e($member->name); ?></td>
							<td><?php echo e($member?->account_type?->name); ?></td>
							<td><?php echo e($member->phone_no); ?></td>
							<td>
								<?php if($member?->subscribed == 1): ?>
									<span class="badge bg-success">Fully paid</span>
								<?php else: ?>
									<span class="badge bg-danger">Not-paid</span>
								<?php endif; ?>
							</td>
							<td>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('members')): ?>
								<a href="<?php echo e(url('admin/members/'.$member->id)); ?>" class="btn btn-sm btn-primary">Show</a>
								<?php endif; ?>
								<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve members')): ?>
								<?php if($member?->latestMembership?->status == "Pending"): ?>
									
									<a href="<?php echo e(url('admin/review_membership/'.$member->id)); ?>" class="btn btn-danger btn-sm">Review Application</a>
								<?php endif; ?>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/admin/members/index.blade.php ENDPATH**/ ?>