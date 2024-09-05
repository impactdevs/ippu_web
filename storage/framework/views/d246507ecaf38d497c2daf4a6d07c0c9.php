<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificate of Completion - <?php echo e($event->code); ?></title>
</head>
<body style="background-color: #f5f5f5; font-family: sans-serif; padding: 20px;">

    <div style="background-color: #0044cc; padding: 20px; text-align: center; color: white;">
        <img src="<?php echo e(url('/logo.png')); ?>" alt="IPPU Membership App Logo" width="200" height="100">
        <h1 style="margin-top: 10px;">Certificate of Completion</h1>
    </div>

    <div style="padding: 20px;">
        <p>Dear <?php echo e($user->name); ?>,</p>
        <p>Congratulations on successfully completing the <strong><?php echo e($event->topic); ?></strong> course!</p>
        <p>We are pleased to present you with this certificate of completion. Your dedication and hard work are truly appreciated.</p>
        <p>Below are the details of the course:</p>
        <ul>
            <li><strong>Course Code:</strong> <?php echo e($event->code); ?></li>
            <li><strong>Course Dates:</strong> <?php echo e($formattedRange); ?></li>
            <li><strong>CPD Hours:</strong> <?php echo e($event->hours); ?> CPD HOURS</li>
        </ul>
    </div>

    <div style="padding: 20px;">
        <p>We hope this certificate serves as a testament to your commitment to professional development.</p>
    </div>

    <div style="padding: 20px; text-align: right;">
        <p>Warm regards,</p>
        <p>The IPPU Membership App Team</p>
    </div>

    <div style="padding: 20px; background-color: #0044cc; text-align: center; color: white;">
        <p>Don't miss out on exclusive deals and member benefits! Download the IPPU Membership App today!</p>
        <a href="https://apps.apple.com/th/app/ippu-membership-app/id6467385648" style="color: white">Download IPPU Membership App from App Store</a><br>
        <a href="https://play.google.com/store/apps/details?id=com.impact.ippu" style="color: white">Download IPPU Membership App from Play Store</a>
    </div>
</body>
</html>
<?php /**PATH /Users/katendenicholas/Desktop/laravel/ippu_web/resources/views/mails/certificate.blade.php ENDPATH**/ ?>