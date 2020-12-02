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
        <div class="col-12"> 
            <p>To,<br />Medical Officer Of Helth<br />Mira bhayandar Muncipal corporation</p>
        </div>
        <div class="col-12 text-center"> 
            <b>Subject : Registration</b>
        </div>
        <div class="col-12 text-center"> 
            <p>After having inspected of <?= $application->hospital_name ?> located at <?= $application->hospital_address ?> the finding on inspection are.</p>
        </div>
        <br />
        <div class="col-12"> 
            <ul>
                <?php if ($inspection->doc_degree_certificate != 0) : ?>
                    <li>Doctor degree Certificate.</li>
                <?php endif ; ?>

                <?php if ($inspection->doc_reg_mmc != 0) : ?>
                    <li>Doctor Ragistration form MMC.</li>
                <?php endif ; ?>
                
                <?php if ($inspection->bio_medical_valid_date != '0000-00-00') : ?>
                    <li>Bio-Medical wast disposal Certificate valid upto <?= $inspection->bio_medical_valid_date ?></li>
                <?php endif ; ?>

                <?php if ($inspection->mpcb_certificate_valid_date != '0000-00-00') : ?>
                    <li>MPCB cirtificate valid upto <?= $inspection->mpcb_certificate_valid_date ?></li>
                <?php endif ; ?>

                <?php if ($inspection->agreement_copy != 0) : ?>
                    <li>Ageement Copy / Leave & license copy</li>
                <?php endif ; ?>

                <?php if ($inspection->tax_recipes != 0) : ?>
                    <li>Tax Receipt.</li>
                <?php endif ; ?>

                <?php if ($inspection->nursing_certificate != 0) : ?>
                    <li>Nursing certificate from Maharashtra Nursing Council.</li>
                <?php endif ; ?>

                <?php if ($inspection->noc_from_society != 0) : ?>
                    <li>NOC from society for hospital/nursing home.</li>
                <?php endif ; ?>


                <?php if ($inspection->noc_from_town_planning_mbmc != 0) : ?>
                    <li>NOC from town planing department, MBMC.</li>
                <?php endif ; ?>

                <?php if ($inspection->no_of_beds != 0 || $inspection->no_of_toilets) : ?>
                    <li>No of beds <?= $inspection->no_of_beds ?> Toilets <?= $inspection->no_of_toilets ?></li>
                <?php endif ; ?>

                <?php if ($inspection->noc_from_fire_dept != 0) : ?>
                    <li>NOC from fire department every 6 month.</li>
                <?php endif ; ?>

                <?php if ($inspection->general_observation != 0) : ?>
                    <li>Genral observations.</li>
                <?php endif ; ?>

                <?php if ($inspection->labour_room_availability != 0) : ?>
                    <li>Labour Room if available is well equipped  with all instruments. O.T. Well equipment with saprate wash room & emergency drug.</li>
                <?php endif ; ?>
            </ul>
        </div>
        <br />
        <div class="col-12 text-right"> 
            <p><b>Medical officer</b><br /> _____Helth post<br />Mira bhayandar Muncipal corporation</p>
        </div>
    </div>
</body>
</html>