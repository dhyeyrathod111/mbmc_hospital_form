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
		<div class = "col-12">
			<span style = "margin-left: 20px;font-family: 'arial';font-weight: lighter"><b>प्रति,</b></span><br>
			<span style = "margin-left: 20px;font-weight: bold;font-family: 'arial';font-weight: lighter">मा. व्यवस्थापक, </span>
		</div>
		<br><br><br>
		<div class = "col-12 text-center">
			<span style = "font-family: 'arial';font-weight: bold"><b>विषय :- </b> अतिरिक्त लांबी मोजणी बाबत.</span><br><br>
			<span style = "font-family: 'arial';margin-right: 28%;"><b>संदर्भ :-</b></span>
		</div>

		<div class = "col-12">
		<br><br><br>
			<p style = "font-family: 'arial';font-weight: lighter">     वरील विषयानुसार मिरा भाईंदर महानगरपालिकेतील क्षेत्रात <?= '<u>'.$appData->road_name.'</u>'; ?> ठिकाणी आपणांस रस्ता खोदाई परवानगी देण्यांत आली आहे. सदर परवानगीचे मुळ मोजमाप <?php if(!empty($totallength)){ echo '<u>'.$totallength[0]['length'].'</u>'; } else { echo '_________';} ?> र.मि. असून आपले समवेत स्थळ निरीक्षण केले असता सदरील रस्ता खोदाई लांबी <?php if(!empty($addlength)){ echo '<u>'.$addlength[0]['length'].'</u>'; } else { echo '_________';} ?> र.मि. एवढी लांबी वाढली आहे. तरी सदर वाढीव लांबी करीता पुन्हा त्वरीत नविन अर्ज करावा. व त्याची खोदाई रक्कम त्वरीत महानगरपालिकेत भरणा करून रितसर परवानगी प्राप्त करून घ्यावी.</p>
		</div>

		<?php
			$this->load->view('letters/sign_section');
		?>
		<br><br><br>
	</div>
</div>
</body>
</html>
