<?php
	$this->load->view('letters/header_letter');
?>

	<div class = "row">
		<div class = "col-12">
			<span class = "float-right" style = "margin-right: 30px;font-family:'arial';font-weight: lighter">जा.क्र.मनपा / साबां / &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp  / <?= date('Y').'-'.date('y', strtotime('+1 year')); ?></span>
			<p></p>
		</div>
		<br>
		<div class = "col-12">
			<span class = "float-right" style = "margin-right: 132px;font-family:'arial',font-weight: lighter">दि.&nbsp&nbsp&nbsp&nbsp&nbsp/&nbsp&nbsp&nbsp&nbsp&nbsp / <?= date('Y'); ?></span>
		</div>
		<br><br><br>
		<div class = "col-12">
			<span style = "margin-left: 20px;font-family: 'arial';font-weight: lighter"><b>प्रति,</b></span><br>
			<span style = "margin-left: 20px;font-weight: bold;font-family: 'arial';font-weight: lighter">मा. व्यवस्थापक, </span>
		</div>
		<br><br><br>
		<div class = "col-12 text-center">
			<span style = "font-family: 'arial';font-weight: bold"><b>विषय :- </b> मुदतवाढ बाबत.</span><br><br>
			<span style = "font-family: 'arial';margin-right: 28%;"><b>संदर्भ :-</b></span>
		</div>
		
		<div class = "col-12 text-center">
			<br><br>
			<span style = "font-family: 'arial';font-weight: bold">मुदतवाढ कालावधी</span>
		</div>

		<div class = "col-12 text-center">
			<br><br><br>
			<table class = "table table-bordered" style="width:100%">
				<tr class = "text-center">
					<th>कामाचे ठिकाण</th>
					<th>दिनांक पासून</th>
					<th>दिनांक पर्यंत</th>
					<th>मुदतवाढीचे कारण</th>
					<th>शेरा</th>
				</tr>
				<tr class = "text-center">
					<td><?= $appData->road_name; ?></td>
					<td><?= $extensionData->date; ?></td>
					<td><?= $extensionData->to_date;; ?></td>
					<td><?= $extensionData->description; ?></td>
					<td>test</td>
				</tr>
			</table>
		</div>

		<?php
			$this->load->view('letters/sign_section');
		?>
        <br><br>
	</div>
</div>
	
</body>
</html>
