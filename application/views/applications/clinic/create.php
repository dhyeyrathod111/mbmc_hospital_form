<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">

                    <h3 class="text-center mt-1">
                        <label for="email_id" class="text-info">Application Form for License to run Dispensary/ Clinic /Day Care Center /Path. Lab./ X-ray Center/ Physiotherapy Center, etc. Under section 386 of Maharashtra Municipal Corporation Act, 1949.</label>
                    </h3>
                    <hr />

                    <form role="form" class="clinic-form" id="clinic-form" method="post" enctype="multipart/form-data">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="card-title">
                                        <label for="email_id" class="text-info">Applicant Details</label>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="application_no"><span>Application No</span><span class="red">*</span></label>
                                        <input type="hidden" value="<?=($app_id != null) ? $app_id :'1' ?>" name="app_id" id="app_id">
                                        <input type="hidden" value="" name="id" id="id">
                                        <?php
                                            if($app_id != null) {
                                                $app_val = 'MBMC-00000'.$app_id;
                                                $app_no = ++$app_val;
                                            } else {
                                              $app_no = 'MBMC-000001';
                                            }
                                            ?>
                                        <input type="text" class="form-control" value="<?=$app_no; ?>" name="application_no" id="application_no" placeholder="Enter Application no" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="applicant_name">Name of the Dispensary/ Clinic, etc<span class="red">*</span></label>
                                        <!-- <i class="fas fa-info-circle" title="Full name of the Dispensary/ Clinic, etc "></i> -->
                                        <input type="text" class="form-control" name="clinic_name" id="clinic_name" placeholder="Enter Name of the Dispensary/ Clinic, etc">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="applicant_name">Telephone No. of Clinic<span class="red">*</span></label>
                                        <!-- <i class="fas fa-info-circle" title=""></i> -->
                                        <input type="text" class="form-control" name="clinic_telephone_no" id="clinic_telephone_no" placeholder="Enter Telephone No.">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Address of the Dispensary/ Clinic, etc<span class="red">*</span></label>
                                        <!-- <i class="fas fa-info-circle" title="Address of the Dispensary/ Clinic, etc"></i> -->
                                        <textarea type="text" rows="5" class="form-control" name="clinic_address" id="clinic_address" placeholder="Enter Address"></textarea> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header mt-5">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="card-title">
                                        <label for="email_id" class="text-info">Applicant Details:</label>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Full Name<span class="red">*</span></label>
                                        <!-- <i class="fas fa-info-circle" title="Full name of applicant"></i> -->
                                        <input type="text" class="form-control" name="applicant_name" id="applicant_name" placeholder="Enter full Name of Applicant">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Qualification<span class="red">*</span></label>
                                        <!-- <i class="fas fa-info-circle" title="Qualification of applicant"></i> -->
                                        <input type="text" class="form-control" name="applicant_qualification" id="applicant_qualification" placeholder="Enter Qualification of Applicant">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Mobile No.<span class="red">*</span></label>
                                        <!-- <i class="fas fa-info-circle" title="Mobile number of applicant"></i> -->
                                        <input type="text" class="form-control" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter Mobile number of Applicant">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Alternate Mobile No.<span class="red">*</span></label>
                                        <!-- <i class="fas fa-info-circle" title="Enter Alternate mobile number"></i> -->
                                        <input type="text" class="form-control" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile number">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Email id<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Note * This email-id will be used for further communications "></i>
                                        <input type="text" class="form-control" name="applicant_email_id" id="applicant_email_id" placeholder="Enter Email id of Applicant">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Residential Address<span class="red">*</span></label>
                                        <!-- <i class="fas fa-info-circle" title="Full address of applicant"></i> -->
                                        <textarea type="text" rows="5" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address of Applicant"></textarea> 
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label>Details of Medical /Paramedical Staff Working</label>
                                    <!-- <i class="fas fa-info-circle" title="Details of Medical /Paramedical Staff Working"></i> -->
                                    <table class="table staff_grid">
                                        <thead>
                                            <tr class = "text-center">
                                                <th>Sr No. </th>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Qualification</th>
                                                <th><button type="button" id="addRow" class="btn btn-sm btn-primary">Add</button></th>
                                            </tr>
                                        </thead>
                                        <tbody class="tableBody">
                                            <tr id="oneStaffField">
                                                <td>
                                                    <input class="form-control" type="number" id="sr_number_clinic_Staff" name="sr_number_clinic_Staff[]" multiple>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" id="name_clinic_Staff" name="name_clinic_Staff[]" multiple>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="designation_clinic_Staff[]" id="designation_clinic_Staff">
                                                        <option value="">---Select designation---</option>
                                                        <?php foreach ($designation as $desig) : ?>
                                                            <option value="<?= $desig['design_id'] ?>"><?= $desig['design_title'] ?></option>
                                                        <?php endforeach ; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="qualification_clinic_Staff[]" id="qualification_clinic_Staff">
                                                        <option value="">---Select qualification---</option>
                                                        <?php foreach ($qualification as $quali) : ?>
                                                            <option value="<?= $quali['qual_id'] ?>"><?= $quali['qual_title'] ?></option>
                                                        <?php endforeach ; ?>
                                                    </select>
                                                </td>
                                                <td style="vertical-align:inherit;cursor: pointer;"><i class="fas fa-trash fa-lg delete_row fa-2x"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Whether immunization is carried out in the clinic </label>
                                            <!-- <i class="fas fa-info-circle" title="Whether immunization is carried out in the clinic"></i> -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="immunization_is_carried" value="Yes">
                                                <label class="form-check-label">
                                                    Yes
                                                </label>
                                            </div>   
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="immunization_is_carried" value="No">
                                                <label class="form-check-label">
                                                    No
                                              </label>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="col-12 cold_chain_facilities_container" style="display: none;">
                                        <div class="form-group">
                                            <label>Give details of cold chain facilities available</label>
                                            <textarea class="form-control" rows="5" name="cold_chain_facilities"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>Certificate regarding Bio-Medical Waste Management </label>
                                            <!-- <i class="fas fa-info-circle" title="Certificate regarding Bio-Medical Waste Management"></i> -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="bio_medical" value="Yes">
                                                <label class="form-check-label">
                                                    Yes
                                                </label>
                                            </div>   
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="bio_medical" value="No">
                                                <label class="form-check-label">
                                                    No
                                              </label>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="col-6 bio_medical_valid_date_container" style="display: none;">
                                        <div class="form-group">
                                            <label>Valid up to</label>
                                            <input class="form-control" id="bio_medical_valid_date" name="bio_medical_valid_date" readonly type="text" >
                                        </div>
                                    </div>

                                    <?php if ($application_type == 2) : ?>

                                        <div class="card-header mt-5">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h3 class="card-title">
                                                        <label for="email_id" class="text-info">For Renewal of Registration:</label>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="alien_name">19.Certificate Number</label>
                                                        <i class="fas fa-info-circle" title="Number of certificate of registration."></i>
                                                        <input placeholder="MH/THN/MBMC/YYYY-Certificate Number " type="text" class="form-control" name="no_of_expiry_certificate">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <label for="alien_name">Date of expiry</label>
                                                        <i class="fas fa-info-circle" title="Date of expiry of the certificate."></i>
                                                        <input type="text" readonly class="form-control" id="date_of_expiry_certificate" name="date_of_expiry_certificate">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif ; ?>

                                </div>
                            </div>
                        </div>
                        <div class="card-header mt-5">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="card-title">
                                        <label class="text-info">Attachments</label>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="owership_agreement">Ownership Agreement<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="ownership_agreement" id="ownership_agreement" class="custom-file-input">
                                                <label class="custom-file-label" for="ownership_agreement">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="ownership_agreement_name" class="text-info"> Please select a document</label>
                                        <input type="hidden" name="ownership_agreement_name" id="ownership_agreement_name_id">
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tax_receipt">Property Tax Receipt<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="tax_receipt" id="tax_receipt" class="custom-file-input">
                                                <label class="custom-file-label" for="tax_receipt">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="tax_receipt_name" class="text-info"> Please select a document </label>
                                        <input type="hidden" name="tax_receipt_name" id="tax_receipt_name_id">
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="doc_certificate">Doctor Degree Certificate<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="doc_certificate" id="doc_certificate" class="custom-file-input">
                                                <label class="custom-file-label" for="doc_certificate">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="doc_certificate_name" class="text-info"> Please select a document </label>
                                        <input type="hidden" name="doc_certificate_name" id="doc_certificate_name_id">
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="bio_medical_certificate">Bio Medical certificate<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="bio_medical_certificate" id="bio_medical_certificate" class="custom-file-input">
                                                <label class="custom-file-label" for="bio_medical_certificate">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="bio_medical_certificate_name" class="text-info"> Please select a document </label>
                                        <input type="hidden" name="bio_medical_certificate_name" id="bio_medical_certificate_name_id">
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="society_noc">Aadhaar Card<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="aadhaar_card" id="aadhaar_card" class="custom-file-input">
                                                <label class="custom-file-label" for="aadhaar_card">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="aadhaar_card_name" id="aadhaar_card_name" class="text-info"> Please select a document </label>
                                        <input type="hidden" name="aadhaar_card_name" id="aadhaar_card_name_id">
                                    </h3>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="society_noc">User image<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="user_image" id="user_image" class="custom-file-input">
                                                <label class="custom-file-label" for="user_image">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="user_image_name" id="user_image_name" class="text-info"> Please select a document </label>
                                        <input type="hidden" name="user_image_name" id="user_image_name_id">
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
                                                <span class="text-danger">File size should Not be more than 5 MB.</span>
                                            </li>
                                        </ul>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        
                        </div>
                        <div class="card-footer">
                            <div class="row center">
                                <div class="col-12">
                                    <input type="hidden" value="<?= $application_type ?>" name="application_type">
                                    <a href="<?= base_url('clinic/create') ?>" class="btn btn-lg btn-info white">Cancel</a>
                                    <button type="submit" class="btn btn-lg btn-primary right">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('includes/footer');?>
</div>
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>


<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/clinic.js" id="createclinic" is_user="<?= $this->authorised_user['is_user']; ?>"></script>
<script type="text/javascript">
    $('#request_letter').change(function() {
      var file = $('#request_letter')[0].files[0].name;
      $('#request_letter_name').text(file);
    });
    
    $('#geo_location_map').change(function() {
      var file = $('#geo_location_map')[0].files[0].name;
      $('#geo_map_name').text(file);
    });
</script>
</body>
</html>