<?php echo e(Form::open(array('url' => array('admin/invoice/'.$invoice->id.'/payment'),'method'=>'post','enctype' => 'multipart/form-data'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('date', __('Date'),['class'=>'form-label'])); ?>

            <?php echo e(Form::date('date', '', array('class' => 'form-control ','required'=>'required'))); ?>

        </div>
        <div class="form-group  col-md-6">
            <?php echo e(Form::label('amount', __('Amount'),['class'=>'form-label'])); ?>

            <?php echo e(Form::number('amount',$invoice->getDue(), array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        

        <div class="form-group  col-md-12">
            <?php echo e(Form::label('reference', __('Reference'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('reference', '', array('class' => 'form-control'))); ?>

        </div>
        <div class="form-group  col-md-12">
            <?php echo e(Form::label('description', __('Description'),['class'=>'form-label'])); ?>

            <?php echo e(Form::textarea('description', '', array('class' => 'form-control','rows'=>3))); ?>

        </div>

        


    </div>
    <div class="modal-footer">

        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Add')); ?>" class="btn  btn-primary">
    </div>

</div>
<?php echo e(Form::close()); ?>


<?php /**PATH /var/www/ippu.org/resources/views/admin/invoice/payment.blade.php ENDPATH**/ ?>