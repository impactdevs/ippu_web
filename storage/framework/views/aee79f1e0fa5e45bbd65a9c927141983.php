
<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Reports</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Member Reports</li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<form class="row mb-3">
	<div class="col-md-4">
		<div class="form-group">
			<select class="form-control" name="type">
				<option value="*" <?php echo e((is_null(request('type'))?'': ((request('type') == '*') ? 'selected' : ''))); ?>>All Members</option>
				<option value="1" <?php echo e((is_null(request('type'))?'': ((request('type') == 1) ? 'selected' : ''))); ?>>Subscribed</option>
				<option value="2" <?php echo e((is_null(request('type'))?'': ((request('type') == 2) ? 'selected' : ''))); ?>>Not Subscribed</option>
			</select>
		</div>
	</div>
	<div class="col-md-4">
		<input type="submit" class="btn btn-primary" name="" value="Search">
	</div>
</form>
<div class="card">
	<div class="card-header">
		<h5>Members Reports</h5>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered dataTable">
				<thead>
					<th>Name</th>
					<th>Address</th>
					<th>Contacts</th>
					<th>Subscribed</th>
				</thead>
				<tbody>
					<?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($member->name); ?></td>
							<td><?php echo e($member->address); ?></td>
							<td><?php echo e($member->phone_no.' '.$member->alt_phone_no); ?></td>
							<td><?php echo e(($member->subscribed) ? 'Subscribed' : 'Not Subscribed'); ?></td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/admin/reports/members.blade.php ENDPATH**/ ?>