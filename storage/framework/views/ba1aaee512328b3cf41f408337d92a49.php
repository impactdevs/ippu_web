<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <?php echo e($newsletter->title); ?>

        </div>
        <div class="card-body">
            <?php echo $newsletter->description; ?>

        </div>
        <div class="card-footer">
            <a class="btn btn-primary" href="<?php echo e(url('/admin/download_newsletter/' . $newsletter->id)); ?>">Download Newsletter</a>
            <?php if(Auth::user()->user_type == 'Admin'): ?>
            
            <form action="<?php echo e(url('/admin/delete_newsletter/' . $newsletter->id)); ?>" method="POST" style="display: inline;"
                class="m-0 p-0">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger"><?php echo app('translator')->get('Delete'); ?></button>
            </form>

            
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit-newsletter">
                Edit Newsletter
            </button>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="modal" tabindex="-1" id="edit-newsletter">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Newsletter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="card" id="updateNewsletterForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?> 
                    <div class="card-body row">
                        <div class="form-group mb-3">
                            <label>Title</label>
                            <input type="text" class="form-control" name="title" value="<?php echo e($newsletter->title); ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label>Sub-title</label>
                            <input type="text" class="form-control" name="sub_title"
                                value="<?php echo e($newsletter->sub_title); ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label>Message</label>
                            <textarea class="form-control" id="message" maxlength="200" name="description"><?php echo e($newsletter->description); ?></textarea>
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
                    <button type="button" class="btn btn-primary" id="updateNewsletterBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('customjs'); ?>
    <script>
        $(document).ready(function() {
            // Update Newsletter
            $('#updateNewsletterBtn').click(function(e) {
                e.preventDefault();

                var formData = new FormData($('#updateNewsletterForm')[0]); // Use [0] to select the form
                //send a post request to the server
                $.ajax({
                    url: "<?php echo e(url('/admin/update_newsletter/' . $newsletter->id)); ?>",
                    type: 'POST', // Change method to type
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);
                        // Hide the modal manually
                        $('#edit-newsletter').modal('hide');
                        // Reload the current page
                        window.location.reload();
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/ippu.org/resources/views/communications/newsletter_details.blade.php ENDPATH**/ ?>