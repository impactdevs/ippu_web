<?php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_favicon=\App\Models\Utility::getValByName('company_favicon');
    $favicon=\App\Models\Utility::getValByName('company_favicon');
?>

<html lang="en">
<meta name="csrf-token" id="csrf-token" content="<?php echo e(csrf_token()); ?>">
<head>
    <title><?php echo e((\App\Models\Utility::getValByName('title_text')) ? \App\Models\Utility::getValByName('title_text') : config('app.name', 'IPPU')); ?> - Form Builder</title>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <!-- Meta -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <link rel="icon" href="<?php echo e($logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')); ?>" type="image" sizes="16x16">

    <!-- Favicon icon -->
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.svg')); ?>" type="image/x-icon"/>

    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/animate.min.css')); ?>">

    <!-- font css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/tabler-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/fontawesome.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/material.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/main.css')); ?>">
    <!-- vendor css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" id="main-style-link">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/customizer.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('css/custom.css')); ?>" id="main-style-link">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/app.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>">

</head>

<body class="theme-4">

    <div class="dash-content">

        <div class="min-vh-100 py-5 d-flex align-items-center">
            <div class="w-100">
                <div class="row justify-content-center">
                    <div class="col-sm-8 col-lg-5">
                        <div class="text-center mb-3">
                            <div class="text-center">
                                <img src="https://ippu.or.ug/wp-content/uploads/2020/03/cropped-Logo-192x192.png" alt="" width="10%" height="10%">
                            </div>
                            <h1>Register To attend</h1>                        
                        </div>
                        <div class="card shadow zindex-100 mb-0">
                            <?php if($form->is_active == 1): ?>
                                <?php echo e(Form::open(array('route'=>array('form.view.store'),'method'=>'post'))); ?>

                                <div class="card-body px-md-5 py-5">
                                    <div class="mb-4">
                                        <h6 class="h3"><?php echo e($form->name); ?></h6>
                                    </div>
                                    <input type="hidden" value="<?php echo e($code); ?>" name="code">
                                    <?php if($objFields && $objFields->count() > 0): ?>
                                        <?php $__currentLoopData = $objFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $objField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($objField->type == 'text'): ?>
                                                <div class="form-group">
                                                    <?php echo e(Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-label'])); ?>

                                                    <?php echo e(Form::text('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id))); ?>

                                                </div>
                                            <?php elseif($objField->type == 'email'): ?>
                                                <div class="form-group">
                                                    <?php echo e(Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-label'])); ?>

                                                    <?php echo e(Form::email('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id))); ?>

                                                </div>
                                            <?php elseif($objField->type == 'number'): ?>
                                                <div class="form-group">
                                                    <?php echo e(Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-label'])); ?>

                                                    <?php echo e(Form::number('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id))); ?>

                                                </div>
                                            <?php elseif($objField->type == 'date'): ?>
                                                <div class="form-group">
                                                    <?php echo e(Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-label'])); ?>

                                                    <?php echo e(Form::date('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id))); ?>

                                                </div>
                                            <?php elseif($objField->type == 'textarea'): ?>
                                                <div class="form-group">
                                                    <?php echo e(Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-label'])); ?>

                                                    <?php echo e(Form::textarea('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id))); ?>

                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                    <div class="mt-4 text-end">

                                        <?php echo e(Form::submit(__('Submit'),array('class'=>'btn btn-primary'))); ?>

                                    </div>
                                </div>

                                <?php echo e(Form::close()); ?>

                            <?php else: ?>
                                <div class="page-title"><h5><?php echo e(__('Form is not active.')); ?></h5></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>

</html>
<?php /**PATH /var/www/ippu.org/resources/views/admin/form_builder/form_view.blade.php ENDPATH**/ ?>