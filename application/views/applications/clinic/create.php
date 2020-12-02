<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
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
                                        <label for="technical_qualification">Application date<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="application_date" id="application_date_clinic" placeholder="Application date">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="mobile_no">Applicant Mobile no<span class="red">*</span></label>
                                        <input type="number" class="form-control" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter mobile no">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label  for="alert_mobile_no">Alternate Mobile no<span class="grey"> (optional)</span></label>
                                        <input type="number" class="form-control" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile no">
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
                                        <label for="alert_mobile_no">Nationality of Applicant<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="applicant_nationality" id="applicant_nationality" placeholder="Enter applicant nationality">
                                    </div>
                                </div>
                                
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="technical_qualification">Technical qualification<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="technical_qualification" id="technical_qualification" placeholder="Enter technical qualification">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Situation of Registration<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="situation_of_registration" id="situation_of_registration" placeholder="Enter Situation of Registration">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Fee Charges<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="fee_charges" id="fee_charges" placeholder="Enter Fee Charges">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Certificate expirydate<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="Certificate_expirydate" id="Certificate_expirydate" placeholder="Enter Certificate expirydate">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title">
                                <label for="email_id" class="text-info">clinic Information</label>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="clinic_name">Name of clinic<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="clinic_name" id="clinic_name" placeholder="Enter clinic name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="contact_no">Contact No<span class="red">*</span></label>
                                        <input type="number" class="form-control" name="contact_no" id="contact_no" placeholder="Enter landline no">
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
                                        <label for="clinic_address">clinic Address<span class="red">*</span></label>
                                        <textarea type="text" class="form-control" name="clinic_address" id="applicant_address" placeholder="Enter clinic address"></textarea> 
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card-header">
                            <h3 class="card-title">
                                <label for="email_id" class="text-info">Nursing home Information</label>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">Name of nursing home<span class="grey">&nbsp;(optional)</span></label>
                                        <input type="text" class="form-control" name="name_of_nursinghome" id="alien_name" placeholder="Enter alien name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">Address of nursing home</label>
                                        <input type="text" class="form-control" name="address_of_nursinghome" id="alien_name" placeholder="Enter alien name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">arrng_immunization_of_the_employees</label>
                                        <input type="text" class="form-control" name="arrng_immunization_of_the_employees" id="arrng_immunization_of_the_employees" placeholder="Enter alien name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">sanitary_conveniences_for_emp</label>
                                        <input type="text" class="form-control" name="sanitary_conveniences_for_emp" id="sanitary_conveniences_for_emp" placeholder="Enter alien name">
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
                                        <input type="text" class="form-control" name="floor_space" id="floor_space" placeholder="Enter floor space">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="check_up_details">Medical check up details<span class="grey">&nbsp;(optional)</span></label>
                                        <input type="text" class="form-control" name="check_up_details" id="check_up_details" placeholder="Enter check up details">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="floor_space_kitchen">Floor Space for kitchen<span class="grey">&nbsp;(optional)</span></label>
                                        <input type="text" class="form-control" name="floor_space_kitchen" id="floor_space_kitchen" placeholder="Enter floor space">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="sanitary_details">Sanitary conveniences Details<span class="grey">&nbsp;(optional)</span></label>
                                        <input type="text" class="form-control" name="sanitary_details" id="sanitary_details" placeholder="Enter sanitary details">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="storage_details">Storage and service food Details<span class="grey">&nbsp;(optional)</span></label>
                                        <input type="text" class="form-control" name="storage_details" id="storage_details" placeholder="Enter storage details">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="others">Others<span class="grey">&nbsp;(optional)</span></label>
                                        <input type="text" class="form-control" name="others" id="others" placeholder="Enter storage others">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="maternity_beds">No. of maternity beds<span class="red">*</span></label>
                                        <input type="number" class="form-control" name="maternity_beds" id="maternity_beds" placeholder="Enter no. of maternity beds">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="patient_beds">No. of patient beds<span class="red">*</span></label>
                                        <input type="number" class="form-control" name="patient_beds" id="patient_beds" placeholder="Enter no. of patient beds">
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
                                    <button type="button" id="addRow" class="form-control btn btn-md btn-info">Add</button>
                                </div>
                            </div>
                            <br>  
                            <div class="row">
                                <div class="col-12">
                                    <table class="table staff_grid">
                                        <thead>
                                            <tr class = "text-center">
                                                <th>Staff Name</th>
                                                <th>Designation</th>
                                                <th>Age</th>
                                                <th>Qualification</th>
                                                <th>Acomodation</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class = "tableBody">
                                            <tr id="oneStaffField">
                                                <td>
                                                    <input class="form-control" type="text" id="staff_name" name="staff_name[]">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="staff_designation[]" id="staff_designation">
                                                        <option value="">---Select staff designation---</option>
                                                        <?php foreach ($designation as $desig) : ?>
                                                            <option value="<?= $desig['design_id'] ?>"><?= $desig['design_title'] ?></option>
                                                        <?php endforeach ; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="number" id="staff_age" name="staff_age[]">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="staff_qualification[]" id="staff_qualification">
                                                        <option value="">---Select staff qualification---</option>
                                                        <?php foreach ($qualification as $quali) : ?>
                                                            <option value="<?= $quali['qual_id'] ?>"><?= $quali['qual_title'] ?></option>
                                                        <?php endforeach ; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" type="text" id="staff_accommodation" name="staff_accommodation[]">
                                                </td>
                                                <td style="vertical-align:inherit;cursor: pointer;"><i class="fas fa-trash fa-lg delete_row"></i></td>
                                            </tr>
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
                                        <input type="text" class="form-control" name="alien_name" id="alien_name" placeholder="Enter alien name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_details">Alien Details<span class="grey">&nbsp;(optional)</span></label>
                                        <input type="text" class="form-control" name="alien_details" id="alien_details" placeholder="Enter alien details">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-header">
                            <h3 class="card-title">
                                <label class="text-info">Other Business</label>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">Other business</label>
                                        <input type="text" class="form-control" name="other_business" id="alien_name" placeholder="Other business details">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">Other business details</label>
                                        <input type="text" class="form-control" name="other_business" id="other_business_details" placeholder="Enter Other business details">
                                    </div>
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
                            <!-- <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="reg_certificate">Doctor Registration Certificate<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="reg_certificate" id="reg_certificate" class="custom-file-input">
                                                <label class="custom-file-label" for="reg_certificate">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="reg_certificate_name"  class="text-info"> Please select a document </label>
                                        <input type="hidden" name="reg_certificate_name" id="reg_certificate_name_id">
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="staff_certificate">Paramedical Staff Certificate<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="staff_certificate" id="staff_certificate" class="custom-file-input">
                                                <label class="custom-file-label" for="staff_certificate">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="staff_certificate_name"  class="text-info"> Please select a document </label>
                                        <input type="hidden" name="staff_certificate_name" id="staff_certificate_name_id">
                                    </h3>
                                </div>
                            </div> -->
                            <!-- <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="nursing_staff_deg_certificate">Nursing Staff Degree Certificate<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="nursing_staff_deg_certificate" id="nursing_staff_deg_certificate" class="custom-file-input">
                                                <label class="custom-file-label" for="nursing_staff_deg_certificate">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="nursing_staff_deg_certificate_name"  class="text-info"> Please select a document </label>
                                        <input type="hidden" name="nursing_staff_deg_certificate_name" id="nursing_staff_deg_certificate_name_id">
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="nursing_staff_reg_certificate">Nursing Staff Degree Certificate<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="nursing_staff_reg_certificate" id="nursing_staff_reg_certificate" class="custom-file-input">
                                                <label class="custom-file-label" for="nursing_staff_reg_certificate">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="nursing_staff_reg_certificate_name"  class="text-info"> Please select a document </label>
                                        <input type="hidden" name="nursing_staff_reg_certificate_name" id="nursing_staff_reg_certificate_name_id">
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="bio_des_certificate">Bio Disposal waste certificate<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="bio_des_certificate" id="bio_des_certificate" class="custom-file-input">
                                                <label class="custom-file-label" for="bio_des_certificate">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="bio_des_certificate_name"  class="text-info"> Please select a document </label>
                                        <input type="hidden" name="bio_des_certificate_name" id="bio_des_certificate_name_id">
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="society_noc">Society NOC<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="society_noc" id="society_noc" class="custom-file-input">
                                                <label class="custom-file-label" for="society_noc">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="society_noc_name" id="society_noc_name"  class="text-info"> Please select a document </label>
                                        <input type="hidden" name="society_noc_name" id="society_noc_name_id">
                                    </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="fire_noc">Fire Department NOC<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="fire_noc" id="fire_noc" class="custom-file-input">
                                                <label class="custom-file-label" for="fire_noc">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="fire_noc_name" id="fire_noc_name"  class="text-info"> Please select a document </label>
                                        <input type="hidden" name="fire_noc_name" id="fire_noc_name_id">
                                    </h3>
                                </div>
                            </div> -->
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
                                    <input type="hidden" value="<?= $application_type ?>" name="application_type">
                                    <a href="<?= base_url()?>pwd" class="btn btn-lg btn-info white">Cancel</a>
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