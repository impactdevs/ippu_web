<?php $__env->startSection('customcss'); ?>
<style>
        #charCount {
            position: absolute;
            bottom: 10px;
            right: 10px;
            color: #888;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<form class="card" action="<?php echo e(url('admin/sms')); ?>" method="POST">
	<?php echo csrf_field(); ?>
	<div class="card-header">
		<h6>Create new communication</h6>
	</div>
	<div class="card-body row">
		<div class="form-group mb-3 col-md-12">
			<label>Target</label>
			<select name="target" class="form-control">
				<option value="*">All Members</option>
				<?php $__currentLoopData = $accountTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $accountType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<option value="<?php echo e($accountType->id); ?>"><?php echo e($accountType->name); ?></option>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
		</div>
		<div class="form-group mb-3">
			<label>Message</label>
			<textarea class="form-control" id="message" maxlength="200" name="message"></textarea>
			<div id="charCount">0 / 200</div>
		</div>
	</div>
	<div class="card-footer text-end">
		<button type="submit" class="btn btn-primary">Publish Communication</button>
	</div>
</form>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('customjs'); ?>
 <script>
        document.addEventListener('DOMContentLoaded', function () {
            var textarea = document.getElementById('message');
            var charCount = document.getElementById('charCount');

            textarea.addEventListener('input', function () {
                var count = textarea.value.length;
                charCount.textContent = count+" / 200";
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/communications/sms.blade.php ENDPATH**/ ?>