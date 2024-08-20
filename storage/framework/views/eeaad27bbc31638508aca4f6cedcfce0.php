
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
		<div class="row mb-3">
			<div class="col-md-3">
				<label for="tinTypeFilter">Filter by Member Type</label>
			    <select id="tinTypeFilter" class="form-control form-select">
			        <option value="">All</option>
			        <?php $__currentLoopData = $account_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			        <option value="<?php echo e($account_type->name); ?>"><?php echo e($account_type->name); ?></option>
			        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			    </select>
			</div>
		</div>
		<div class="table-responsive mt-3">
			<table class="table table-striped table-hover" id="members_table">
				<thead>
					<th>Membership No.</th>
					<th>Name</th>
					<th>Type</th>
					<th>Organisation</th>
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
							<th><?php echo e($member?->organisation); ?></th>
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
								<div class="btn-group btn-group-sm">
			                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
			                            <ul class="dropdown-menu">
										<?php if($member?->latestMembership?->status == "Pending"): ?>
															
			                                <li>
			                                	<a class="dropdown-item" href="<?php echo e(url('admin/review_membership/'.$member->id)); ?>">Review Application</a>
			                                </li>
										<?php endif; ?>
											<li>
				                                <button class="dropdown-item btn-danger btn-delete" delete-item-form="delete-member">Delete</button>
				                                <form id="delete-member" action="<?php echo e(route('delete-member',$member->id)); ?>" method="POST" style="display: none;" class="m-0 p-0">
			                                    	<?php echo csrf_field(); ?>
			                                     	<?php echo method_field('DELETE'); ?>
			                                     	 
			                                    </form>
			                                </li>
			                            </ul>
			                        </div>
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
<?php $__env->startSection('customjs'); ?>
<script type="text/javascript">
	$(document).ready(function () {
        var table = $('#members_table').DataTable();

        $('#tinTypeFilter').on('change', function () {
                table.column(2).search($(this).val()).draw();
            });
        });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/members/index.blade.php ENDPATH**/ ?>