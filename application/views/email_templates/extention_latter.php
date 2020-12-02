<!DOCTYPE html>
<html>
<head>
	<title>Extention latter</title>
</head>
<body>
	<?php if (empty($is_rejected)) : ?>
		<p>Dear <?= $application->applicant_name ?>,</p>
		<p>This is to inform that your request for extension of work with regard to the Application No: MBMC-00000<?= $application->app_id ?> has been approved.</p>
		<p>Click on the below link for the extension letter</p>
		<p><a href="<?= $file_url ?>">Extention latter</a></p>
		<p>Thanks and Regards,</p>
		<p>PWD Department</p>
		<p>MBMC</p>
		<p>For more information login to the MBMC portal:</p>
		<p><a href="<?= base_url() ?>">MBMC</a></p>
	<?php else : ?>
		<p>Dear <?= $application->applicant_name ?>,</p>
		<P>This is to inform that your request for extension of work with regard to the Application No: MBMC-00000<?= $application->app_id ?> has been rejected. </P>
		<p>Thanks and Regards,</p>
		<p>PWD Department</p>
		<p>MBMC</p>
		<p>For more information login to the MBMC portal:</p>
		<p><a href="<?= base_url() ?>">MBMC</a></p>
	<?php endif ; ?>
</body>
</html>




