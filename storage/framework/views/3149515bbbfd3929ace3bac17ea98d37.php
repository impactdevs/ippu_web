<?php echo e(Form::model($customField, array('url' => array('admin/custom-fields', $customField->id), 'method' => 'PUT'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <?php echo e(Form::label('name',__('Custom Field Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name',null,array('class'=>'form-control','required'=>'required'))); ?>

        </div>

    </div>
</div>

    <div class="modal-footer">
        <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
    </div>
<?php echo e(Form::close()); ?>

<?php /**PATH /var/www/ippu.org/resources/views/admin/customFields/edit.blade.php ENDPATH**/ ?>