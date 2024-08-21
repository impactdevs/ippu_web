<?php $__env->startPush('script-page'); ?>
    <script>
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_name']").val($("[name='billing_name']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_phone']").val($("[name='billing_phone']").val());
            $("[name='shipping_zip']").val($("[name='billing_zip']").val());
            $("[name='shipping_address']").val($("[name='billing_address']").val());
        })

    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Customers</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
            <li class="breadcrumb-item"><?php echo e(__('Customer')); ?></li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="text-end mb-3">
        
        <a href="#" data-size="lg" data-url="<?php echo e(url('admin/customers/create')); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Create')); ?>" data-title="<?php echo e(__('Create Customer')); ?>" class="btn btn-sm btn-primary">
            <i class="ri-add-fill"></i> Create Customer
        </a>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th> <?php echo e(__('Name')); ?></th>
                                <th> <?php echo e(__('Contact')); ?></th>
                                <th> <?php echo e(__('Email')); ?></th>
                                <th> <?php echo e(__('Balance')); ?></th>
                                <th> <?php echo e(__('Last Login')); ?></th>
                                <th><?php echo e(__('Action')); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="cust_tr" id="cust_detail" data-url="<?php echo e(url('customer.show',$customer['id'])); ?>" data-id="<?php echo e($customer['id']); ?>">
                                    <td class="Id">
                                        
                                            <a href="<?php echo e(url('customer.show',\Crypt::encrypt($customer['id']))); ?>" class="btn btn-outline-primary">
                                                <?php echo e(Auth::user()->customerNumberFormat($customer['customer_id'])); ?>

                                            </a>
                                        
                                            
                                        
                                    </td>
                                    <td class="font-style"><?php echo e($customer['name']); ?></td>
                                    <td><?php echo e($customer['contact']); ?></td>
                                    <td><?php echo e($customer['email']); ?></td>
                                    <td><?php echo e(\Auth::user()->priceFormat($customer['balance'])); ?></td>
                                    <td>
                                        <?php echo e((!empty($customer->last_login_at)) ? $customer->last_login_at : '-'); ?>

                                    </td>
                                    <td class="Action">
                                        <span>
                                        <?php if($customer['is_active']==0): ?>
                                                <i class="ti ti-lock" title="Inactive"></i>
                                            <?php else: ?>
                                                
                                                <div class="action-btn  ms-2">
                                                    <a href="<?php echo e(url('admin/customers',\Crypt::encrypt($customer['id']))); ?>" class="mx-3 bg-info btn btn-sm align-items-center"
                                                       data-bs-toggle="tooltip" title="<?php echo e(__('View')); ?>">
                                                        <i class="las la-eye text-white text-white"></i>
                                                    </a>
                                                </div>
                                                
                                                
                                                    <div class="ms-2">
                                                        <a href="#" class="mx-3 bg-primary  btn btn-sm  align-items-center" data-url="<?php echo e(url('admin/customers/'.$customer['id'].'/edit')); ?>" data-ajax-popup="true"  data-size="lg" data-bs-toggle="tooltip" title="<?php echo e(__('Edit')); ?>"  data-title="<?php echo e(__('Edit Customer')); ?>">
                                                            <i class="las la-edit text-white"></i>
                                                        </a>
                                                    </div>

                                                



                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete customer')): ?>
                                                    <div class=" ms-2">
                                                        <?php echo Form::open(['method' => 'DELETE', 'url' => ['customer.destroy', $customer['id']],'id'=>'delete-form-'.$customer['id']]); ?>

                                                        <a href="#" class="mx-3 bg-danger btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="<?php echo e(__('Delete')); ?>" ><i class="las la-trash text-white text-white"></i></a>
                                                        <?php echo Form::close(); ?>

                                                    </div>
                                                <?php endif; ?>

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/customer/index.blade.php ENDPATH**/ ?>