<!DOCTYPE html>
<html lang = "mr">
<head>
    <title>medical certificate</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .A4 {
              background: white;
              width: 21cm;
              /* height: 29.7cm; */
              display: block;
              margin: 0 auto;
              padding: 10px 25px;
              margin-bottom: 0.5cm;
              box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
              box-sizing: border-box;
              font-size: 12pt;
            }

            @media print {
              .page-break {
                display: block;
                page-break-before: always;
              }
              size: A4 portrait;
            }

            @media print {
              body {
                margin: 0;
                padding: 0;
              }
              .A4 {
                box-shadow: none;
                margin: 0;
                width: auto;
                height: auto;
              }
              .noprint {
                display: none;
              }
              .enable-print {
                display: block;
              }
            }
    </style>
</head>
<body>  
    <div class = "A4">
        <div class = "row">
            <div class = "col-2">
                <img src="<?= base_url('assets/images/mbmc_offical') ?>/logo.png" style = "margin-top: 10px;">
            </div>
            <div class = "col-10">
                <div>
                    <h2 class = "text-center"><b>मिरा-भाईंदर महानगरपालिका</b></h2>
                    <h6 class = "text-center">प्रभाग कार्यालय क्र.२</h6>
                    <h6 class = "text-center"><b>डॉ.बाबासाहेबआंबेडकर भवन, भाईंदर (पश्‍चिम), ता.जि.ठाणे ४०११०१</b></h6>
                </div>
            </div>
        </div>
        <hr style = "border: 1px solid #000;" />
        <div class="row mt-2">
            <div class="col-4">
                <p>जा.क्र.मनपा/प्र.का.क्र-२/</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4">
                <p> दिनांक:- /  / </p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-6">
                <p>प्रति,</p>
                <p><?= $application->applicant_name ?></p>
                <p><?= $application->applicant_address ?></p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 text-center">
                <p> घिषय :- मंडप लावणेकरीता परवानगी बाबत.</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <p> संदर्भ :-</p>
                <p>१) मुंबई प्रांतिक महानगरपालिका अधिनियम १९४९ चे कलम २३४ अन्वये. </p>
                <p>२) मा.नगरसेवक श्री.मिलन म्हात्रे यांनी मा.उच्च न्यायालय, मुंबई येथे दाखल केलेल्या जनहित याचिका क्र.४५/२००९ च सदर याचिकेत दि.२६/०२/२००९ रोजी मा.उच्च न्यायालयाच्या खंडपीठाने पारीत केलेले आदेश.</p>
                <p>३) दावा क्र.आर.सी.एस.४३४/१९९८ यात मा.न्यायालय, ठाणे यांनी दि.२१/१२/२००५ रोजीच्या आदेशान्वये दिलेले निर्देशन.</p>
                <p>४) मा.महासभा, दि.०५/०९/२०१८, प्रकरण क्र.७२, ठराव क्र.७३.</p>
                <p>५) मा.आयूक्त सो, यांचेकडील जा.क्र.मनपा/आयुक्‍्त/१४८/९-१० दि.१८/८/२००९ रोजीचा आदेश. </p>
                <p>६) <?= $application->applicant_name ?> मराठी बाणा सामाजिक संस्था यांचा दि. <?= date('d-m-Y',strtotime($application->created_at)) ?> रोजीचा अर्ज.</p>
                <p>७) वाहतुक शाखा, काशिमिरा यांचे कडील जाक/वा.शा./ना-हरकत/ /२०२०, दि. <?= date('d-m-Y',strtotime($application->date_traffic_of_noc)) ?> रोजीचे ना-हरकत पत्र.</p>
                <p>८) निक पोलिस स्टेशन यांचे कडील भाईंदर पो. स्टे.जा. क्र. १८५/२०२०.दि. ०५/०३/२०२०रोजीचे  ना  इतका भरणा करावा लागेल.</p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
            	<p>मंडप स्थळ :- <?= $application->booking_address ?></p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="row text-center">
            	<p>वरोल विषयासंग्रंधी कळविण्यात येदे. की, मिरा भाईंदर महानगरपालिका क्षेत्रात Applicant Name  यांनी  <?= $application->reason ?> दि.<?= $application->booking_date ?>  रोजी मंडप लावणेस खालील अटी / शर्तीवर परवानगी देण्यात येत आहे.  </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
            	<p>अटी शती:</p>
            	<p>१. मंडप उभारतांना सार्वजनिक रस्ते वा पदपथावर खणण्याऐवजी डम च मोठ्या पिंपात वजनदार वस्तुंच्या सहाय्याने यांबूना आधारे देवून याष्दारे मंडप तयार करण्यांत यावा.</p>
            	<p>२. मंडपा बाहेर जाहिरातीचे फलक व बोर्ड उभारणेसाठी महानगरपालिकेच्या वतीने एक फलक/बो्डसाठीच देण्यांत येईल.चरयानगीशिवाय अन्य बार्ड/बेंनर दर्शनी भागावर लावल्यास नियमानुसार कार्यवाही केली जाईल.</p>
            	<p>३. रहदारीस/पाहतुकीस अडथळा निर्माण होणार नाही, याची दक्षता घ्यावी.</p>
            	<p>४. प्रस्तावित मंडप/स्टेजची रुंदी रस्त्याच्या १/४ पेक्षा अधिक असणार नाही व सार्वजनिक वाहतुकीस अडथळा येणार नाही.व नागरिकांना त्यापासून उपद्रप तसेच आक्षेपार्ह होणार नाही याची दक्षता संबंघिल मंडळांनी घ्यावयाची आहे.</p>
            	<p>५. पाणी टँकर / रूग्णघाहिका व रहदारीस रस्ता बंद कस नये.</p>
            	<p>६. पोलिस खात्याकडून, वाहतुक शाखेकडुन तसेच इतर शासकीय खात्याकडून परवानगी घेण्याची जबाबदारी आपणांवर राहील.</p>
            	<p>७. अग्निशमन परवाना घेण्याची जबाबदारी आपली राहील.</p>
            	<p>८. शांतता द सुव्यवस्था अंगं होणार नाही, याची दक्षता घ्यावी.</p>
            	<p>१. दि. <?= $application->booking_date ?>  रोजी कार्यक्रम संपल्यांवर मंडप काढण्यांत यावा, अन्यथा महानगरपालिकेमार्फत कार्यवाही करण्यांत येईल.</p>
            	<p>१०. मंडप फी एकदा महापालिकेकडे भरणा केल्यास त्या रकमेचा परतावा केला जाणार नाही,याची नोद घ्यावी.</p>
            	<p>११. ध्वनीक्षेपका बाबतची परवानगी पोलिस विभागाकडुन घेणे बंधनकारक राहील.</p>
            	<p>१२. वरील अटीशतींचे उल्लंघन केल्यास तसेंथ मंडपाबाबत तक्रारी प्राप्त झाल्यास सदरील परचानगी रद्द करण्यांत येईल, याची स्पष्ट नोंद घ्यावी.</p>
            	<p>१३. सदरील मंडप लाकडी बांबूचा बांधावा. मंडपाचे भाडे रक्‍कम रु.<?= $payment_stack->amount ?>/- इतका भरणा करावा लागेल.</p>
            </div>
        </div>



        <div class="row mt-2">
            <div class="col-4"></div>
            <div class="col-4"></div>
            <div class="col-4 text-right">
            	<p>प्रभाग अधिकारी</p>
            	<p>प्रभाग कार्यालय क्र.र </p>
            	<p>मिरा भाईंदर महानगरपालिका</p>
            </div>
        </div>



        <br />       
    </div>
</body>
</html>