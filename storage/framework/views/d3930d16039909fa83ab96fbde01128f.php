<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Proposals')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
   

<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Quotations</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
            <li class="breadcrumb-item">Quotations</li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">

        <a href="<?php echo e(url('proposal.export')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Export')); ?>">
            <i class=" ri-download-cloud-2-fill"></i>
        </a>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create proposal')): ?>
            <a href="<?php echo e(url('proposal.create',0)); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>">
                <i class="ri-add-fill"></i>
            </a>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startPush('script-page'); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="text-end mb-3">

        

        
            <a href="<?php echo e(url('admin/proposals/create',0)); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>">
                <i class="ri-add-fill"></i> Create Quotations
            </a>
        
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">

                            <?php echo e(Form::open(array('url' => array('admin/proposals'),'method' => 'GET','id'=>'frm_submit'))); ?>




                        <div class="d-flex align-items-center justify-content-end">








                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                <div class="btn-box">
                                    <?php echo e(Form::label('issue_date', __('Date'),['class'=>'form-label'])); ?>

                                    <?php echo e(Form::date('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:null, array('class' => 'form-control month-btn','id'=>'pc-daterangepicker-1'))); ?>

                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 ">
                                <div class="btn-box">
                                    <?php echo e(Form::label('status', __('Status'),['class'=>'form-label'])); ?>

                                    <?php echo e(Form::select('status', [ ''=>'Select Status'] + $status,isset($_GET['status'])?$_GET['status']:'', ['class' => 'form-control select','id'=>'stat'])); ?>

                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">

                                <a href="javascript:void(0)" class="btn btn-sm btn-primary" onclick="document.getElementById('frm_submit').submit(); return false;" data-bs-toggle="tooltip" title="<?php echo e(__('apply')); ?>">
                                    <span class="btn-inner--icon">Search
                                    </span>
                                </a>
                                <a href="javascript:void(0)" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                   title="<?php echo e(__('Reset')); ?>">
                                    <span class="btn-inner--icon">Reset</span>
                                </a>
                            </div>

                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Quote No.')); ?></th>



                                <th> <?php echo e(__('Category')); ?></th>
                                <th> <?php echo e(__('Issue Date')); ?></th>
                                <th> <?php echo e(__('Status')); ?></th>
                                
                                    <th width="10%"> <?php echo e(__('Action')); ?></th>
                                
                                
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $proposals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="font-style">
                                    <td class="Id">
                                        <a href="<?php echo e(url('admin/proposals',\Crypt::encrypt($proposal->id))); ?>" class="btn btn-outline-primary"><?php echo e(Auth::user()->proposalNumberFormat($proposal->proposal_id)); ?>

                                        </a>
                                    </td>

                                    <td><?php echo e(!empty($proposal->category)?$proposal->category->name:''); ?></td>
                                    <td><?php echo e(Auth::user()->dateFormat($proposal->issue_date)); ?></td>
                                    <td>
                                        <?php if($proposal->status == 0): ?>
                                            <span class="status_badge badge bg-primary p-2 px-3 rounded"><?php echo e(__(\App\Models\Proposal::$statues[$proposal->status])); ?></span>
                                        <?php elseif($proposal->status == 1): ?>
                                            <span class="status_badge badge bg-info p-2 px-3 rounded"><?php echo e(__(\App\Models\Proposal::$statues[$proposal->status])); ?></span>
                                        <?php elseif($proposal->status == 2): ?>
                                            <span class="status_badge badge bg-success p-2 px-3 rounded"><?php echo e(__(\App\Models\Proposal::$statues[$proposal->status])); ?></span>
                                        <?php elseif($proposal->status == 3): ?>
                                            <span class="status_badge badge bg-warning p-2 px-3 rounded"><?php echo e(__(\App\Models\Proposal::$statues[$proposal->status])); ?></span>
                                        <?php elseif($proposal->status == 4): ?>
                                            <span class="status_badge badge bg-danger p-2 px-3 rounded"><?php echo e(__(\App\Models\Proposal::$statues[$proposal->status])); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    
                                        <td class="Action">
                                            <?php if($proposal->is_convert==0): ?>
                                                
                                                    
                                                
                                            <?php else: ?>
                                                
                                                   
                                                
                                            <?php endif; ?>
                                            
                                               
                                            
                                            

                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="<?php echo e(url('admin/proposals',\Crypt::encrypt($proposal->id))); ?>" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Show')); ?>" title="<?php echo e(__('Detail')); ?>">
                                                            <i class="las la-eye text-white text-white"></i>
                                                        </a>
                                                    </div>
                                            
                                            
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="<?php echo e(url('admin/proposals/'.$proposal->id.'/edit')); ?>" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>" title="<?php echo e(__('Edit')); ?>">
                                                        <i class="las la-edit text-white"></i>
                                                    </a>
                                                </div>
                                            

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete proposal')): ?>
                                                <div class="action-btn bg-danger ms-2">
                                                    <?php echo Form::open(['method' => 'DELETE', 'url' => ['proposal.destroy', $proposal->id],'id'=>'delete-form-'.$proposal->id]); ?>


                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" title="<?php echo e(__('Delete')); ?>" data-confirm="<?php echo e(__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')); ?>" data-confirm-yes="document.getElementById('delete-form-<?php echo e($proposal->id); ?>').submit();">
                                                        <i class="las la-trash text-white text-white"></i>
                                                    </a>
                                                    <?php echo Form::close(); ?>

                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/proposal/index.blade.php ENDPATH**/ ?>