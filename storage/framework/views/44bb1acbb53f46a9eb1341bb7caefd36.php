<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Invoices')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script>
        function copyToClipboard(element) {

            var copyText = element.id;
            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('success', 'Url copied to clipboard', 'success');
        }
    </script>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('breadcrumb'); ?>
    

    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Invoices</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
            <li class="breadcrumb-item"><?php echo e(__('Invoices')); ?></li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-btn'); ?>
    <div class="float-end">
        
        
        

        <a href="<?php echo e(url('invoice.export')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Export')); ?>">
            <i class=" ri-download-cloud-2-fill"></i>
        </a>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create invoice')): ?>
            <a href="<?php echo e(url('invoice.create', 0)); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>">
                <i class="ri-add-fill"></i>
            </a>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
 <div class="text-end mb-3">
        
        
        

        

        
            <a href="<?php echo e(url('admin/invoices/create/0')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>">
                <i class="ri-add-fill"></i> Create New Invoice
            </a>
        
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">

                            <?php echo e(Form::open(['url' => ['invoice.index'], 'method' => 'GET', 'id' => 'customer_submit'])); ?>




                        <div class="row d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    <?php echo e(Form::label('issue_date', __('Issue Date'),['class'=>'form-label'])); ?>

                                    <?php echo e(Form::date('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:'', array('class' => 'form-control month-btn','id'=>'pc-daterangepicker-1'))); ?>



                                </div>
                            </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                    <div class="btn-box">
                                        <?php echo e(Form::label('customer', __('Customer'),['class'=>'form-label'])); ?>

                                        <?php echo e(Form::select('customer', $customer, isset($_GET['customer']) ? $_GET['customer'] : '', ['class' => 'form-control select'])); ?>

                                    </div>
                                </div>

                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                <div class="btn-box">
                                    <?php echo e(Form::label('status', __('Status'),['class'=>'form-label'])); ?>


                                    <select class="form-control select" name="status">
                                        <option value="">Select Status</option>
                                        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($index); ?>"<?php echo e((isset($_GET['status'])? (($_GET['status'] == $index) ? 'selected' : ''):'')); ?>><?php echo e($st); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto float-end ms-2 mt-4">

                                <a href="#" class="btn btn-sm btn-primary"
                                   onclick="document.getElementById('customer_submit').submit(); return false;"
                                   data-toggle="tooltip" title="<?php echo e(__('apply')); ?>">
                                    <span class="btn-inner--icon"><i class="las la-search"></i></span>
                                </a>







                                    <a href="<?php echo e(url('customer.index')); ?>" class="btn btn-sm btn-primary" data-toggle="tooltip"
                                       title="<?php echo e(__('Reset')); ?>">
                                        <span class="btn-inner--icon"><i class="las la-trash text-white-off"></i></span>
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
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> <?php echo e(__('Invoice')); ?></th>



                                <th><?php echo e(__('Issue Date')); ?></th>
                                <th><?php echo e(__('Due Date')); ?></th>
                                <th><?php echo e(__('Due Amount')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                
                                    <th><?php echo e(__('Action')); ?></th>
                                
                                
                            </tr>
                            </thead>

                            <tbody>
                            <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="Id">



                                            <a href="<?php echo e(url('admin/invoices', \Crypt::encrypt($invoice->id))); ?>" class="btn btn-outline-primary"><?php echo e(Auth::user()->invoiceNumberFormat($invoice->invoice_id)); ?></a>

                                    </td>



                                    <td><?php echo e(Auth::user()->dateFormat($invoice->issue_date)); ?></td>
                                    <td>
                                        <?php if($invoice->due_date < date('Y-m-d')): ?>
                                            <p class="text-danger">
                                                <?php echo e(\Auth::user()->dateFormat($invoice->due_date)); ?></p>
                                        <?php else: ?>
                                            <?php echo e(\Auth::user()->dateFormat($invoice->due_date)); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(\Auth::user()->priceFormat($invoice->getDue())); ?></td>
                                    <td>
                                        <?php if($invoice->status == 0): ?>
                                            <span
                                                class="status_badge badge bg-secondary p-2 px-3 rounded"><?php echo e(__(\App\Models\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php elseif($invoice->status == 1): ?>
                                            <span
                                                class="status_badge badge bg-warning p-2 px-3 rounded"><?php echo e(__(\App\Models\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php elseif($invoice->status == 2): ?>
                                            <span
                                                class="status_badge badge bg-danger p-2 px-3 rounded"><?php echo e(__(\App\Models\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php elseif($invoice->status == 3): ?>
                                            <span
                                                class="status_badge badge bg-info p-2 px-3 rounded"><?php echo e(__(\App\Models\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php elseif($invoice->status == 4): ?>
                                            <span
                                                class="status_badge badge bg-primary p-2 px-3 rounded"><?php echo e(__(\App\Models\Invoice::$statues[$invoice->status])); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    
                                        <td class="Action">
                                                <span>
                                                <?php $invoiceID= Crypt::encrypt($invoice->id); ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('copy invoice')): ?>
                                                        <div class="  ms-2">
                                                            <a href="#" id="<?php echo e(url('invoice.link.copy',[$invoiceID])); ?>" class="mx-3 bg-warning btn btn-sm align-items-center"   onclick="copyToClipboard(this)" data-bs-toggle="tooltip" title="<?php echo e(__('Click to copy')); ?>"><i class="las la-link text-white"></i></a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('duplicate invoice')): ?>
                                                        <div class=" ms-2">
                                                           <?php echo Form::open(['method' => 'get', 'url' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id,'class' => 'd-inline']); ?>


                                                            <a href="#" class="mx-3 btn bg-success btn-sm align-items-center bs-pass-para" data-toggle="tooltip"
                                                               title="<?php echo e(__('Duplicate')); ?>" data-bs-toggle="tooltip" title="Duplicate Invoice"
                                                               title="<?php echo e(__('Delete')); ?>"
                                                               data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back"
                                                               data-confirm-yes="document.getElementById('duplicate-form-<?php echo e($invoice->id); ?>').submit();">
                                                                <i class="las la-copy text-white"></i>
                                                                <?php echo Form::open(['method' => 'get', 'url' => ['invoice.duplicate', $invoice->id], 'id' => 'duplicate-form-' . $invoice->id,'class' => 'd-inline']); ?>

                                                                <?php echo Form::close(); ?>

                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    









                                                            <div class="ms-2">
                                                                    <a href="<?php echo e(url('admin/invoices', \Crypt::encrypt($invoice->id))); ?>"
                                                                       class="mx-3 bg-info btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Show "
                                                                       title="<?php echo e(__('Detail')); ?>">
                                                                        <i class="las la-eye text-white"></i>
                                                                    </a>
                                                                </div>

                                                    
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit invoice')): ?>
                                                        <div class=" ms-2">
                                                                <a href="<?php echo e(url('invoice.e', \Crypt::encrypt($invoice->id))); ?>"
                                                                   class="mx-3 bg-primary btn btn-sm align-items-center" data-bs-toggle="tooltip" title="Edit "
                                                                   title="<?php echo e(__('Edit')); ?>">
                                                                    <i class="las la-edit text-white"></i>
                                                                </a>
                                                            </div>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete invoice')): ?>
                                                        <div class=" ms-2">
                                                                <?php echo Form::open(['method' => 'DELETE', 'url' => ['invoice.destroy', $invoice->id], 'id' => 'delete-form-' . $invoice->id]); ?>

                                                                    <a href="#" class="mx-3 bg-danger btn btn-sm align-items-center bs-pass-para " data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>"
                                                                       title="<?php echo e(__('Delete')); ?>"
                                                                       data-confirm="<?php echo e(__('Are You Sure?') . '|' . __('This action can not be undone. Do you want to continue?')); ?>"
                                                                       data-confirm-yes="document.getElementById('delete-form-<?php echo e($invoice->id); ?>').submit();">
                                                                        <i class="las la-trash text-white"></i>
                                                                    </a>
                                                                <?php echo Form::close(); ?>

                                                            </div>
                                                    <?php endif; ?>
                                                </span>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/invoice/index.blade.php ENDPATH**/ ?>