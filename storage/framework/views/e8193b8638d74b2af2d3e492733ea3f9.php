
<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Reports</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Points Reports</li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<form class="row mb-3">
	<div class="col-md-4">
		<div class="form-group">
			<select class="form-control" name="year">
				<option value="2020" <?php echo e((is_null(request('year'))?'': ((request('year') == '2020') ? 'selected' : ''))); ?>>2020</option>
				<option value="2021" <?php echo e((is_null(request('year'))?'': ((request('year') == 2021) ? 'selected' : ''))); ?>>2021</option>
				<option value="2022" <?php echo e((is_null(request('year'))?'': ((request('year') == 2022) ? 'selected' : ''))); ?>>2022</option>
				<option value="2023" <?php echo e((is_null(request('year'))?'': ((request('year') == 2023) ? 'selected' : ''))); ?>>2023</option>
				<option value="2024" <?php echo e((is_null(request('year'))?'': ((request('year') == 2024) ? 'selected' : ''))); ?>>2024</option>
				<option value="2025" <?php echo e((is_null(request('year'))?'': ((request('year') == 2025) ? 'selected' : ''))); ?>>2025</option>
			</select>
		</div>
	</div>
	<div class="col-md-4">
		<input type="submit" class="btn btn-primary" name="" value="Search">
	</div>
</form>
<div class="card">
	<div class="card-header">
		<h5>Point Reports</h5>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered dataTable">
				<thead>
					<th>Name</th>
					<th>Address</th>
					<th>Contacts</th>
					<th>Points</th>
				</thead>
				<tbody>
					<?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($member->name); ?></td>
							<td><?php echo e($member->address); ?></td>
							<td><?php echo e($member->phone_no.' '.$member->alt_phone_no); ?></td>
							<td><?php echo e(number_format($member->points_details_sum_points)); ?></td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/reports/points.blade.php ENDPATH**/ ?>