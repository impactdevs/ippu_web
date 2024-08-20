<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
</head>
<body>
<h1 style="text-align:center">
	SCAN TO ATTEND
</h1>
<div class="qrcode" style="text-align:center;">
    <img src="data:image/png;base64, <?php echo e(base64_encode($qrCode)); ?>" width="50%" alt="QR Code">
</div>
</body>
</html><?php /**PATH /var/www/ippu.org/resources/views/members/attendence/qrcode.blade.php ENDPATH**/ ?>