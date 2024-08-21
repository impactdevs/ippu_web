<form method="POST" action="<?php echo e(url('attend_cpd')); ?>">
	<?php echo csrf_field(); ?>
	<input type="hidden" name="cpd_id" value="<?php echo e($event->id); ?>">
	<div class="modal-body">
		<?php if((\Auth::user()->latestMembership->expiry_date >= date('Y-m-d'))): ?>
			<?php if($event->member_rate > 0): ?>
				This CPD is will cost you <?php echo e(number_format($event->member_rate)); ?><br>
				Payment Instructions
			<?php else: ?>
				CPD Instructions
			<?php endif; ?>
		<?php else: ?>
			<?php if($event->rate > 0): ?>
				This CPD is will cost you <?php echo e(number_format($event->member_rate)); ?><br>
				Payment Instructions
			<?php else: ?>
				CPD Instructions
			<?php endif; ?>
		<?php endif; ?>
		<img src="<?php echo e(asset('assets/images/payments.jpeg')); ?>" width="100%" />
	</div>
	<div class="modal-footer text-end">
		<button type="submit" class="btn btn-primary">Confirm Attendence</button>
	</div>
</form><?php /**PATH /var/www/html/ippu/resources/views/members/cpds/confirmation.blade.php ENDPATH**/ ?>