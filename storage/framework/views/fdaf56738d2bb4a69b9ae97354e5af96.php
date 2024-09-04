<?php $__env->startSection('breadcrumb'); ?>
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">CPDs</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(url('admin/cpds')); ?>">CPDs</a></li>
                <li class="breadcrumb-item active"><?php echo e($cpd->topic); ?></li>
            </ol>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-body">
            <p class="text-muted"><?php echo e($cpd->topic); ?></p>
            <!-- Nav tabs -->
            <ul class="nav nav-pills nav-justified mb-3 bg-light" role="tablist">
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab">Details</a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab">
                        Pending Confirmation (<?php echo e($cpd?->pending_confimation?->count()); ?>)
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-messages-1" role="tab">
                        Confirmed (<?php echo e($cpd?->confirmed?->count()); ?>)
                    </a>
                </li>
                <li class="nav-item waves-effect waves-light">
                    <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-settings-1" role="tab">
                        Attended (<?php echo e($cpd?->attended_event?->count()); ?>)
                    </a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content text-muted">
                <div class="tab-pane active" id="pill-justified-home-1" role="tabpanel">
                    <div class="row">
                        <div class="col-md-7">
                            <?php echo $cpd->content; ?>

                            <div class="p-1 mb-2 bg-light">
                                <div class="mb-3"><?php echo e($cpd->target_group); ?></div>
                                <div class="mb-3 d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">Location</h6>
                                        <span><?php echo e($cpd->location); ?></span>
                                    </div>
                                    <?php if($cpd->points): ?>
                                        <div>
                                            <h6 class="text-warning font-weight-bold fw-medium">Points</h6>
                                            <span><?php echo e(number_format($cpd->points)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex flex-row align-items-center justify-content-between">
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
                                        <span><?php echo e(date('F j, Y, g:i a', strtotime($cpd->start_date))); ?></span>
                                    </div>
                                    <div>
                                        <h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
                                        <span><?php echo e(date('F j, Y, g:i a', strtotime($cpd->end_date))); ?></span>
                                    </div>
                                    <div>
                                        <h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
                                        <span><?php echo e($cpd->member_rate ? number_format($cpd->member_rate) : 'Free'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <a href="<?php echo e(asset('storage/attachments/' . $cpd->resource)); ?>" class="btn btn-warning btn-sm"
                                download>Download Resource</a>
                            <a href="<?php echo e(url('generate_qr/cpd/' . $cpd->id)); ?>" class="btn btn-danger btn-sm ms-4">Generate QR
                                Code</a>
                        </div>
                        <div class="col-md-5">
                            <img class="card-img-top img-fluid image" src="<?php echo e(asset('storage/banners/' . $cpd->banner)); ?>"
                                alt="<?php echo e($cpd->topic); ?>"
                                onerror="this.onerror=null;this.src='https://ippu.or.ug/wp-content/uploads/2020/08/ppulogo.png';">
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="pill-justified-profile-1" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable">
                            <thead>
                                <th>Name</th>
                                <th>Contacts</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cpd->pending_confimation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($attendence->user->name); ?></td>
                                        <td><?php echo e($attendence->user->phone_no); ?></td>
                                        <td><?php echo e($attendence->user->email); ?></td>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve CPD attendence')): ?>
                                                <a href="<?php echo e(url('admin/cpds/attendence/' . $attendence->id . '/Confirmed')); ?>"
                                                    class="btn btn-sm btn-primary">
                                                    Book Attendance
                                                </a>
                                                <a href="<?php echo e(url('admin/cpds/attendence/' . $attendence->id . '/Attended')); ?>"
                                                    class="btn btn-sm btn-danger">
                                                    Confirm Attendance
                                                </a>
                                                <?php if($attendence->payment_proof): ?>
                                                    <a href="javascript:void(0);" class="btn-sm btn btn-warning"
                                                        data-url="<?php echo e(url('admin/view_payment_proof/' . $attendence->payment_proof)); ?>"
                                                        data-ajax-popup="true" data-bs-toggle="tooltip"
                                                        title="<?php echo e(__('Payment Proof')); ?>" class="btn btn-primary"
                                                        data-size="lg">View Payment Proof</a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane" id="pill-justified-messages-1" role="tabpanel">
                
                    <div class="table-responsive">
                        <table class="table table-striped dataTable">
                            <thead>
                                <th>Name</th>
                                <th>Contacts</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cpd->confirmed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($attendence->user->name); ?></td>
                                        <td><?php echo e($attendence->user->phone_no); ?></td>
                                        <td><?php echo e($attendence->user->email); ?></td>
                                        <td>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve CPD attendence')): ?>
                                                <a href="<?php echo e(url('admin/cpds/attendence/' . $attendence->id . '/Attended')); ?>"
                                                    class="btn btn-sm btn-primary">
                                                    Confirm Attendance
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane" id="pill-justified-settings-1" role="tabpanel">
                 <!-- Add New Attendee Button -->
                        <div class="d-flex justify-content-end mb-3">
                            <button id="addNewAttendeeBtn" class="btn btn-success btn-sm">
                                Add New Attendee
                            </button>
                        </div>

                    <div class="table-responsive">
                        <form id="bulk-actions-form" method="POST" action="<?php echo e(url('admin/cpds/bulk-email')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="cpd_id" value="<?php echo e($cpd->id); ?>">
                            <div class="mb-3">
                                <button type="submit" name="action" value="download" class="btn btn-danger">Download Bulk
                                    Certificates</button>
                            </div>
                        </form>

                        <form action="<?php echo e(route('cpds.bulkDownload')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="cpd_id" value="<?php echo e($cpd->id); ?>">
                            <input type="hidden" name="attendees[]" value="user1">
                            <input type="hidden" name="attendees[]" value="user2">
                            <!-- Add more hidden inputs for other user IDs as needed -->
                            <button type="submit">Download Certificates</button>
                        </form>

                        <table class="table table-striped dataTable">
                            <thead>
                                <th>
                                    <input type="checkbox" id="select-all">
                                </th>
                                <th>Name</th>
                                <th>Contacts</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cpd->attended_event; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="attendees[]"
                                                value="<?php echo e($attendence->user->id); ?>">
                                        </td>
                                        <td><?php echo e($attendence->user->name); ?></td>
                                        <td><?php echo e($attendence->user->phone_no); ?></td>
                                        <td><?php echo e($attendence->user->email); ?></td>
                                        <td>
                                            <a href="<?php echo e(url('admin/cpds/attendence/' . $cpd->id . '/' . $attendence->user->id)); ?>"
                                                class="btn btn-sm btn-primary mr-2 mb-2">
                                                Email Certificate
                                            </a>
                                            <a href="<?php echo e(url('admin/cpds/download_certificate/' . $cpd->id . '/' . $attendence->user->id)); ?>"
                                                class="btn btn-sm btn-warning mb-2">
                                                Download Certificate
                                            </a>
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

<?php $__env->startSection('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkboxes
            document.getElementById('select-all').addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('input[name="attendees[]"]').forEach(function(checkbox) {
                    checkbox.checked = isChecked;
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('customjs'); ?>
<script>
$('#addNewAttendeeBtn').on('click', function () {
    Swal.fire({
        title: 'Add New Attendee',
        html: `
            <form id="addAttendeeForm">
                <div class="mb-3">
                    <label for="attendeeName" class="form-label">Attendee Name</label>
                    <input type="text" id="attendeeName" class="form-control" required placeholder="Attendee Name">
                </div>
                <div class="mb-3">
                    <label for="attendeeEmail" class="form-label">Attendee Email</label>
                    <input type="email" id="attendeeEmail" class="form-control" required placeholder="Attendee Email">
                </div>
                <div class="mb-3">
                    <label for="membershipNumber" class="form-label">Membership Number (Optional)</label>
                    <input type="text" id="membershipNumber" class="form-control" placeholder="Membership Number">
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'Register Attendee',
        preConfirm: function () {
            var name = $('#attendeeName').val();
            var email = $('#attendeeEmail').val();
            var membershipNumber = $('#membershipNumber').val();

            if (!name || !email) {
                Swal.showValidationMessage('Please enter both name and email');
                return false;
            }

            return { name: name, email: email, membershipNumber: membershipNumber };
        }
    }).then(function (result) {
        if (result.isConfirmed) {
            var data = result.value;
            // Send the data to the server to register the attendee
            registerAttendee(data.name, data.email, data.membershipNumber);
        }
    });
});

function registerAttendee(name, email, membershipNumber) {
    // Use jQuery AJAX to send the data to the server
    $.ajax({
        url: '<?php echo e(route('cpds.attendence.store')); ?>', // Make sure this route is correct
        type: 'POST',
        data: {
            event_id: <?php echo e($cpd->id); ?>,
            name: name,
            email: email,
            membership_number: membershipNumber
        },
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' // Include CSRF token for Laravel
        },
        beforeSend: function () {
             //show swal loading
            Swal.fire({
                title: 'Registering Attendee...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        success: function (response) {

            if (response.success) {
                Swal.fire('Success', 'Attendee has been registered!', 'success').then(function () {
                    location.reload(); // Reload the page to see the changes
                });
            } else {
                Swal.fire('Error', response.message || 'There was an error registering the attendee.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Failed to register attendee. Please try again later.', 'error');
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/katendenicholas/Desktop/laravel/ippu_web/resources/views/admin/cpds/show.blade.php ENDPATH**/ ?>