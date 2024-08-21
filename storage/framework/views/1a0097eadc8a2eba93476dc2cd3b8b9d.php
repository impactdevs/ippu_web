<?php if(!empty($customer)): ?>
    <div class="row">
        <div class="col-md-6">
            <h6><?php echo e(__('Customer Details')); ?></h6>
            <div class="bill-to">
                <small>
                    <span><?php echo e($customer['name']); ?></span><br>
                    <span><?php echo e($customer['email']); ?></span><br>
                    <span><?php echo e($customer['contact']); ?></span><br>
                    
                </small>
            </div>
        </div>
        <div class="col-md-6">
            <h6><?php echo e(__('Bill to')); ?></h6>
            <div class="bill-to">
                <small>
                    <span><?php echo e($customer['billing_name']); ?></span><br>
                    <span><?php echo e($customer['billing_phone']); ?></span><br>
                    <span><?php echo e($customer['billing_address']); ?></span><br>
                    <span><?php echo e($customer['billing_zip']); ?></span><br>
                    <span><?php echo e($customer['billing_country'] . ' , '.$customer['billing_city'].' , '.$customer['billing_state'].'.'); ?></span>
                </small>
            </div>
        </div>
        
        <div class="col-md-12 text-end">
            <a href="#" id="remove" class="btn btn-sm btn-danger"><?php echo e(__(' Remove')); ?></a>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/ippu.org/resources/views/admin/proposal/customer_detail.blade.php ENDPATH**/ ?>