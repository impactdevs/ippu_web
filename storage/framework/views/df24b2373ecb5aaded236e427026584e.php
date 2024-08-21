<?php $__env->startSection('content'); ?>
<div class="col-md-12 mx-auto">
    <div class="card">
        <div class="card-header">
            <h5>Reminders Test</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped dataTable table-responsive table-hover">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $reminders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reminder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr id="notification_<?php echo e($reminder->id); ?>">
                        <td>
                            <?php if($reminder->member): ?>
                            <div class="d-flex">
                                <img src="<?php echo e(asset('storage/profiles/'.$reminder->member->profile_pic)); ?>" onerror="this.onerror=null;this.src='<?php echo e(asset('assets/images/users/user-dummy-img.jpg')); ?>';" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-1">
                                    <a href="<?php echo e(url('admin/members/'.$reminder->member_id)); ?>">
                                        <h6 class="mt-0 mb-1 fs-13 fw-semibold"><?php echo e($reminder->member->name); ?></h6>
                                    </a>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="d-flex">
                                <img src="<?php echo e(asset('assets/images/users/user-dummy-img.jpg')); ?>" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-1">
                                    <h6 class="mt-0 mb-1 fs-13 fw-semibold text-muted">Unknown Member</h6>
                                </div>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($reminder->title); ?></td>
                        <td><?php echo e($reminder->created_at->diffForHumans()); ?> ago</td>
                        <td>
                            <div class="form-check form-switch form-switch-success">
                                <input class="form-check-input read_notification" type="checkbox" role="switch" id="SwitchCheck3" value="<?php echo e($reminder->id); ?>">
                                <label class="form-check-label" for="SwitchCheck3">Mark As Read</label>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('customjs'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize the DataTable
        $('.dataTable').DataTable();

        // Handle notification read functionality
        $('.read_notification').change(function(){
            var id = $(this).val();
            if(this.checked) {
                $.ajax({
                    url: '<?php echo e(url('admin/read_notification')); ?>',
                    type: 'post',
                    data: 'id=' + id,
                    dataType: 'json',
                    success: function(data) {
                        $("#notification_" + id).slideUp();
                    }
                })
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/katendenicholas/Desktop/laravel/ippu_web/resources/views/admin/reminders/index.blade.php ENDPATH**/ ?>