<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">

                    <h3 class="text-center mt-1">
                        <label for="email_id" class="text-info">Application for Registration/Renewal of registration under section 5 of the Bombay Nursing Home Registration Act, 1949</label>
                    </h3>
                    <hr />

                    <form role="form" class="hospital-form" id="hospital-form" method="post" enctype="multipart/form-data">
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
                                        <label for="applicant_name">1.Applicant Name<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Full name of the Applicant"></i>
                                        <input type="text" class="form-control" name="applicant_name" id="applicant_name" placeholder="Enter Applicant name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="mobile_no">1.a. Mobile Number<span class="red">*</span></label>
                                        <input type="number" class="form-control" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter mobile number">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label  for="alert_mobile_no">1.b. Alternate Mobile Number<span class="grey"> (optional)</span></label>
                                        <input type="number" class="form-control" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile number">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email_id">1.c. E-mail Address<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Note Notifications and documents will be shared on this email id"></i>
                                        <input type="text" class="form-control" name="applicant_email_id" id="applicant_email_id" placeholder="Enter email Id">
                                    </div>
                                </div>
                                <div class="col-4">
                                <div class="form-group">
                                        <label for="alert_mobile_no">2.Residential Address<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Full residential address of the applicant"></i>
                                        <textarea type="text" rows="1" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address"></textarea> 
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="technical_qualification">3.Technical Qualifications<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="technical_qualification" id="technical_qualification" placeholder="Enter technical qualification">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">4.Nationality of the Applicant<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="applicant_nationality" id="applicant_nationality" placeholder="Enter applicant nationality">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">5.Situation of the registered office<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Situation of the registered or principal office of the Company, Society, Association or other body corporate"></i>
                                        <input type="text" class="form-control" name="situation_of_registration" id="situation_of_registration" placeholder="Enter Situation of Registration">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">6.Name of the Nursing Home<span class="grey"></span></label>
                                         <i class="fas fa-info-circle" title="Name and other particulars of the nursing home in respect of which the registration is applied for"></i>
                                        <input type="text" class="form-control" name="name_of_nursinghome" id="alien_name" placeholder="Enter Name of the Nursing home">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">7.Location of Nursing Home</label>
                                        <i class="fas fa-info-circle" title="Place where the nursing home is situated"></i>
                                        <input type="text" class="form-control" name="address_of_nursinghome" placeholder="Enter Location name">
                                    </div>
                                </div>
                                <!-- <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">2.Residential Address<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Full residential address of the applicant"></i>
                                        <textarea type="text" rows="1" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address"></textarea> 
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="card-header mt-5">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="card-title">
                                        <label for="email_id" class="text-info">Brief description of the construction, size and equipment of the nursing home or any premises used in connection there with as detailed below</label>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="floor_space">8.a. Floor space of bed rooms<span class="red">*</span></label>
                                            <i class="fas fa-info-circle" title="Floor space of bed rooms provided for the patients giving number of beds"></i>
                                        <span id="add_one_flore" class="float-right btn btn-sm btn-primary">Add</span>
                                        <div class="flore_container mt-2">
                                            <div class="row mb-3 flore_details_container" id="singel_flore_data">
                                                <div class="col-3">
                                                    <input class="form-control" placeholder="Enter floor Number" name="flore_number[]" type="text" multiple>  
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control" placeholder="Enter total bedrooms on floor" name="bedrooms_on_flore[]" type="number" multiple>
                                                </div>
                                                <div class="col-4">
                                                    <input class="form-control" placeholder="Enter total Number of beds" name="number_of_beds[]" type="number" multiple>
                                                </div>
                                                <div class="col-1">
                                                    <a class="btn btn-danger float-right"><i class="fas fa-trash delete_floor"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alien_name">8.b. Arrangements made for medical check-up</label>
                                        <i class="fas fa-info-circle" title="Arrangements made for medical check-up and immunization of the employees."></i>
                                        <textarea class="form-control" name="arrangement_for_checkup" rows="5"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="alien_name">8.c. Floor Space of kitchen, servant’s rooms, etc</label>
                                    <i class="fas fa-info-circle" title="Floor space of kitchen, servant's rooms and other rooms giving details of user and area of each room"></i>
                                    <table class="table fsk_table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Room name</th>
                                                <th>Floor Name</th>
                                                <th>Area(sq.ft)</th>
                                                <th>User (patients/employee)</th>
                                                <th><a class="btn-primary btn btn-sm fsk_add_row" href="javascript:void(0)">Add</a></th>
                                            </tr>
                                        </thead>
                                        <tbody class="fsk_tablebody">
                                            <tr id="oneRow">
                                                <td><input type="text" class="form-control" name="fs_for_kitchen_room_name[]" multiple></td>
                                                <td><input type="text" class="form-control" name="fs_for_kitchen_floor_name[]" multiple></td>
                                                <td><input type="number" class="form-control" name="fs_for_kitchen_area[]" multiple></td>
                                                <td>
                                                    <select name="fs_for_kitchen_user_type[]" id="fs_for_kitchen_user_type" class="form-control">
                                                        <option value="">---Select user---</option>
                                                        <option value="1">patients</option>
                                                        <option value="2">employee</option>
                                                    </select>
                                                </td>
                                                <td><i class="fas fa-trash fsk_delete_row fa-2x"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">8.d. sanitary for employees</label>
                                        <i class="fas fa-info-circle" title="Details of arrangement made for sanitary for employee giving their number"></i>
                                        <input type="text" class="form-control" name="detail_arrange_sanitary_employee">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">8.d. sanitary for patients</label>
                                        <i class="fas fa-info-circle" title="Details of arrangement made for sanitary for patients giving their number"></i>
                                        <input type="text" class="form-control" name="detail_arrange_sanitary_patients">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">8.e. Arrangements made for storage</label>
                                        <i class="fas fa-info-circle" title="Details of arrangements made for storage and service of food."></i>
                                        <input type="text" class="form-control" name="storage_arrangements">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alien_name">9.Other purpose</label>
                                        <i class="fas fa-info-circle" title="Whether the nursing home or any premises used in connection therewith are used or are to be used for purposes other than that of carrying on a nursing home."></i>
                                        <textarea class="form-control" name="other_purpose" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">10.a. Maternity beds</label>
                                        <i class="fas fa-info-circle" title="Total Number of beds for Maternity patients."></i>
                                        <input type="text" class="form-control" name="maternity_beds_number">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">10.b. Patients beds</label>
                                        <i class="fas fa-info-circle" title="Total Number of beds for other patients."></i>
                                        <input type="text" class="form-control" name="patients_beds_number">
                                    </div>
                                </div>



                                <div class="col-12">
                                    <label for="alien_name">11.Nursing Staff Information</label>
                                    
                                    <table class="table staff_grid">
                                        <thead>
                                            <tr class = "text-center">
                                                <th>Staff Name</th>
                                                <th>Designation</th>
                                                <th>Age</th>
                                                <th>Qualification</th>
                                                <th><button type="button" id="addRow" class="btn btn-sm btn-primary">Add</button></th>
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
                                                <td style="vertical-align:inherit;cursor: pointer;"><i class="fas fa-trash fa-lg delete_row fa-2x"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alien_name">12.Accomodation</label>
                                        <i class="fas fa-info-circle" title="Place of Accomodation."></i>
                                        <textarea class="form-control" name="Accomodation" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label>13.Physician / Surgeon Information</label>
                                    <table class="table surgeon_table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Qualification</th>
                                                <th>Visiting</th>
                                                <th><button type="button" class="btn btn-sm btn-primary surgeonBtn">Add</button></th>
                                            </tr>
                                        </thead>
                                        <tbody class="surgeonTableBody">
                                            <tr id="surgeonField">
                                                <td><input class="form-control" type="text" name="surgeon_name[]" multiple></td>
                                                <td><input class="form-control" type="number" name="surgeon_age[]" multiple></td>
                                                <td><input class="form-control" type="text" name="surgeon_qualification[]" multiple></td>
                                                <td><input class="form-control" type="text" name="surgeon_is_visiting[]" multiple></td>
                                                <td><i class="fas fa-trash surgeonDeleteBtn fa-2x"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <br /><br />

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>14.a. Nursing home is under the supervision</label>
                                        <i class="fas fa-info-circle" title="Whether the nursing home is under the supervision of a qualified medical practitioner / qualified nurse"></i>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_supervision" value="Yes">
                                            <label class="form-check-label">
                                                Yes
                                            </label>
                                        </div>   
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_supervision" value="No">
                                            <label class="form-check-label">
                                                No
                                          </label>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-12 supervision_container" style="display: none;">
                                    <label for="supervision_name">Supervisor Details</label>
                                    <table class="table supervision_table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Qualification</th>
                                                <th><button type="button" class="btn btn-sm btn-primary addsupervisionbtn">Add</button></th>
                                            </tr>
                                        </thead>
                                        <tbody class="supervisionTableBody">
                                            <tr id="onesupervisionField">
                                                <td><input class="form-control" type="text" name="supervision_name[]" multiple></td>
                                                <td><input class="form-control" type="number" name="supervision_age[]" multiple></td>
                                                <td><input class="form-control" type="text" name="supervision_qualification[]" multiple></td> 
                                                <td><i class="fas fa-trash supervisionDeleteBtn fa-2x"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alien_name">14.b. Proportion of the qualified</label>
                                        <i class="fas fa-info-circle" title="Proportion of the qualified and unqualified nurses on the nursing staff."></i>
                                        <input type="text" class="form-control" name="proportion_of_qualified">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>15.a. Nursing home is under the supervision</label>
                                        <i class="fas fa-info-circle" title="Whether the nursing home is under the supervision of a qualified nurse or midwife."></i>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_midwife" value="Yes">
                                            <label class="form-check-label">
                                                Yes
                                            </label>
                                        </div>  
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_midwife" value="No">
                                            <label class="form-check-label">
                                                No
                                          </label>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-12 midwife_container" style="display: none;">
                                    <label for="midwife_name">Details of nurse/midwife</label>
                                    <table class="table midwife_table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Qualification</th>
                                                <th><button type="button" class="btn btn-sm btn-primary addmidwifebtn">Add</button></th>
                                            </tr>
                                        </thead>
                                        <tbody class="midwifeTableBody">
                                            <tr id="onemidwifeField">
                                                <td><input class="form-control" type="text" name="midwife_name[]" multiple></td>
                                                <td><input class="form-control" type="number" name="midwife_age[]" multiple></td>
                                                <td><input class="form-control" type="text" name="midwife_qualification[]" multiple></td> 
                                                <td><i class="fas fa-trash midwifeDeleteBtn fa-2x"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class='col-12'>
                                    <div calss="form-group">
                                        <label>15.b. Unregisterd medical staff</label>
                                        <i class="fas fa-info-circle" title="Whether any unregistered medical practitioner or unqualified midwife is employed for nursing any patient in the nursing home." ></i>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="unregisterd_medical"  value="Yes">
                                        <label class="form-check-label">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="unregisterd_medical"  value="No">
                                        <label class="form-check-label">
                                            No
                                        </label>
                                    </div>
                                </div>

                                 <div class="col-12">
                                    <div class="form-group">
                                        <label>16.Whether any person of Alien nationality</label>
                                        <i class="fas fa-info-circle" title="Whether any person of Alien nationality is employed in the nursing home."></i>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_alien" value="Yes">
                                            <label class="form-check-label">
                                                Yes
                                            </label>
                                        </div>   
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_alien" value="No">
                                            <label class="form-check-label">
                                                No
                                          </label>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-12 alien_container" style="display: none;">
                                    <label for="alien_name">Alien Details:</label>
                                    <table class="table alien_table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Qualification</th>
                                                <th>Nationality</th>
                                                <th><button type="button" class="btn btn-sm btn-primary addalienbtn">Add</button></th>
                                            </tr>
                                        </thead>
                                        <tbody class="alienTableBody">
                                            <tr id="onealienField">
                                                <td><input class="form-control" type="text" name="alien_name[]" multiple></td>
                                                <td><input class="form-control" type="number" name="alien_age[]" multiple></td>
                                                <td><input class="form-control" type="text" name="alien_qualification[]" multiple></td>
                                                <td><input class="form-control" type="text" name="alien_nationality[]" multiple></td>
                                                <td><i class="fas fa-trash alienDeleteBtn fa-2x"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-12">
                                    <label for="alien_name">17.Fees Charges:</label>
                                    <table class="table fees_table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Sr.No.</th>
                                                <th>Service</th>
                                                <th>Charges (in ₹)</th>
                                                <th><button type="button" class="btn btn-sm btn-primary addFeesbtn">Add</button></th>
                                            </tr>
                                        </thead>
                                        <tbody class="feesTableBody">
                                            <tr id="oneFeesField">
                                                <td><input class="form-control" type="number" name="sr_number[]" multiple></td>
                                                <td><input class="form-control" type="text" name="fees_service[]" multiple></td>
                                                <td><input class="form-control" type="number" name="charges[]" multiple></td>
                                                <td><i class="fas fa-trash fessDeleteBtn fa-2x"></i></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>



                                <div class="col-12">
                                    <div class="form-group">
                                        <label>18.Other nursing home or business</label>
                                        <i class="fas fa-info-circle" title="Whether the applicant is interested in any other nursing home or business"></i>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="other_bussness" value="Yes">
                                            <label class="form-check-label">
                                                Yes
                                            </label>
                                        </div>   
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="other_bussness" value="No">
                                            <label class="form-check-label">
                                                No
                                          </label>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-12 other_bussness_container" style="display: none;">
                                    <div class="form-group">
                                        <textarea class="form-control" rows="5" name="other_business_address" placeholder="Enter details of the place where such business is conducted."></textarea>
                                    </div>
                                </div>
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

                        
                        <div class="card-header mt-5">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="card-title">
                                        <label for="email_id" class="text-info">Attachments</label>
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
                            </div>
                            <div class="row">
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
                                <div class="col-12">
                                    <div class="form-check">
                                        <input type="checkbox"  name="promise" value="1" class="form-check-input" id="promise">
                                        <label class="form-check-label" for="exampleCheck1"><b>I solemnly declare that the above statements are true to the best of my knowledge and belief.</b></label>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 text-left text-danger">
                                    <p><b>Note:</b><br>
                                     *The queries made in item 14 should be answered only when the nursing home is a maternity or mixed home(i.e home having maternity and non-maternity wards).<br> In case of a mixed home, the queries should be answered with reference to the non-maternity ward.</p>
                                    <p>*The queries made in item 15 should be answered only when the nursing home is a maternity or mixed home(i.e home having maternity and non-maternity wards).<br> In case of a mixed home, the queries should be answered with reference to the maternity ward.</p>
                                </div>
                            </div>
                        </div>

                        

                        <div class="card-footer">
                            <div class="row center">
                                <div class="col-12">
                                    <input type="hidden" value="<?= $application_type ?>" name="application_type">
                                    <a href="<?= base_url('hospital/create') ?>" class="btn btn-lg btn-info white">Cancel</a>
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


<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/hospital.js" id="createHospital" is_user="<?= $this->authorised_user['is_user']; ?>"></script>
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