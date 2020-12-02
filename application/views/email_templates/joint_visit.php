<!DOCTYPE html>
<html>
<head>
	<title>joitn visit</title>
</head>
<body>
	<p>Dear <?= $application->applicant_name ?> </p>
	<br /><br />
	<p>This is to inform that with regard to your Application No:MBMC-00000<?= $application->app_id ?> there is a Joint Visit Meeting scheduled by the MBMC authority on <?= $this->input->post('jv_date') ?>.</p>
	<p>You are requested to kindly be available on the above mentioned date.</p>
	<br />
	<p>Thanks and Regards,</p>
	<p>PWD Department</p>
	<p>MBMC</p>
	<p>For more information login to the MBMC portal:</p>
	<p><a href="<?= base_url() ?>">MBMC</a></p>
</body>
</html>