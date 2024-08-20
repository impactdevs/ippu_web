<?php $__env->startSection('content'); ?>
<div class="col-md-6 mx-auto">
	<div class="card">
		<div class="card-header">
			<h5>Reminders</h5>
		</div>
		<div class="card-body">
			<?php $__currentLoopData = $reminders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reminder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<div id="notification_<?php echo e($reminder->id); ?>">
					<div class="text-reset notification-item d-block dropdown-item">
                                  <div class="d-flex">
                                            <img src="<?php echo e(asset('storage/profiles/'.$reminder?->member?->profile_pic)); ?>" onerror="this.onerror=null;this.src='<?php echo e(asset('assets/images/users/user-dummy-img.jpg')); ?>';" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                            <div class="flex-1">
                                                <a href="<?php echo e(url('admin/members/'.$reminder->member_id)); ?>" class="stretched-link">
                                                    <h6 class="mt-0 mb-1 fs-13 fw-semibold"><?php echo e($reminder?->member?->name); ?></h6>
                                                </a>
                                                <div class="fs-13 text-muted">
                                                    <p class="mb-1"><?php echo e($reminder->title); ?></p>
                                                </div>
                                                <p class="mb-0 fs-11 fw-medium text-uppercase text-muted">
                                                    <span><i class="mdi mdi-clock-outline"></i> <?php echo e($reminder->created_at->diffForHumans()); ?> ago</span>
                                                </p>
                                            </div>
                                            <div class="px-2 fs-15">
                                                <div class="form-check form-switch form-switch-success">
												    <input class="form-check-input read_notification" type="checkbox" role="switch" id="SwitchCheck3" value="<?php echo e($reminder->id); ?>">
												    <label class="form-check-label" for="SwitchCheck3">Mark As Read</label>
												</div>
                                            </div> 
                                        </div>
                                    </div>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('customjs'); ?>
<script type="text/javascript">
	$(document).ready(function(){
		 $('.read_notification').change(function(){
		 	var id = $(this).val();
	        if(this.checked) {
	            $.ajax({
	            	url: '<?php echo e(url('admin/read_notification')); ?>',
	            	type: 'post',
	            	data:'id='+id,
	            	dataType: 'json',

	            	success: function(data){
	            		$("#notification_"+id).slideUp(); 
	            	}
	            })
	        }
	    });
	})
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/reminders/index.blade.php ENDPATH**/ ?>