
<?php $__env->startSection('content'); ?>
<div class="">
	<div class="card">
		<form method="POST" action="<?php echo e(url('admin/review_membership')); ?>">
			<?php echo csrf_field(); ?>
			<input type="hidden" name="member" value="<?php echo e($member->id); ?>">
			<div class="card-body">
			<div class="row">
				<div class="col-md-4 mb-3">
					<h5>Name</h5>
					<div><?php echo e($member->name); ?></div>
				</div>
				<div class="col-md-4 mb-3">
					<h5>Address</h5>
					<div><?php echo e($member->address); ?></div>
				</div>
				<div class="col-md-4 mb-3">
					<h5>Address</h5>
					<div><?php echo e($member->phone_no.' / '.$member->alt_phone_no); ?></div>
				</div>
				<div class="col-md-4 mb-3">
					<h5>Gender</h5>
					<div><?php echo e($member->gender); ?></div>
				</div>

				<div class="col-md-4 mb-3">
					<h5>Application Date</h5>
					<div><?php echo e(date('d M, Y',strtotime($member->latestMembership->created_at))); ?></div>
				</div>
				<div class="col-md-4 mb-3">
					<h5>Account Type</h5>
					<div><?php echo e($member?->account_type?->name); ?></div>
				</div>
				<div class="col-md-12 mb-3">
					<h5>Comment</h5>
					<textarea rows="7" class="form-control" name="comment"></textarea>
				</div>
				<div class="col-md-6">
					<div class="form-check mb-2">
						<input class="form-check-input" type="radio" name="status" value="Approved" id="flexRadioDefault1" required>
						<label class="form-check-label" for="flexRadioDefault1">
							Approve
						</label>
					</div>
					<div class="form-check form-radio-danger">
						<input class="form-check-input" type="radio" name="status" value="Denied" id="flexRadioDefault2" required>
						<label class="form-check-label" for="flexRadioDefault2">
							Deny
						</label>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Amount Paid</label>
						<input type="text" name="payment" value="<?php echo e(number_format($payment)); ?>" class="form-control number_format">
					</div>
				</div>
			</div>
		</div>
		<div class="card-footer text-end">
			<button type="submit" class="btn btn-danger">Review</button>
		</div>
		</form>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/admin/members/review.blade.php ENDPATH**/ ?>