<?php $__env->startSection('content'); ?>
<div class="text-end mb-3">
	<?php if(\Auth::user()->user_type == "Admin"): ?>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-newsletter">
        Add Newsletter
      </button>
      <?php endif; ?>
</div>
  <div class="row">
	<?php $__currentLoopData = $communications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $communication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
	<div class=" col-md-6">
        <div class="card">
            <h5 class="card-header"><?php echo e($communication->title); ?></h5>
            <div class="card-body">
              <h5 class="card-title"><?php echo e($communication->sub_title); ?></h5>
              <p class="card-text">
                <?php echo e($communication->description); ?>

              </p>
				<a href="<?php echo e(url('/admin/newsletter/'.$communication->id)); ?>" class="btn btn-primary">Newsletter Details</a>
                 <?php if(\Auth::user()->user_type == "Admin"): ?>
                
                <form action="<?php echo e(url('/admin/send_newsletter/' . $communication->id)); ?>" method="POST" style="display: inline;"
                    class="m-0 p-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success">Send Newsletter</button>
                </form>
                <?php endif; ?>
            </div>
          </div>
	</div>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="modal" tabindex="-1" id="add-newsletter">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Newsletter</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="card" method="POST" id="newsletterForm">
            <?php echo csrf_field(); ?>
            <div class="card-body row">
                <div class="form-group mb-3">
                    <label>Title</label>
                    <input type="text" class="form-control" name="title">
                </div>

                <div class="form-group mb-3">
                    <label>Sub-title</label>
                    <input type="text" class="form-control" name="sub_title">
                </div>

                <div class="form-group mb-3">
                    <label>Message</label>
                    <textarea class="form-control" id="message" maxlength="200" name="description"></textarea>
                    <div id="charCount">0 / 200</div>
                </div>

                <div class="form-group mb-3">
                    <label>Newsletter File(.pdf format)</label>
                    <input type="file" class="form-control" name="newsletter_file">
                </div>

            </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveNewsletterBtn">Save</button>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('customjs'); ?>
 <script>
$(document).ready(function() {
    // Add a click event handler to the "Save" button
    $('#saveNewsletterBtn').on('click', function() {
        console.log('making a request');

        // Create a FormData object to handle form fields and files
        var formData = new FormData($('#newsletterForm')[0]);

        // Make a POST request to the server
        $.ajax({
            url: '/admin/newsletter',
            method: 'POST',
            data: formData,
            processData: false,  // Important: prevent jQuery from processing the data
            contentType: false,  // Important: let the server handle the content type
            success: function(response) {
                console.log("response:", response);
                // If the request is successful, hide the modal
                $('#staticBackdrop').modal('hide');
                // Reload the page
                location.reload();
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
});

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/communications/newsletter.blade.php ENDPATH**/ ?>