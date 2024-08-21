<div class="card-body">
    <div class="mb-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" name="name" id="name" class="form-control" value="<?php echo e(@old('name')); ?>" required/>
        <?php if($errors->has('name')): ?>
        <div class='error small text-danger'><?php echo e($errors->first('name')); ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="rate" class="form-label">Rate:</label>
        <input type="text" name="rate" id="rate" class="form-control" value="<?php echo e(@old('rate')); ?>" required/>
        <?php if($errors->has('rate')): ?>
        <div class='error small text-danger'><?php echo e($errors->first('rate')); ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="is_active" class="form-label">Is Active:</label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_active" id="is_active_yes" value="1" <?php echo e(@old('is_active') == '1' ? 'checked' : ''); ?> required>
                <label class="form-check-label" for="is_active_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="is_active" id="is_active_no" value="0" <?php echo e(@old('is_active') == '0' ? 'checked' : ''); ?> required>
                <label class="form-check-label" for="is_active_no">No</label>
            </div>
        </div>
        <?php if($errors->has('is_active')): ?>
        <div class='error small text-danger'><?php echo e($errors->first('is_active')); ?></div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label for="rate" class="form-label">Description:</label>
        <textarea class="form-control" name="description" required></textarea>
        <?php if($errors->has('description')): ?>
        <div class='error small text-danger'><?php echo e($errors->first('description')); ?></div>
        <?php endif; ?>
    </div>

</div>
<?php /**PATH /var/www/ippu.org/resources/views/admin/account_types/create.blade.php ENDPATH**/ ?>