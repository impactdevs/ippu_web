<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Leads')); ?> <?php if($pipeline): ?> - <?php echo e($pipeline->name); ?> <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('customcss'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/summernote/summernote-bs4.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/dragula.min.css')); ?>" id="main-style-link">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Leads</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Leads</li>
        </ol>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="text-end mb-3">


            <?php echo e(Form::open(array('url' => 'admin/deals_change_pipeline','id'=>'change-pipeline','class'=>'btn btn-sm '))); ?>

            <?php echo e(Form::select('default_pipeline_id', $pipelines,$pipeline->id, array('class' => 'form-control select','id'=>'default_pipeline_id'))); ?>

            <?php echo e(Form::close()); ?>



            <a href="<?php echo e(url('admin/leads_list')); ?>" data-size="lg" data-bs-toggle="tooltip" title="<?php echo e(__('List View')); ?>" class="btn btn-sm btn-primary">
                <i class="ri-list-check"></i>
            </a>
            <a href="#" data-size="lg" data-action="New" data-id="2323" data-title="Create New Lead" ajax-load="true" data-url="<?php echo e(url('admin/leads')); ?>" data-bs-toggle="tooltip" title="<?php echo e(__('Create New Lead')); ?>" class="btn btn-sm btn-primary">
                <i class="ri-add-fill"></i>
            </a>

    </div>
    <div class="row">
        <div class="col-sm-12">
            <?php
                $lead_stages = $pipeline->leadStages;
                $json = [];
                foreach ($lead_stages as $lead_stage){
                    $json[] = 'task-list-'.$lead_stage->id;
                }
            ?>
            <div class="row kanban-wrapper horizontal-scroll-cards" data-containers='<?php echo json_encode($json); ?>' data-plugin="dragula">
                <?php $__currentLoopData = $lead_stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead_stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($leads = $lead_stage->lead()); ?>
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <span class="btn btn-sm btn-primary btn-icon count">
                                        <?php echo e(count($leads)); ?>

                                    </span>
                                </div>
                                <h4 class="mb-0"><?php echo e($lead_stage->name); ?></h4>
                            </div>
                            <div class="card-body kanban-box" id="task-list-<?php echo e($lead_stage->id); ?>" data-id="<?php echo e($lead_stage->id); ?>">
                                <?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="card" data-id="<?php echo e($lead->id); ?>">
                                        <div class="pt-3 ps-3">
                                            <?php ($labels = $lead->labels()); ?>
                                            <?php if($labels): ?>
                                                <?php $__currentLoopData = $labels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="badge-xs badge bg-<?php echo e($label->color); ?> p-2 px-3 rounded"><?php echo e($label->name); ?></div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-header border-0 pb-0  d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                                            <h5><a href="<?php echo e(url('admin/leads/'.$lead->id)); ?>"><?php echo e($lead->name); ?></a></h5>
                                            <div class="card-header-right">
                                                <?php if(Auth::user()->type != 'Client'): ?>
                                                    <div class="btn-group card-option">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="las la-menu"></i></button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            
                                                                <a href="#!" data-size="md" data-url="<?php echo e(URL::to('admin/leads/'.$lead->id.'/labels')); ?>" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="<?php echo e(__('Labels')); ?>">
                                                                    <i class="ti ti-bookmark"></i>
                                                                    <span><?php echo e(__('Labels')); ?></span>
                                                                </a>

                                                                <a href="#!" data-size="lg" data-action="Update" data-id="<?php echo e($lead->id); ?>" data-title="Edit Lead" ajax-load="true" data-url="<?php echo e(url('admin/leads')); ?>" class="dropdown-item" data-bs-original-title="<?php echo e(__('Edit Lead')); ?>">
                                                                    
                                                                    <span><?php echo e(__('Edit')); ?></span>
                                                                </a>

                                                                <a href="#!" data-size="lg" data-url="<?php echo e(URL::to('admin/leads/'.$lead->id.'/edit')); ?>" data-ajax-popup="true" class="dropdown-item" data-bs-original-title="<?php echo e(__('Edit Lead')); ?>">
                                                                    <i class="las la-edit"></i>
                                                                    <span><?php echo e(__('Edit')); ?></span>
                                                                </a>
                                                            
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete lead')): ?>
                                                                <?php echo Form::open(['method' => 'DELETE', 'route' => ['leads.destroy', $lead->id],'id'=>'delete-form-'.$lead->id]); ?>

                                                                <a href="#!" class="dropdown-item bs-pass-para">
                                                                    <i class="ti ti-archive"></i>
                                                                    <span> <?php echo e(__('Delete')); ?> </span>
                                                                </a>
                                                                <?php echo Form::close(); ?>

                                                            <?php endif; ?>


                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php
                                        $products = $lead->products();
                                        $sources = $lead->sources();
                                        ?>
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <ul class="list-inline mb-0">

                                                    <li class="list-inline-item d-inline-flex align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Product')); ?>">
                                                        <i class="f-16 text-primary ti ti-shopping-cart"></i> <?php echo e(count($products)); ?>

                                                    </li>

                                                    <li class="list-inline-item d-inline-flex align-items-center" data-bs-toggle="tooltip" title="<?php echo e(__('Source')); ?>">
                                                        <i class="f-16 text-primary ti ti-social"></i><?php echo e(count($sources)); ?>

                                                    </li>
                                                </ul>
                                                <div class="user-group">
                                                    <?php $__currentLoopData = $lead->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <img class="rounded-circle header-profile-user" src="<?php echo e(asset('assets/images/users/avatar-1.jpg')); ?>" alt="<?php echo e($user->name); ?>" data-bs-toggle="tooltip" title="<?php echo e($user->name); ?>">
                                                        
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('customjs'); ?>
<script src="<?php echo e(asset('css/summernote/summernote-bs4.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/dragula.min.js')); ?>"></script>
    <script>
        !function (a) {
            "use strict";
            var t = function () {
                this.$body = a("body")
            };
            t.prototype.init = function () {
                a('[data-plugin="dragula"]').each(function () {
                    var t = a(this).data("containers"), n = [];
                    if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                    var r = a(this).data("handleclass");
                    r ? dragula(n, {
                        moves: function (a, t, n) {
                            return n.classList.contains(r)
                        }
                    }) : dragula(n).on('drop', function (el, target, source, sibling) {

                        var order = [];
                        $("#" + target.id + " > div").each(function () {
                            order[$(this).index()] = $(this).attr('data-id');
                        });

                        var id = $(el).attr('data-id');

                        var old_status = $("#" + source.id).data('status');
                        var new_status = $("#" + target.id).data('status');
                        var stage_id = $(target).attr('data-id');
                        var pipeline_id = '<?php echo e($pipeline->id); ?>';

                        $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                        $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);
                        $.ajax({
                            url: '<?php echo e(url('admin/leads_order')); ?>',
                            type: 'POST',
                            data: {lead_id: id, stage_id: stage_id, order: order, new_status: new_status, old_status: old_status, pipeline_id: pipeline_id, "_token": $('meta[name="csrf-token"]').attr('content')},
                            success: function (data) {
                            },
                            error: function (data) {
                                data = data.responseJSON;
                                show_toastr('error', data.error, 'error')
                            }
                        });
                    });
                })
            }, a.Dragula = new t, a.Dragula.Constructor = t
        }(window.jQuery), function (a) {
            "use strict";

            a.Dragula.init()

        }(window.jQuery);


    </script>
    <script>
        $(document).on("change", "#default_pipeline_id", function () {
            $('#change-pipeline').submit();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/admin/leads/index.blade.php ENDPATH**/ ?>