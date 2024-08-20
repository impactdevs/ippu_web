<form action="<?php echo e(url('admin/update_member_details')); ?>" method="POST">
	<?php echo csrf_field(); ?>
	<input type="hidden" name="member" value="<?php echo e($member->id); ?>">
	
	<div class="modal-body">
		<div class="form-group mb-3">
			<label>Name</label>
			<input type="text" class="form-control" name="name" value="<?php echo e($member->name); ?>">
		</div>
		<div class="form-group mb-3">
			<label>Membership Number</label>
			<input type="text"  class="form-control" name="membership_number" value="<?php echo e($member->membership_number); ?>">
		</div>
		<div class="form-group mb-3">
			<label>Email</label>
			<input type="text" class="form-control" name="email" value="<?php echo e($member->email); ?>">
		</div>
		<div class="form-group mb-3">
			<label>Gender</label>
			<select class="form-control form-select" name="gender">
				<option value="Male" <?php echo e(($member->gender == "Male") ? 'selected' : ''); ?>>Male</option>
				<option value="Female" <?php echo e(($member->gender == "Female") ? 'selected' : ''); ?>>Female</option>
			</select>
		</div>
		<div class="form-group mb-3">
			<label>Organisation</label>
			<input type="text" class="form-control" name="organisation" value="<?php echo e($member->organisation); ?>">
		</div>
	<div class="form-group">
		<label>Account Type</label>
		<select class="form-control form-select" name="account_type">
			<?php $__currentLoopData = $account_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value="<?php echo e($account_type->id); ?>" <?php echo e(($account_type->id == $member->account_type_id) ? 'selected' : ''); ?>><?php echo e($account_type->name); ?></option>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</select>
	</div>
</div>
<div class="modal-footer text-end">
	<button type="submit" class="btn btn-danger">Update Member Details</button>
</div>
</form><?php /**PATH /var/www/staging.ippu.org/resources/views/admin/members/update.blade.php ENDPATH**/ ?>