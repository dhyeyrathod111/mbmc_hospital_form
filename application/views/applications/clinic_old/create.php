<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <form role="form" class="clinic-form" id="clinic-form" method="post" enctype="multipart/form-data">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="card-title">
                                        <label for="email_id" class="text-info">Personal Information</label>
                                    </h3>
                                    <a class="btn btn-success float-right" href="<?= base_url('clinic/user_apps_list') ?>">Application List</a>
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
                                        <input type="text" class="form-control" value="<?= $app_no; ?>" name="application_no" id="application_no" placeholder="Enter Application no" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="applicant_name">Applicant Name<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="applicant_name" id="applicant_name" placeholder="Enter Applicant name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email_id">Applicant Email Id<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="applicant_email_id" id="applicant_email_id" placeholder="Enter email Id">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="mobile_no">Applicant Mobile no<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter mobile no">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label  for="alert_mobile_no">Alternate Mobile no<span class="grey"> (optional)</span></label>
                                        <input type="text" class="form-control" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile no">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Applicant Address<span class="red">*</span></label>
                                        <textarea type="text" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address"></textarea> 
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="applicant_qualification">Applicant qualification<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="applicant_qualification" id="applicant_qualification" placeholder="Enter applicant qualification">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title">
                                <label for="email_id" class="text-info">Clinic Information</label>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="clinic_name">Name of Clinic<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="clinic_name" id="clinic_name" placeholder="Enter clinic name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="contact_no">Contact No<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="contact_no" id="contact_no" placeholder="Enter landline no">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="contact_person">Contact Person<span class="red">*</span></label>
                                        <input type="text" name="contact_person" class="form-control" id="contact_person" placeholder="Enter Name of contact person">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="clinic_address">Clinic Address<span class="red">*</span></label>
                                        <textarea type="text" class="form-control" name="clinic_address" id="clinic_address" placeholder="Enter clinic address"></textarea> 
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
                                            <tr>
                                                <td class = 'text-center name'>
                                                    <input type="text" class="form-control" name="staff_name[]" id="staff_name" placeholder="Enter Staff Name">
                                                </td>
                                                <td class = 'text-center activity'>
                                                    <select class='selectpicker form-control' id='designation' name='designation[]' data-live-search='true' required="">
                                                        <option value="0">Select Designation</option>
                                                        option
                                                        <?php
                                                            foreach ($designation as $key => $design) { ?>
                                                        <option value = '<?= $design['design_id'] ?>'><?= $design['design_title']?></option>
                                                        ;
                                                        <?php  } ?>
                                                    </select>
                                                </td>
                                                <td class = 'text-center noOfTrees'>
                                                    <input type = 'number' class="form-control" name = 'age[]' id = 'age[]' placeholder='Enter Age' required="">
                                                </td>
                                                <td class = 'text-center permission'>
                                                    <select class='selectpicker form-control' id='qualification' name='qualification[]' data-live-search='true' required="">
                                                        <option value="0">Select Qualification</option>
                                                        option
                                                        <?php
                                                            foreach ($qualification as $key => $qual) { ?>
                                                        <option value = '<?= $qual['qual_id'] ?>'><?= $qual['qual_title']?></option>
                                                        ;
                                                        <?php  } ?>
                                                    </select>
                                                </td>
                                                <td class = 'text-center action'><span class = 'delete' data-id = '0'><i class='fas fa-trash'></i></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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
                                        <label for="" id="tax_receipt_name"  class="text-info"> Please select a document </label>
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
                                        <label for="" id="doc_certificate_name"  class="text-info"> Please select a document </label>
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
                                        <label for="" id="bio_medical_certificate_name"  class="text-info"> Please select a document </label>
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
                                        <label for="aadhaar_card_name" id="aadhaar_card_name"  class="text-info"> Please select a document </label>
                                        <input type="hidden" name="aadhaar_card_name" id="aadhaar_card_name_id">
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
                            <div class="row center">
                                <div class="col-12">
                                    <a href="<?= base_url()?>clinic" class="btn btn-lg btn-info white">Cancel</a>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/clinic.js" id = "createClinic" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>
</body>
</html>