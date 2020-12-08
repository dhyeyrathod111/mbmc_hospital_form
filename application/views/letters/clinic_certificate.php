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
                <img src="<?= base_url() ?>assets/images/mbmc_offical/logo.png" style = "margin-top: 10px;">
            </div>
            <div class = "col-10">
                <div>
                    <h2 class = "text-center"><b>मिरा-भाईंदर महानगरपालिका</b></h2>
                    <h6 class = "text-center">मुख्य कार्यालय भाईंदर</h6>
                    <h6 class = "text-center"><b>स्व. इंदिरा गांधी भवन, छत्रपती शिवाजी महाराज मार्ग, भाईंदर (प.), ता.जि.ठाणे - 401101 
                    दुरध्वनी : 28192828 / 28193028 / 28181183 / 28181353 / <br> 25145985 फॅक्स : 28197636 </b></h6>
                    <p>Fax No. - 28199093 E-mail - rchmbmc@rediffmail.com, rchmbmc@gmail.com</p>
                </div>
            </div>
        </div>
        <hr style = "border: 1px solid #000;">
        <div class="row">
            <div class="col-6 text-center">
                <p>जा . क्र . मनपा / वैधकीय  /     / </p>
            </div>
            <div class="col-6 text-center">
                <p>दिनांक : <?= date('d/m/Y',strtotime($finalApprovelDate->created_at)) ?></p>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-9">
                <p>१) मा. आयुक्ता याची दि .   /   /  रोजीची  मंजुरी. </p>
                <p>२) डा. <?= $application->applicant_name ?> याचा दि. <?= date('d/m/Y',strtotime($application->created_at)) ?> रोजची अर्ज. </p>
                <p>३) वैद्य  कार्य  अधिकारी <?= $finalApprovelDate->senior_doctor_name ?> याचा दि. <?= date('d/m/Y',strtotime($inspection->created_at)) ?> रोजीचा तपासणी अहवाल.</p>
            </div>
            <div class="col-3 text-center">
                <?php if (!empty($appimages->user_image)) : ?>
                    <img style="width: 100%;height: auto;" src="<?= base_url('uploads/clinic/'.$appimages->user_image->image_enc_name) ?>">
                <?php else : ?>
                    <img style="width: 100%;height: auto;" src="<?= true ?>">
                <?php endif ; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <p>प्रथम परवाना</p>
                <p style="font-size: small;">(महाराष्ट्र महानगरपालिका अधिनियम १९४९ चे ३८६ अन्वये)</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-justify">
                <p>मीराभाईंदर महानगरपालिका क्षेत्रात <?= $application->applicant_address ?> या नावाने <?= $application->clinic_name ?> या ठिकाणी डा. <?= $application->applicant_name ?> यांनी व्यवसाय चालविण्यास खालील अटी-शर्तीचा अधीन राहून दि.  <?= date("d-m-Y", strtotime('+ 3 year', strtotime($finalApprovelDate->created_at))) ?>  या कालावधीपर्यंत परवाना देण्यात येते आहे.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <h4>परवाना क्: MBMC/HEALTH/ /01</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h6>अटी शर्ती :-</h6>
                <p>१) सदर परवान्यांचा नूतनीकरण्याचा अर्ज फेब्रुवारी महिन्यात करणे बंधनकारक राहील.</p>
                <p>२) सदरचा परवाना दिल्यामूडे जागेच्या मालकी हक्क सिद्ध होते नाही. तसेज सदरचा परवाना दिल्यामूडे कोणतीही अनधिकृत इमारत / बांधकाम अधिकृत ठरत नाही.</p>
                <p>३) सदर व्यवसायामुळे निर्माण होणारा सर्वाधारण कचरा तसेच जैव-वैध कीय कचरा यांनी शास्त्रीय पद्धतीने व्हिलेवाट लावण्याची जवाबदारी परवानाधारकाची राहील . तसेच  जैव-वैध कीय कचऱ्याची शास्त्रीय पद्धतीने व्हिलेवाट लावत असल्याबाबतचे प्रमाणपत्र सादर करणे बंधनकारक राहील. </p>
                <p>४) आवश्यकता असेल तेथे महाराष्ट्र प्रदूषण नियंत्रण महामंडळाचे प्रमाणपत्र सादर करणे बांधणीकारक राहील.</p>
                <p>५) परवाना देण्यात आलेल्या व्यवसायात , व्यवसायाचा नावात अथवा पत्ता/ठिकाणा यात मनपाच्या परवानगी शिवाय बदल करता येणार नाही.</p>
                <p>६) महा नगरपालिकेच्या विविध विभागांच्या तसेच शासनाच्या आवश्यक परवानग्या घेण्याची जबाबदारी परवानाधारकाची राहील.</p>
                <p>७) परवान्यासाठी दाखल केलेली कागदपत्रे खोटी  अथवा बनावट  आढळल्यास त्यासंबंधीची सर्व जबाबदारी पेरवानाधारकांची राहील. </p>
                <p>८) परवानाधारकांनी व्यवसायाचा ठिकाणी बालमजूर ठेऊ नये. </p>
                <p>९) परवान्याबाबद वाद / तक्रार  निर्माण झाल्यास मा. आयुक्त  यांचा निर्णय अंतिम राहील. तसेच परवाना देताना विचारात घेतलेल्या कोणत्याही अति-शर्तीचा भंग झाल्यास परवाना निलंबित अथवा रद्द करण्यात येईल.</p>
                <p>१०) हा परवाना कामाच्या \ व्यवसायाच्या ठिकाणी दर्शनी भागात   ठळ कपणे दिसेल अशाप्रकारे लावण्यात यावा. </p>
                <p>११) परवाना देण्यात आलेल्या ठिकाणी काही अपरिहार्य कारणामुळे अल्प काळासाठी आपणांस इतर व्यक्तीस नेमवायचे असल्यास त्या वव्यक्तीच्या पात्रता प्रमाणपत्रासह कमीतकमी दोन दिवस आधी वैद्यकीय आरोग्य अधिकारी यांना लेखी कालवावे लागेल.</p>
                <p>१२) सांसर्गिक व साथीच्या रुग्णाची माहिती उदा. हिवताप , डेंगू , लेप्टोस्पायरोसिस , चिकनगुनिया , स्वाईनफ्लू , क्षयरोग , कुष्ठरोग इ. तात्काळ    वैधकीय विभागास कळविणे बंधनकारक राहील.</p>
                <p>१३) लसीकरण दिलेल्या सर्व लाभार्त्याची (मुले व गर्भवती स्त्रीया) माहिती दरमहा संबंधित वैधकीय अधिकारी याना विहित नमुन्यात देणे बंधनकारक राहील.</p>
                <p>१४) उक्त नमूद अति-शर्ती मध्ये केव्हाहि व कोणत्या हि व कोणत्याही प्रकारचा बद्दल करण्याचा अधिकार महानगरपालिकेने राखून ठेवलेल्या आहे.</p>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-12 text-right">
                <h4>वैधकीय आरोग्य अधिकारी</h4>
                <p>मिरा-भाईंदर महानगरपालिका</p>
            </div>
        </div>        
    </div>
</body>
</html>