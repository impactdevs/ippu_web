<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WELCOME TO IPPU MEMBERSHIP APP</title>
</head>
<body>
    <h1>WELCOME TO IPPU MEMBERSHIP APP</h1>
    <p>Dear <?php echo e($name); ?>,</p>
    <p>Thank you for registering with us. Your Email Verification Code is <strong><?php echo e($code); ?>.</strong></p>
    <p>Please use the code to comfirm your email.</p>
    <p>Thank you</p>
    <p>IPPU Membership App</p>
</body>
</html>
<?php /**PATH /var/www/html/ippu/resources/views/emails/verify-email.blade.php ENDPATH**/ ?>