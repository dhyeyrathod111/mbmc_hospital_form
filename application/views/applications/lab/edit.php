  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Application</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Application</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid  - ->
    </section> -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <form role="form" class="lab-form" id="lab-form" method="post" enctype="multipart/form-data">

                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="application_no"><span>Application No</span><span class="red">*</span></label>


                            <input type="hidden" value="<?=($users['app_id'] != null) ? $users['app_id'] :'1' ?>" name="app_id" id="app_id">
                             <input type="hidden" value="<?=($users['id'] != null) ? $users['id'] :'1' ?>" name="id" id="id">
                            <?php
                            // echo'<pre>';print_r($users['app_id']);exit;
                              if($users['app_id'] != null) {
                                  $app_val = 'MBMC-00000'.$users['app_id'];
                                  $app_no = $app_val;
                              } else {
                                $app_no = 'MBMC-000001';
                              }
                           ?>
                            <input type="text" class="form-control" value="<?=$app_no; ?>" name="application_no" id="application_no" placeholder="Enter Application no" readonly>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Applicant Name<span class="red">*</span></label>
                            <input type="text" class="form-control" value="<?=($users['applicant_name'] != null) ? $users['applicant_name'] :'' ?>" name="applicant_name" id="applicant_name" placeholder="Enter Applicant name">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="email_id">Applicant Email Id<span class="red">*</span></label>
                            <input type="text" class="form-control" value="<?=($users['applicant_email_id'] != null) ? $users['applicant_email_id'] :'' ?>" name="applicant_email_id" id="applicant_email_id" placeholder="Enter email Id">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="mobile_no">Applicant Mobile no<span class="red">*</span></label>
                            <input type="number" class="form-control" value="<?=($users['applicant_mobile_no'] != null) ? $users['applicant_mobile_no'] :'' ?>" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter mobile no">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label  for="alert_mobile_no">Alternate Mobile no<span class="grey"> (optional)</span></label>
                            <input type="number" class="form-control" value="<?=($users['applicant_alternate_no'] != null) ? $users['applicant_alternate_no'] :'' ?>" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile no">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="alert_mobile_no">Applicant Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address">
                              <?=($users['applicant_address'] != null) ? $users['applicant_address'] :'' ?>
                            </textarea> 
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="alert_mobile_no">Nationality of Applicant<span class="red">*</span></label>
                            <input type="text" class="form-control" value="<?=($users['applicant_nationality'] != null) ? $users['applicant_nationality'] :'' ?>" name="applicant_nationality" id="applicant_nationality" placeholder="Enter applicant nationality">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="technical_qualification">Technical qualification<span class="red">*</span></label>
                            <input type="text" class="form-control" value="<?=($users['technical_qualification'] != null) ? $users['technical_qualification'] :'' ?>" name="technical_qualification" id="technical_qualification" placeholder="Enter technical qualification">
                          </div>
                        </div>
                        <div class="col-4">
                          
                        </div>

                        <!-- <div class="col-4">
                          <div class="icheck-primary">
                            <input type="checkbox" value="<?=($users['un_reg_medical_practice'] != null) ? $users['un_reg_medical_practice'] :'0' ?>" name="un_reg_medical_practice" id="un_reg_medical_practice" <?= ($users['un_reg_medical_practice'] == '1' ? 'checked' : '') ?>>
                            <label for="un_reg_medical_practice">
                              Un-register Medical Practice
                            </label>
                          </div>
                        </div> -->
                        
                      </div>
                    </div>

                    <!-- lab info -->
                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">lab Information</label>
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="lab_name">Name of lab<span class="red">*</span></label>
                            <input type="text" class="form-control" name="lab_name" value="<?=($users['lab_name'] != null) ? $users['lab_name'] :'' ?>" id="lab_name" placeholder="Enter lab name">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="contact_no">Contact No<span class="red">*</span></label>
                            <input type="number" class="form-control" value="<?=($users['contact_no'] != null) ? $users['contact_no'] :'' ?>" name="contact_no" id="contact_no" placeholder="Enter landline no">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="contact_person">Contact Person<span class="red">*</span></label>
                            <input type="text" class="form-control" name="contact_person" value="<?=($users['contact_person'] != null) ? $users['contact_person'] :'' ?>" id="contact_person" placeholder="Enter Name of contact person">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="lab_address">lab Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control" value="" name="lab_address" id="applicant_address" placeholder="Enter lab address">
                              <?=($users['lab_address'] != null) ? $users['lab_address'] :'' ?>
                            </textarea> 
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Premises Information</label>
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="floor_space">Floor Space<span class="red">*</span></label>
                            
                            <input type="text" class="form-control" value="<?=($users['floor_space'] != null) ? $users['floor_space'] :'' ?>" name="floor_space" id="floor_space" placeholder="Enter floor space">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="check_up_details">Medical check up details<span class="grey">&nbsp;(optional)</span></label>
                            
                            <input type="text" class="form-control" value="<?=($users['check_up_details'] != null) ? $users['check_up_details'] :'' ?>" name="check_up_details" id="check_up_details" placeholder="Enter check up details">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="floor_space_kitchen">Floor Space for kitchen<span class="grey">&nbsp;(optional)</span></label>
                            <input type="text" class="form-control" value="<?=($users['floor_space_kitchen'] != null) ? $users['floor_space_kitchen'] :'' ?>" name="floor_space_kitchen" id="floor_space_kitchen" placeholder="Enter floor space">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="sanitary_details">Sanitary conveniences Details<span class="grey">&nbsp;(optional)</span></label>
                            
                            <input type="text" class="form-control" value="<?=($users['sanitary_details'] != null) ? $users['sanitary_details'] :'' ?>" name="sanitary_details" id="sanitary_details" placeholder="Enter sanitary details">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="storage_details">Storage and service food Details<span class="grey">&nbsp;(optional)</span></label>
                            
                            <input type="text" class="form-control" value="<?=($users['storage_details'] != null) ? $users['storage_details'] :'' ?>" name="storage_details" id="storage_details" placeholder="Enter storage details">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="others">Others<span class="grey">&nbsp;(optional)</span></label>
                            
                            <input type="text" class="form-control" value="<?=($users['others'] != null) ? $users['others'] :'' ?>" name="others" id="others" placeholder="Enter storage others">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="maternity_beds">No. of maternity beds<span class="red">*</span></label>
                            
                            <input type="number" class="form-control" value="<?=($users['maternity_beds'] != null) ? $users['maternity_beds'] :'' ?>" name="maternity_beds" id="maternity_beds" placeholder="Enter no. of maternity beds">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="patient_beds">No. of patient beds<span class="red">*</span></label>
                            
                            <input type="number" class="form-control" value="<?=($users['patient_beds'] != null) ? $users['patient_beds'] :'' ?>" name="patient_beds" id="patient_beds" placeholder="Enter no. of patient beds">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Staff Information</label>
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class = "col-10"></div>
                        <div class = "col-2">
                          <button type="button" class = "form-control btn btn-md btn-info addRow">Add</button>
                        </div>
                      </div>
                      <br>  
                      <div class="row">
                        <div class="col-12">
                          <table class = "table">
                              <thead>
                                <tr class = "text-center">
                                  <th>Staff Name</th>
                                  <th>Designation</th>
                                  <th>Age</th>
                                  <th>Qualification</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody class = "tableBody">
                                 <?php foreach($staff_details as $key => $staff){ ?>
                                    <tr id="tr_<?=$staff['id']?>">
                                    <td class = 'text-center name'>
                                      <input type="hidden" class="form-control" value="<?=($staff['id'] != null) ? $staff['id'] :'' ?>" name="staff_id[]" id="staff_id" placeholder="Enter Staff Name">
                                      <input type="text" class="form-control" value="<?=($staff['staff_name'] != null) ? $staff['staff_name'] :'' ?>" name="staff_name[]" id="staff_name" placeholder="Enter Staff Name">
                                    </td>
                                    <td class = 'text-center activity'>
                                      <select class='selectpicker form-control' id='designation' name='designation[]' data-live-search='true' required="">
                                        <option value="0">Select Designation</option>
                                        option
                                        <?php
                                          foreach ($designation as $key => $design) {
                                              if($staff['design_id'] == $design['design_id']) {
                                                $select = "selected='selected'";
                                              } else {
                                                $select = "";
                                              }

                                           ?>

                                            <option value = '<?= $design['design_id'] ?>' <?= $select; ?>><?= $design['design_title']?></option>;
                                        <?php  } ?>
                                      </select>
                                    </td>
                                    <td class = 'text-center noOfTrees'>
                                      <input type = 'number' class="form-control" value="<?=($staff['age'] != null) ? $staff['age'] :'' ?>" name = 'age[]' id = 'age[]' placeholder='Enter Age' required="">
                                    </td>
                                    <td class = 'text-center permission'>
                                      <select class='selectpicker form-control' id='qualification' name='qualification[]' data-live-search='true' required="">
                                        <option value="0">Select Qualification</option>
                                        option
                                        <?php
                                          foreach ($qualification as $key => $qual) {
                                            if($staff['qual_id'] == $qual['qual_id']) {
                                              $select = "selected='selected'";
                                            } else {
                                              $select = "";
                                            }

                                           ?>
                                            <option value = '<?= $qual['qual_id'] ?>' <?= $select; ?>><?= $qual['qual_title']?></option>;
                                        <?php  } ?>
                                      </select>
                                    </td>
                                    <td class = 'text-center action'>
                                      <span style = 'cursor:pointer' class ='delete' data-staff-id ='<?=$staff['id']?>'>
                                        <i class='fas fa-trash'></i>
                                      </span>
                                    </td>
                                  </tr>

                                <?php }?>
                                  
                              </tbody>
                            </table>  
                        </div>
                      </div>
                      </div>
                      <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Alien Information</label>
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="alien_name">Alien Name<span class="grey">&nbsp;(optional)</span></label>
                            
                            <input type="text" class="form-control" value="<?=($users['alien_name'] != null) ? $users['alien_name'] :'' ?>"  name="alien_name" id="alien_name" placeholder="Enter alien name">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="alien_details">Alien Details<span class="grey">&nbsp;(optional)</span></label>
                            
                            <input type="text" class="form-control" value="<?=($users['alien_details'] != null) ? $users['alien_details'] :'' ?>"  name="alien_details" id="alien_details" placeholder="Enter alien details">
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- </div> -->

                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Attachments</label>
                      </h3>
                    </div>

                    <div class="card-body">
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="owership_agreement">Ownership Agreement<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="ownership_agreement" id="ownership_agreement" 
                                  value="<?= $files[0]['path'] ?>" class="custom-file-input">
                                 <input type="hidden" name="ownership_agreement_name" 
                                  id="ownership_agreement_name_id" value="<?= $files[0]['path'] ?>" class="custom-file-input">
                                <label class="custom-file-label" for="ownership_agreement">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <h3 class="card-title link-margin">
                            <label for="" id="ownership_agreement_name" class="text-info">
                              <a href="<?= $files[0]['path'] ?>"><?= $files[0]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <!--  -->
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="tax_receipt">Property Tax Receipt<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="tax_receipt" id="tax_receipt" class="custom-file-input" value="<?= $files[1]['path'] ?>">
                                <input type="hidden" name="tax_receipt_name" 
                                  id="tax_receipt_name_id" value="<?= $files[1]['path'] ?>" class="custom-file-input">

                                <label class="custom-file-label" for="tax_receipt">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="tax_receipt_name"  class="text-info"> 
                              <a href="<?= $files[1]['path'] ?>"><?= $files[1]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="doc_certificate">Doctor Degree Certificate<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="doc_certificate" id="doc_certificate" class="custom-file-input" value="<?= $files[2]['path'] ?>">
                                <input type="hidden" name="doc_certificate_name" 
                                  id="doc_certificate_name_id" value="<?= $files[2]['path'] ?>" class="custom-file-input">

                                <label class="custom-file-label" for="doc_certificate">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="doc_certificate_name"  class="text-info"> 
                              <a href="<?= $files[2]['path'] ?>"><?= $files[2]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="reg_certificate">Doctor Registration Certificate<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="reg_certificate" id="reg_certificate" class="custom-file-input" value="<?= $files[3]['path'] ?>">
                                <input type="hidden" name="reg_certificate_name" 
                                  id="reg_certificate_name_id" value="<?= $files[3]['path'] ?>" class="custom-file-input">
                                <label class="custom-file-label" for="reg_certificate">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="reg_certificate_name"  class="text-info"> 
                              <a href="<?= $files[3]['path'] ?>"><?= $files[3]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="staff_certificate">Paramedical Staff Certificate<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="staff_certificate" id="staff_certificate" class="custom-file-input" value="<?= $files[4]['path'] ?>">
                                <input type="hidden" name="staff_certificate_name" 
                                  id="staff_certificate_name_id" value="<?= $files[4]['path'] ?>" class="custom-file-input">
                                <label class="custom-file-label" for="staff_certificate">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="staff_certificate_name"  class="text-info">
                              <a href="<?= $files[4]['path'] ?>"><?= $files[4]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="nursing_staff_deg_certificate">Nursing Staff Degree Certificate<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                 <input type="file" name="nursing_staff_deg_certificate" id="nursing_staff_deg_certificate" class="custom-file-input" value="<?= $files[5]['path'] ?>">
                                <input type="hidden" name="nursing_staff_deg_certificate_name" 
                                  id="nursing_staff_deg_certificate_name_id" value="<?= $files[5]['path'] ?>" class="custom-file-input">
                                <label class="custom-file-label" for="nursing_staff_deg_certificate">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="nursing_staff_deg_certificate_name"  class="text-info"> 
                              <a href="<?= $files[5]['path'] ?>"><?= $files[5]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="nursing_staff_reg_certificate">Nursing Staff Degree Certificate<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="nursing_staff_reg_certificate" id="nursing_staff_reg_certificate" class="custom-file-input" value="<?= $files[6]['path'] ?>">
                                <input type="hidden" name="nursing_staff_reg_certificate_name" 
                                  id="nursing_staff_reg_certificate_name_id" value="<?= $files[6]['path'] ?>" class="custom-file-input">
                                <label class="custom-file-label" for="nursing_staff_reg_certificate">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="nursing_staff_reg_certificate_name"  class="text-info"> 
                              <a href="<?= $files[6]['path'] ?>"><?= $files[6]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="bio_des_certificate">Bio Disposal waste certificate<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="bio_des_certificate" id="bio_des_certificate" class="custom-file-input" value="<?= $files[7]['path'] ?>">
                                <input type="hidden" name="bio_des_certificate_name" 
                                  id="bio_des_certificate_name_id" value="<?= $files[7]['path'] ?>" class="custom-file-input">
                                <label class="custom-file-label" for="bio_des_certificate">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="bio_des_certificate_name"  class="text-info"> 
                              <a href="<?= $files[7]['path'] ?>"><?= $files[7]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="society_noc">Society NOC<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="society_noc" id="society_noc" class="custom-file-input" value="<?= $files[8]['path'] ?>">
                                <input type="hidden" name="society_noc_name" 
                                  id="society_noc_name_id" value="<?= $files[8]['path'] ?>" class="custom-file-input">
                                <label class="custom-file-label" for="society_noc">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="society_noc_name" id="society_noc_name"  class="text-info">
                              <a href="<?= $files[8]['path'] ?>"><?= $files[8]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="fire_noc">Fire Department NOC<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="fire_noc" id="fire_noc" class="custom-file-input" value="<?= $files[9]['path'] ?>">
                                <input type="hidden" name="fire_noc_name" 
                                  id="fire_noc_name_id" value="<?= $files[9]['path'] ?>" class="custom-file-input">
                                <label class="custom-file-label" for="fire_noc">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="fire_noc_name" id="fire_noc_name"  class="text-info"> 
                               <a href="<?= $files[9]['path'] ?>"><?= $files[9]['name'] ?></a>
                            </label>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-12">
                            <h3 class="card-title">
                              <label for="email_id" class="text-danger">Note:</label>
                              <ul style="list-style: none;">
                                <li>
                                  <i class=" text-danger fas fa-exclamation-circle"></i>
                                  <span class="text-danger">Only JPG, JPEG, PNG, PDF, DOCX are allowed.</span>
                                </li>
                                <li>
                                  <i class="text-danger fas fa-exclamation-circle"></i>
                                  <span class="text-danger" >File size should Not be more than 5 MB.</span>
                                  
                                </li>
                              </ul>
                            </h3>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                       <div class="row justify-content-center">
                         
                            <a href="<?= base_url()?>lab" class="btn btn-lg btn-info white">Cancel</a>&nbsp&nbsp&nbsp
                            <button type="submit" class="btn btn-lg btn-primary right">Submit</button>
                        
                      </div>
                      
                    </div>
                  </form>
                </div>  
              </div>
        </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
   <?php $this->load->view('includes/footer');?>

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- DataTables -->
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/lab.js" id="createlab" is_user="<?= $this->authorised_user['is_user']; ?>"></script>
<script type="text/javascript">
 
</script>

<!-- page script -->
</body>
</html>
