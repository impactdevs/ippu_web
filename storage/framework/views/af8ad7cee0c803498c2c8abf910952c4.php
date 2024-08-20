<?php $__env->startSection('breadcrumb'); ?>
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
	<h4 class="mb-sm-0">Member Details</h4>
	<div class="page-title-right">
		<ol class="breadcrumb m-0">
			<li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
			<li class="breadcrumb-item"><a href="<?php echo e(url('admin/members')); ?>">Members</a></li>
			<li class="breadcrumb-item active"><?php echo e($member->name); ?></li>
		</ol>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
	<div class="col-xl-4">
		<div class="card">
			<div class="card-header bg-primary">
				<h5 class="text-white">Personal Information</h5>
			</div>
			<div class="card-body">
				<div class="mb-3">
					<h6 class="text-bold-700">Name</h6>
					<div><?php echo e($member->name); ?></div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Membership Number</h6>
					<div><?php echo e(($member->membership_number) ? $member->membership_number : 'Not Provided'); ?></div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Contacts</h6>
					<div><?php echo e($member->phone_no); ?> / <?php echo e($member->alt_phone_no); ?></div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Email</h6>
					<div><?php echo e($member->email); ?></div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Type</h6>
					<div><?php echo e($member?->account_type?->name); ?></div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Since</h6>
					<div><?php echo e(date('d M, y',strtotime($member->created_at))); ?> <span class="badge bg-secondary"><?php echo e($member->status); ?></span></div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">Status</h6>
					<div>
						<?php if($member?->latestMembership?->status == "Approved"): ?>
									<span class="badge bg-success">Subscribed</span>
								<?php endif; ?>
					</div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">NOK Name</h6>
					<div><?php echo e(($member->nok_name)?:"(blank)"); ?></div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">NOK Contacts</h6>
					<div><?php echo e(($member->nok_phone_no)?:"(blank)"); ?> </div>
				</div>
				<div class="mb-3">
					<h6 class="text-bold-700">NOK Email</h6>
					<div><?php echo e(($member->nok_address)?:"(blank)"); ?></div>
				</div>
			</div>
			<div class="card-footer d-flex flex-column flex-md-row align-items-md-center justify-content-between">

				<?php if($member->status == "Inactive"): ?>
				<a href="javascript:void(0);" class="btn btn-primary" title="<?php echo e(__('Change Account Status')); ?>" data-bs-toggle="tooltip" data-url="<?php echo e(url('admin/change_member_status/'.$member->id)); ?>" data-ajax-popup="true" data-size="lg">Activate Account</a>
				<?php elseif($member->status == "Active"): ?>
				<a href="javascript:void(0);" class="btn btn-warning btn-sm" title="<?php echo e(__('Change Account Status')); ?>" data-bs-toggle="tooltip" data-url="<?php echo e(url('admin/change_member_status/'.$member->id)); ?>" data-ajax-popup="true" data-size="lg">Suspend Account</a>
				<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('make admin')): ?>
				<a href="<?php echo e(url('admin/change_account_type/Admin/'.$member->id)); ?>" class="btn btn-danger btn-sm">Make Member Admin</a>
				<?php endif; ?>
				<?php else: ?>
				<a href="javascript:void(0);" class="btn btn-primary" title="<?php echo e(__('Change Account Status')); ?>" data-bs-toggle="tooltip" data-url="<?php echo e(url('admin/change_member_status/'.$member->id)); ?>" data-ajax-popup="true" data-size="lg">Unsuspend Account</a> 
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="col-xl-8">
		<div class="card">
			<div class="card-body">
				<ul class="nav nav-tabs nav-justified mb-3" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" data-bs-toggle="tab" href="#tab-cps" role="tab" aria-selected="false">
							CPDs
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#tab-events" role="tab" aria-selected="false">
							Events
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#tab-education" role="tab" aria-selected="false">
							Education
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-bs-toggle="tab" href="#tab-employment" role="tab" aria-selected="true">
							Employment
						</a>
					</li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content  text-muted">
					<div class="tab-pane active" id="tab-cps" role="tabpanel">
						<h6>CPDs</h6>
						<div class="table-responsive">
							<table class="table table-striped dataTable">
								<thead>
									<th>Name</th>
									<th>Points</th>
									<th>Duration</th>
									<th>Type</th>
									<th>Date</th>
								</thead>
								<tbody>
									<?php $__currentLoopData = $member->cpd_attendences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<tr>
											<td><?php echo e($attendence?->cpd?->topic); ?></td>
											<td><?php echo e($attendence?->cpd?->points); ?></td>
											<td><?php echo e(date('d M, Y',strtotime($attendence?->cpd?->start_date)).' - '. date('d M, Y',strtotime($attendence?->cpd?->end_date))); ?></td>
											<td><?php echo e($attendence?->cpd?->type); ?></td>
											<td><?php echo e(date('d M, Y',strtotime($attendence->created_at))); ?></td>
										</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane" id="tab-events" role="tabpanel">
						<h6>Events</h6>
						<div class="table-responsive">
							<table class="table table-striped dataTable">
								<thead>
									<th>Name</th>
									<th>Duration</th>
									<th>Date</th>
								</thead>
								<tbody>
									<?php $__currentLoopData = $member->event_attendences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<tr>
											<td><?php echo e($attendence?->event?->name); ?></td>
											<td><?php echo e(date('d M, Y',strtotime($attendence?->event?->start_date)).' - '. date('d M, Y',strtotime($attendence?->event?->end_date))); ?></td>
											<td><?php echo e(date('d M, Y',strtotime($attendence->created_at))); ?></td>
										</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="tab-pane" id="tab-education" role="tabpanel">
						<h6>Education</h6>
						<?php $__currentLoopData = $member->education; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $educ): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<div class="mb-4">
								<h5><?php echo e($educ->title); ?></h5>
								<h6><?php echo e($educ->field); ?></h6>
								<div>
									<?php echo $educ->description; ?>

								</div>
								<div class="text-end">
									<?php echo e(date('d M, y',strtotime($educ->start_date))); ?> - <?php echo e(date('d M, y',strtotime($educ->end_date))); ?>

								</div>
							</div>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
					<div class="tab-pane" id="tab-employment" role="tabpanel">
						<h6>Employment Experience</h6>
						<?php $__currentLoopData = $member->employment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $educ): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<div class="mb-4">
								<h5><?php echo e($educ->title); ?></h5>
								<h6><?php echo e($educ->position); ?></h6>
								<div>
									<?php echo $educ->description; ?>

								</div>
								<div class="text-end">
									<?php echo e(date('d M, y',strtotime($educ->start_date))); ?> - <?php echo e(date('d M, y',strtotime($educ->end_date))); ?>

								</div>
							</div>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</div>
				</div>
			</div><!-- end card-body -->
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ippu/resources/views/admin/members/show.blade.php ENDPATH**/ ?>