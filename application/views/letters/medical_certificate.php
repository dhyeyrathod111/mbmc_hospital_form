<!DOCTYPE html>
<html lang = "mr">
<head>
    <title>Test</title>
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
                    <h6 class = "text-center"><b>स्व. इंदिरा गांधी भवन, छत्रपती शिवाजी महाराज मार्ग, भाईंदर (प.), ता.जि.ठाणे - 401101 
                    दुरध्वनी : 28192828 / 28193028 / 28181183 / 28181353 / <br> 25145985 फॅक्स : 28197636 </b></h6>
                    <!-- <h5 class = "text-center"><b>// बांधकाम विभाग //</b></h5> -->
                </div>
            </div>
        </div>
        <hr style = "border: 1px solid #000;">
        <div class="col-12 text-center"> 
            <h3>FORM C ORIGINAL</h3>
        </div>
        <div class="col-12"> 
            <p>Certificate of Registrationunder section 5 of Maharashtra (Bombay) Nursing Home Registration Act 1949</p>
        </div>
        <div class="col-12 text-center"> 
            <h3>SEE RULE – 5</h3>
        </div>
        <div class="col-12"> 
            <p>This is to certify that Dr <?= $application->applicant_name ?>. has been registered under the Bombay Nursing Home Registration Act 1949 in respect of. Situated at <?= $application->situation_registration ?>  And has been authorized to carry out the said nursing home.</p>
        </div>
        <br />
        <div class="col-12">
            <h5>Registration No MBMC-00000<?= $application->app_id ?></h5>
            <h5>Date of Registration <?= $application->created_at ?></h5>
            <h5>Place <?= $application->applicant_address ?></h5>
            <h5>No. of Beds <?= $inspection->no_of_beds ?></h5>
        </div>
        <br />
        <div class="col-12"> 
            <p>This certificate of Registration shall be valid upto.</p>
            <p>However this registration shall stand cancelled, if any of the conditions which are considered while granting the registration</p>
            <p>are found to be breachbed</p>
        </div>
        <br />
        <div class="col-12 text-right"> 
            <h5>Medical Officer of Health</h5>
            <h5>Mira Bhayander Municipal Corporation</h5>
        </div>
        
    </div>
</body>
</html>