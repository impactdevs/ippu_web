<div class="row">
	<div class="form-group mb-3">
		<label>Institution Name</label>
		<input type="text" name="institution_name" class="form-control" value="<?php echo e($experience->title); ?>" placeholder="Institution Name" required>
	</div>
	<div class="form-group mb-3">
		<label>Field Of Study</label>
		<input type="text" name="field" class="form-control" placeholder="Field of Study" value="<?php echo e($experience->field); ?>">
	</div>
	<div class="col-md-4 form-group mb-3">
		<label>Points</label>
		<input type="text" name="points" class="form-control number_format" placeholder="points" value="<?php echo e($experience->points); ?>">
	</div>
	<div class="col-md-4 form-group mb-3">
		<label>Start Date</label>
		<input type="date" name="start_date" class="form-control" value="<?php echo e($experience->start_date); ?>">
	</div>
	<div class="col-md-4 form-group mb-3">
		<label>Start Date</label>
		<input type="date" name="end_date" class="form-control" value="<?php echo e($experience->end_date); ?>">
	</div>
	
</div><?php /**PATH /var/www/ippu.org/resources/views/members/education/edit.blade.php ENDPATH**/ ?>