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
                    <h6 class = "text-center"><b>डॉ.बाबासाहेबआंबेडकर भवन, भाईंदर (पश्‍चिम), ता.जि.ठाणे ४०११०१</h6>
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
                <p>प्रति, <?= $application->applicant_name ?></p>
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
                <p>७) वाहतुक शाखा, काशिमिरा यांचे कडील जाक/वा.शा./ना-हरकत/ /२०२०, दि. /  /२०२० रोजीचे ना-हरकत पत्र.</p>
                <p>८) स्थानिक पोलिस स्टेशन यांचे कडील भाईंदर पो. स्टे.जा. क्र. १८५/२०२०.दि. ०५/०३/२०२० रोजीचे ना  हरकत पत्र</p>

            </div>
        </div>



        <br />       
    </div>
</body>
</html>