<?php $__env->startSection('breadcrumb'); ?>
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0">Events</h4>

        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="<?php echo e(url('dashboard')); ?>">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(url('admin/events')); ?>">Events</a></li>
                <li class="breadcrumb-item active"><?php echo e($event->name); ?></li>
            </ol>
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h4 class=""><?php echo e($event->name); ?></h4>
                <!-- Nav tabs -->
                <ul class="nav nav-pills nav-justified mb-3 bg-light" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-bs-toggle="tab" href="#pill-justified-home-1" role="tab">
                            Details
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-profile-1" role="tab">
                            Pending Confirmation (<?php echo e($event->pending_confimation->count()); ?>)
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-messages-1" role="tab">
                            Confirmed (<?php echo e($event->confirmed->count()); ?>)
                        </a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#pill-justified-settings-1" role="tab">
                            Attended (<?php echo e($event->attended_event->count()); ?>)
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content text-muted">
                    <div class="tab-pane active" id="pill-justified-home-1" role="tabpanel">
                        <div class="row">
                            <div class="col-md-7">
                                <?php echo $event->details; ?>

                                <div class="p-1 mb-2 bg-light">
                                    <div class="d-flex flex-row align-items-center justify-content-between">
                                        <div>
                                            <h6 class="text-danger font-weight-bold fw-medium">Start Date</h6>
                                            <span><?php echo e(date('F j, Y, g:i a', strtotime($event->start_date))); ?></span>
                                        </div>
                                        <div>
                                            <h6 class="text-danger font-weight-bold fw-medium">End Date</h6>
                                            <span><?php echo e(date('F j, Y, g:i a', strtotime($event->end_date))); ?></span>
                                        </div>
                                        <div>
                                            <h6 class="text-warning font-weight-bold fw-medium">Rate</h6>
                                            <span><?php echo e($event->member_rate ? number_format($event->member_rate) : 'Free'); ?></span>
                                        </div>
                                        <?php if($event->points): ?>
                                            <div>
                                                <h6 class="text-warning font-weight-bold fw-medium">Points</h6>
                                                <span><?php echo e($event->points); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <a href="<?php echo e(asset('storage/attachments/' . $event->attachment_name)); ?>"
                                    class="btn btn-warning btn-sm" download>Download Resource</a>
                                <a href="<?php echo e(url('generate_qr/event/' . $event->id)); ?>"
                                    class="btn btn-danger btn-sm ms-4">Generate QR Code</a>
                            </div>
                            <div class="col-md-5">
                                <img class="card-img-top img-fluid image"
                                    src="<?php echo e(asset('storage/banners/' . $event->banner_name)); ?>" alt="<?php echo e($event->name); ?>"
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
                                    <?php $__currentLoopData = $event->pending_confimation; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($attendence?->user?->name); ?></td>
                                            <td><?php echo e($attendence?->user?->phone_no); ?></td>
                                            <td><?php echo e($attendence?->user?->email); ?></td>
                                            <td>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve event attendence')): ?>
                                                    <a href="<?php echo e(url('admin/events/attendence/' . $attendence->id . '/Confirmed')); ?>"
                                                        class="btn btn-sm btn-primary">
                                                        Book Attendence
                                                    </a>

                                                    <a href="<?php echo e(url('admin/events/attendence/' . $attendence->id . '/Attended')); ?>"
                                                        class="btn btn-sm btn-danger">
                                                        Confirm Attendence
                                                    </a>
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
                                    <?php $__currentLoopData = $event->confirmed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($attendence?->user?->name); ?></td>
                                            <td><?php echo e($attendence?->user?->phone_no); ?></td>
                                            <td><?php echo e($attendence?->user?->email); ?></td>
                                            <td>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approve event attendence')): ?>
                                                    <a href="<?php echo e(url('admin/events/attendence/' . $attendence->id . '/Attended')); ?>"
                                                        class="btn btn-sm btn-primary">
                                                        Confirm Attendence
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

                        <form action="<?php echo e(route('events.bulkDownload')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="event_id" value="<?php echo e($event->id); ?>">
                            <button type="submit" class="btn btn-primary mb-3">Download Bulk Certificates</button>
                        </form>

                            <form action="<?php echo e(route('events.bulkEmail')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="event_id" value="<?php echo e($event->id); ?>">
                            <button type="submit" class="btn btn-primary mb-3">Bulk Email Cerificates</button>
                        </form>
                        <!-- Add New Attendee Button -->
                        <div class="d-flex justify-content-end mb-3">
                            <button id="addNewAttendeeBtn" class="btn btn-success btn-sm">
                                Add New Attendee
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped dataTable">
                                <thead>
                                    <th>Name</th>
                                    <th>Contacts</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $event->attended_event; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($attendence?->user?->name); ?></td>
                                            <td><?php echo e($attendence?->user?->phone_no); ?></td>
                                            <td><?php echo e($attendence?->user?->email); ?></td>
                                            <td>
                                                <a href="<?php echo e(url('admin/events/attendence/' . $event->id . '/' . $attendence->user->id)); ?>"
                                                    class="btn btn-sm btn-primary mr-2 mb-2">
                                                    Email Certificate
                                                </a>
                                                <a href="<?php echo e(url('admin/events/download_certificate/' . $event->id . '/' . $attendence->user->id)); ?>"
                                                    class="btn btn-sm btn-warning mb-2">
                                                    Download Certificate
                                                </a>
                                                <!-- Edit Email Button -->
                                                <button class="btn btn-sm btn-info mb-2 edit-email-btn"
                                                    data-id="<?php echo e($attendence->id); ?>"
                                                    data-email="<?php echo e($attendence?->user?->email); ?>"
                                                    data-name="<?php echo e($attendence?->user?->name); ?>">
                                                    
                                                    Edit Details
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div><!-- end card-body -->
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('customjs'); ?>
    <script>
        $('#addNewAttendeeBtn').on('click', function() {
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
                preConfirm: function() {
                    var name = $('#attendeeName').val();
                    var email = $('#attendeeEmail').val();
                    var membershipNumber = $('#membershipNumber').val();

                    if (!name || !email) {
                        Swal.showValidationMessage('Please enter both name and email');
                        return false;
                    }

                    return {
                        name: name,
                        email: email,
                        membershipNumber: membershipNumber
                    };
                }
            }).then(function(result) {
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
                url: '<?php echo e(route('events.attendence.store')); ?>', // Make sure this route is correct
                type: 'POST',
                data: {
                    event_id: <?php echo e($event->id); ?>,
                    name: name,
                    email: email,
                    membership_number: membershipNumber
                },
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' // Include CSRF token for Laravel
                },
                beforeSend: function() {
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
                success: function(response) {

                    if (response.success) {
                        Swal.fire('Success', 'Attendee has been registered!', 'success').then(function() {
                            location.reload(); // Reload the page to see the changes
                        });
                    } else {
                        Swal.fire('Error', response.message || 'There was an error registering the attendee.',
                            'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to register attendee. Please try again later.', 'error');
                }
            });
        }
    </script>

<script>
    $(document).on('click', '.edit-email-btn', function() {
        var attendenceId = $(this).data('id');
        var currentEmail = $(this).data('email');
        var currentName = $(this).data('name'); // Retrieve the current name

        Swal.fire({
            title: 'Edit Attendee Details',
            html: `
            <form id="editEmailForm">
                <div class="mb-3">
                    <label for="attendeeName" class="form-label">Attendee Name</label>
                    <input type="text" id="attendeeName" class="form-control" required placeholder="New Name" value="${currentName}">
                </div>
                <div class="mb-3">
                    <label for="attendeeEmail" class="form-label">New Email</label>
                    <input type="email" id="attendeeEmail" class="form-control" required placeholder="New Email" value="${currentEmail}">
                </div>
            </form>
        `,
            showCancelButton: true,
            confirmButtonText: 'Update Details',
            preConfirm: function() {
                var newEmail = $('#attendeeEmail').val();
                var newName = $('#attendeeName').val(); // Get the new name

                if (!newEmail) {
                    Swal.showValidationMessage('Please enter a new email');
                    return false;
                }

                if (!newName) {
                    Swal.showValidationMessage('Please enter a new name');
                    return false;
                }

                return {
                    newEmail: newEmail,
                    newName: newName
                };
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                var data = result.value;
                // Send the data to the server to update the email and name
                updateAttendeeDetails(attendenceId, data.newEmail, data.newName);
            }
        });
    });

    function updateAttendeeDetails(attendenceId, newEmail, newName) {
        // Use jQuery AJAX to send the data to the server
        $.ajax({
            url: '<?php echo e(route('events.attendence.updateEmail')); ?>', // Ensure this route is correct
            type: 'POST',
            data: {
                attendence_id: attendenceId,
                email: newEmail,
                name: newName // Send the name along with the email
            },
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' // Include CSRF token for Laravel
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Updating Details...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', 'Details have been updated!', 'success').then(function() {
                        location.reload(); // Reload the page to see the changes
                    });
                } else {
                    Swal.fire('Error', response.message || 'There was an error updating the details.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Failed to update details. Please try again later.', 'error');
            }
        });
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/katendenicholas/Desktop/laravel/ippu_web/resources/views/admin/events/show.blade.php ENDPATH**/ ?>