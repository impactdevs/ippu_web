<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">CPDs</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(url('admin/cpds')); ?>">CPDs</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h5 class="card-title">Create New CPD</h5>
        </div>

        <div class="card-body">
            <form action="<?php echo e(route('cpds.store', [])); ?>" method="POST" class="m-0 p-0" enctype="multipart/form-data">
                <div class="card-body row">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3 col-lg-3">
                        <label for="code" class="form-label">Code:</label>
                        <input type="text" name="code" id="code" class="form-control" value="<?php echo e(@old('code')); ?>" required/>
                        <?php if($errors->has('code')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('code')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-9">
                        <label for="topic" class="form-label">Topic:</label>
                        <input type="text" name="topic" id="topic" class="form-control" value="<?php echo e(@old('topic')); ?>" required/>
                        <?php if($errors->has('topic')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('topic')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content:</label>
                        <textarea name="content" id="content" class="form-control ckeditor"><?php echo e(@old('content')); ?></textarea>
                        <?php if($errors->has('content')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('content')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-3">
                        <label for="hours" class="form-label">Hours:</label>
                        <input type="text" name="hours" id="hours" class="form-control" value="<?php echo e(@old('hours')); ?>" required/>
                        <?php if($errors->has('hours')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('hours')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-3">
                        <label for="points" class="form-label">Points:</label>
                        <input type="text" name="points" id="points" class="form-control" value="<?php echo e(@old('points')); ?>" required/>
                        <?php if($errors->has('points')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('points')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-3">
                        <label for="target_group" class="form-label">Target Group:</label>
                        <input type="text" name="target_group" id="target_group" class="form-control" value="<?php echo e(@old('target_group')); ?>" required/>
                        <?php if($errors->has('target_group')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('target_group')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-3">
                        <label for="location" class="form-label">Location:</label>
                        <input type="text" name="location" id="location" class="form-control" value="<?php echo e(@old('location')); ?>" required/>
                        <?php if($errors->has('location')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('location')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="start_date" class="form-label">Start Date:</label>
                        <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="<?php echo e(@old('start_date')); ?>" required/>
                        <?php if($errors->has('start_date')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('start_date')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="end_date" class="form-label">End Date:</label>
                        <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="<?php echo e(@old('end_date')); ?>" required/>
                        <?php if($errors->has('end_date')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('end_date')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="normal_rate" class="form-label">Normal Rate:</label>
                        <input type="text" name="normal_rate" id="normal_rate" class="form-control number_format" value="<?php echo e(@old('normal_rate')); ?>"/>
                        <?php if($errors->has('normal_rate')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('normal_rate')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="members_rate" class="form-label">Members Rate:</label>
                        <input type="text" name="members_rate" id="members_rate" class="form-control number_format" value="<?php echo e(@old('members_rate')); ?>"/>
                        <?php if($errors->has('members_rate')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('members_rate')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="resource" class="form-label">Banner:</label>
                        <input type="file" name="banner" id="banner" class="form-control" value="<?php echo e(@old('banner')); ?>" required/>
                        <?php if($errors->has('resource')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('banner')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="resource" class="form-label">Resource:</label>
                        <input type="file" name="resource" id="resource" class="form-control" value="<?php echo e(@old('resource')); ?>" required/>
                        <?php if($errors->has('resource')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('resource')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="statu1s" class="form-label">Status:</label>
                        <select name="status" id="statu1s" class="form-control form-select" required>
                            <option value="" selected disabled>Select Status</option>
                            <?php $__currentLoopData = ["Active" => "Active", "Inactive" => "Inactive"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(@old('status') == $value ? "selected" : ""); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($errors->has('status')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('status')); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3 col-lg-4">
                        <label for="type" class="form-label">Type:</label>
                        <select name="type" id="type" class="form-control form-select" >
                            <option value="" selected disabled>Select Type</option>
                            <?php $__currentLoopData = ["Free" => "Free", "Paid" => "Paid"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php echo e(@old('type') == $value ? "selected" : ""); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if($errors->has('type')): ?>
                        <div class='error small text-danger'><?php echo e($errors->first('type')); ?></div>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="card-footer">
                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <a href="<?php echo e(route('cpds.index', [])); ?>" class="btn btn-light"><?php echo app('translator')->get('Cancel'); ?></a>
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Create new Cpd'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/staging.ippu.org/resources/views/admin/cpds/create.blade.php ENDPATH**/ ?>