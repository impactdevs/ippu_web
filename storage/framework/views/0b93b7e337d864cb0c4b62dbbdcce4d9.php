<div class="row">
	<div class="form-group mb-3">
		<label>Institution Name</label>
		<input type="text" name="title" class="form-control" placeholder="Institution Name" value="<?php echo e($experience->title); ?>" required>
	</div>
	<div class="form-group mb-3">
		<label>Position</label>
		<input type="text" name="position" class="form-control" placeholder="Position Held" value="<?php echo e($experience->position); ?>" required>
	</div>
	<div class="col-md-6 form-group mb-3">
		<label>Start Date</label>
		<input type="date" name="start_date" class="form-control" value="<?php echo e($experience->start_date); ?>" required>
	</div>
	<div class="col-md-6 form-group mb-3">
		<label>Start Date</label>
		<input type="date" name="end_date" class="form-control" value="<?php echo e($experience->end_date); ?>">
	</div>
	<div class="form-group mb-3">
		<label>Description</label>
		<textarea class="form-control" name="description" rows="5" placeholder="Details / Description"><?php echo e($experience->description); ?></textarea>
	</div>
</div><?php /**PATH /var/www/html/ippu/resources/views/members/work/edit.blade.php ENDPATH**/ ?>