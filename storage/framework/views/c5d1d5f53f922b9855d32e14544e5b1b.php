<form action="<?php echo e(url('admin/change_member_status')); ?>" method="POST">
	<?php echo csrf_field(); ?>
	<input type="hidden" name="member" value="<?php echo e($member->id); ?>">
	<div class="modal-body">
	<div class="form-group">
		<label>Status</label>
		<select class="form-control form-select" name="status">
			<?php if($member->status == "Inactive"): ?>
				<option selected disabled>Select Member Account Status</option>
				<option value="Active">Activate Account</option>
			<?php elseif($member->status == "Active"): ?>
				<option value="Suspended">Suspend Account</option>
			<?php else: ?>
				<option value="Active">Unsuspend Account</option>
			<?php endif; ?>
		</select>
	</div>
	<div>
		<label>Comment</label>
		<textarea class="form-control" rows="4" name="comment"></textarea>
	</div>
</div>
<div class="modal-footer text-end">
	<button type="submit" class="btn btn-danger">Confirm Action</button>
</div>
</form><?php /**PATH /var/www/ippu.org/resources/views/admin/members/status.blade.php ENDPATH**/ ?>