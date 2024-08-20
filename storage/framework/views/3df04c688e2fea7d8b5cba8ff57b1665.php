
<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<h4 class="mb-sm-0">Reports</h4>

	<div class="page-title-right">
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
			<li class="breadcrumb-item active">Payments Report</li>
		</ol>
	</div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<form class="row mb-3">
	<div class="col-md-3">
		<div class="form-group">
			<select class="form-control" name="type">
				<option value="*" <?php echo e((is_null(request('type'))?'': ((request('type') == '*') ? 'selected' : ''))); ?>>All Payments</option>
				<option value="Subscription" <?php echo e((is_null(request('type'))?'': ((request('type') == 'Subscription') ? 'selected' : ''))); ?>>Subscription</option>
				<option value="Event" <?php echo e((is_null(request('type'))?'': ((request('type') == 'Event') ? 'selected' : ''))); ?>>Event</option>
				<option value="CPD" <?php echo e((is_null(request('type'))?'': ((request('type') == 'CPD') ? 'selected' : ''))); ?>>CPD</option>
			</select>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-text" id="inputGroup-sizing-default">From</span>
				<input type="date" class="form-control" name="from" value="<?php echo e(is_null(request('from')) ? date('Y-m-d') : request('from')); ?>">
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			<div class="input-group">
				<span class="input-group-text" id="inputGroup-sizing-default">From</span>
				<input type="date" class="form-control" name="to" value="<?php echo e(is_null(request('to')) ? date('Y-m-d') : request('to')); ?>">
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<input type="submit" class="btn btn-primary" name="" value="Search">
	</div>
</form>
<div class="card">
	<div class="card-header">
		<h5>Payments Report</h5>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-striped table-hover table-bordered dataTable">
				<thead>
					<th>Name</th>
					<th>Type</th>
					<th>Amount</th>
					<th>Balance</th>
					<th>Date</th>
					<td>Received By</td>
				</thead>
				<tbody>
					<?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr>
						<td><?php echo e($payment?->user?->name); ?></td>
						<td><?php echo e($payment->type); ?></td>
						<th><?php echo e(number_format($payment->amount)); ?></th>
						<td><?php echo e(number_format($payment->balance)); ?></td>
						<td><?php echo e(date('d M, Y',strtotime($payment->created_at))); ?></td>
						<th><?php echo e($payment?->receiver?->name); ?></th>
					</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/reports/payments.blade.php ENDPATH**/ ?>