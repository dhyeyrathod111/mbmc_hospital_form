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
                    <h6 class = "text-center"><b>स्व. इंदिरा गांधी भवन, छत्रपती शिवाजी महाराज मार्ग, भाईंदर (प.), ता.जि.ठाणे - 401101 
                    दुरध्वनी : 28192828 / 28193028 / 28181183 / 28181353 / <br> 25145985 फॅक्स : 28197636 </b></h6>
                    <!-- <h5 class = "text-center"><b>// बांधकाम विभाग //</b></h5> -->
                </div>
            </div>
        </div>
        <hr style = "border: 1px solid #000;">
        <div class="col-12 text-center">
            <h3 style="font-size: larger;">FORM 'C' - RENEWAL</h3>
        </div>
        <br />
        <div class="col-12"> 
            <p>Certificate of Registrationunder section 5 of Maharashtra (Bombay) Nursing Home Registration Act 1949</p>
        </div>
        <div class="col-12 text-center"> 
            <h3 style="font-size: larger;">SEE RULE – 5</h3>
        </div>
        <br />
        <div class="col-12"> 
            <p>This is to certify that Dr <span style="text-decoration: underline;"><?= $application->applicant_name ?></span>. has been registered under the Bombay Nursing Home Registration Act 1949 in respect of <span style="text-decoration: underline;"><?= $application->hospital_name ?></span>. Situated at <span style="text-decoration: underline;"><?= $application->hospital_address ?></span> And has been authorized to carry out the said nursing home.</p>
        </div>
        <br />
        <div class="col-12">
            <div class="row">
              <div class="col-3">
                <p>Registration No</p>
              </div>
              <div class="col-9">
                <p>MH/THN/MBMC/YYYY- Certificate Number</p>
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <p>Date of Renual</p>
              </div>
              <div class="col-9">
                <p><?= date("d-m-Y", strtotime($finalApprovelDate->created_at)) ?></p>
              </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p>Place</p>
                </div>
                <div class="col-9">
                    <p><?= $application->applicant_address ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <p>No. of Beds</p>
                </div>
                <div class="col-9">
                    <p><?= $inspection->no_of_beds ?></p>
                </div>
            </div>
        </div>
        <br />
        <div class="col-12"> 
            <p>This certificate of Renewal shall be valid upto. <?= date("d-m-Y", strtotime('+ 3 year', strtotime($finalApprovelDate->created_at))) ?></p>
            <p>However this registration shall stand cancelled, if any of the conditions which are considered while granting the registration</p>
            <p>are found to be breachbed</p>
        </div>
        <br />
        <div class="row">
          <div class="col-6"> 
            <h5>Date : </h5>
          </div>
          <div class="col-6 text-right"> 
              <h5>Medical Officer of Health</h5>
              <h5>Mira Bhayander Municipal Corporation</h5>
          </div>
        </div>
        
    </div>
</body>
</html>