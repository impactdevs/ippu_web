<?php if(!empty($vender)): ?>
    <div class="row">
        <div class="col-md-5">
            <h6><?php echo e(__('Bill to')); ?></h6>
            <div class="bill-to">
                <small>
                    <span><?php echo e($vender['name']); ?></span><br>
                    <span><?php echo e($vender['phone_no']); ?></span><br>
                    <span><?php echo e($vender['email']); ?></span><br>
                    <span><?php echo e($vender['billing_zip']); ?></span><br>
                    <span><?php echo e($vender['billing_country'] . ' , '.$vender['billing_city'].' , '.$vender['billing_state'].'.'); ?></span>
                </small>
            </div>
        </div>
        <div class="col-md-5">
            <h6><?php echo e(__('Ship to')); ?></h6>
            <div class="bill-to">
                <small>
                    <span><?php echo e($vender['shipping_name']); ?></span><br>
                    <span><?php echo e($vender['shipping_phone']); ?></span><br>
                    <span><?php echo e($vender['shipping_address']); ?></span><br>
                    <span><?php echo e($vender['shipping_zip']); ?></span><br>
                    <span><?php echo e($vender['shipping_country'] . ' , '.$vender['shipping_state'].' , '.$vender['shipping_city'].'.'); ?></span>
                </small>
            </div>
        </div>
        <div class="col-md-2">
            <a href="#" id="remove" class="text-sm btn btn-sm btn-danger"><?php echo e(__(' Remove')); ?></a>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/ippu.org/resources/views/admin/bill/vender_detail.blade.php ENDPATH**/ ?>