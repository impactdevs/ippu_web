
<div class="my">
    <div class="rw">
        <div class="form-group col-12">
            <?php echo e(Form::label('name', __('Pipeline Name'),['class'=>'form-label'])); ?>

            <?php echo e(Form::text('name', $pipeline->name, array('class' => 'form-control','required'=>'required'))); ?>

        </div>
    </div>
</div><?php /**PATH /var/www/ippu.org/resources/views/admin/pipelines/edit.blade.php ENDPATH**/ ?>