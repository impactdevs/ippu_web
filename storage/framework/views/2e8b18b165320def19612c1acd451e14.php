<?php echo e(Form::open(array('url' => 'admin/product-category'))); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <?php echo e(Form::label('name', __('Category Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name', '', array('class' => 'form-control','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('type', __('Category Type'),['class'=>'form-label'])); ?>

            <?php echo e(Form::select('type',$types,null, array('class' => 'form-control select ','required'=>'required'))); ?>

        </div>
        <div class="form-group col-md-12">
            <?php echo e(Form::label('color', __('Category Color'),['class'=>'form-label'])); ?>

            <input type="color" name="color" class="form-control jscolor" required>
           
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="<?php echo e(__('Cancel')); ?>" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /var/www/html/ippu/resources/views/admin/productServiceCategory/create.blade.php ENDPATH**/ ?>