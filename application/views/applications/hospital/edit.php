<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
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
                                        <input type="hidden" value="<?= $application->id ?>" name="id" id="id">
                                        <input type="text" class="form-control" readonly value="<?= 'MBMC-00000'.$application->app_id ?>" name="app_id">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="applicant_name">Applicant Name<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Full name of the Applicant"></i>
                                        <input type="text" class="form-control" value="<?= $application->applicant_name ?>" name="applicant_name" id="applicant_name" placeholder="Enter Applicant name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="mobile_no">Mobile Number<span class="red">*</span></label>
                                        <input type="number" value="<?= $application->applicant_mobile_no ?>" class="form-control" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter mobile no">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label  for="alert_mobile_no">Alternate Mobile no<span class="grey"> (optional)</span></label>
                                        <input type="number" value="<?= $application->applicant_alternate_no ?>" class="form-control" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile no">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email_id">E-mail Address<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Note Notifications and documents will be shared on this email id"></i>
                                        <input type="text" value="<?= $application->applicant_email_id ?>" class="form-control" name="applicant_email_id" id="applicant_email_id" placeholder="Enter email Id">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="technical_qualification">Technical Qualifications<span class="red">*</span></label>
                                        <input type="text" value="<?= $application->technical_qualification ?>" class="form-control" name="technical_qualification" id="technical_qualification" placeholder="Enter technical qualification">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Nationality of the Applicant<span class="red">*</span></label>
                                        <input type="text" value="<?= $application->applicant_nationality ?>" class="form-control" name="applicant_nationality" id="applicant_nationality" placeholder="Enter applicant nationality">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Situation of the registered office<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Situation of the registered or principal office of the Company, Society, Association or other body corporate"></i>
                                        <input type="text" value="<?= $application->situation_registration ?>" class="form-control" name="situation_of_registration" id="situation_of_registration" placeholder="Enter Situation of Registration">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">Name of the Nursing Home<span class="grey"></span></label>
                                        <input type="text" value="<?= $application->hospital_address ?>" class="form-control" name="name_of_nursinghome" id="alien_name" placeholder="Enter alien name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">Location of Nursing Home</label>
                                        <i class="fas fa-info-circle" title="Place where the nursing home is situated"></i>
                                        <input type="text" value="<?= $application->hospital_address ?>" class="form-control" name="address_of_nursinghome" placeholder="Enter Location name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Residential Address<span class="red">*</span></label>
                                        <i class="fas fa-info-circle" title="Full residential address of the applicant"></i>
                                        <textarea type="text" rows="1" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address"><?= $application->applicant_address ?></textarea>
                                    </div>
                                </div>
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
                                        <label for="floor_space">Floor space of bed rooms provided for the<span class="red">*</span></label>
                                            <i class="fas fa-info-circle" title="number of floor and bed count."></i>
                                        <span id="add_one_flore" class="float-right btn btn-sm btn-primary">Add</span>
                                        <div class="flore_container mt-2">
                                            <?php foreach ($FS_bedrooms as $key => $onespace) : ?>
                                                <div class="row mb-3 flore_details_container" id="singel_flore_data">
                                                    <div class="col-3">
                                                        <input class="form-control" value="<?= $onespace->floor_number ?>" placeholder="Enter floor Number" name="flore_number[]" type="text" multiple>  
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-control" value="<?= $onespace->total_bedrooms_on_flore ?>" placeholder="Enter total bedrooms on floor" name="bedrooms_on_flore[]" type="number" multiple>
                                                    </div>
                                                    <div class="col-4">
                                                        <input class="form-control" value="<?= $onespace->total_number_of_beds ?>" placeholder="Enter total Number of beds" name="number_of_beds[]" type="number" multiple>
                                                    </div>
                                                    <div class="col-1">
                                                        <a class="btn btn-danger float-right"><i class="fas fa-trash delete_floor"></i></a>
                                                    </div>
                                                </div>
                                            <?php endforeach ; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alien_name">Arrangements made for medical check-up:</label>
                                        <i class="fas fa-info-circle" title="Arrangements made for medical check-up and immunization of the employees."></i>
                                        <textarea class="form-control" name="arrangement_for_checkup" rows="5"><?= $application->arrangement_for_checkup ?></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="alien_name">Floor Space of kitchen, servant’s rooms:</label>
                                    <i class="fas fa-info-circle" title="Arrangements made for medical check-up and immunization of the employees."></i>
                                    <table class="table fsk_table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Room name</th>
                                                <th>Floor Name</th>
                                                <th>Area</th>
                                                <th>User (patients/employee)</th>
                                                <th><a class="btn-primary btn btn-sm fsk_add_row" href="javascript:void(0)">Add</a></th>
                                            </tr>
                                        </thead>
                                        <tbody class="fsk_tablebody">
                                            <?php foreach ($FS_kitchen as $key => $kitchen) : ?>
                                                <tr id="oneRow">
                                                    <td><input type="text" value="<?= $kitchen->room_name ?>" class="form-control" name="fs_for_kitchen_room_name[]" multiple></td>
                                                    <td><input value="<?= $kitchen->room_name ?>" type="text" class="form-control" name="fs_for_kitchen_floor_name[]" multiple></td>
                                                    <td><input value="<?= $kitchen->area ?>" type="number" class="form-control" name="fs_for_kitchen_area[]" multiple></td>
                                                    <td>
                                                        <select value="<?= $kitchen->room_name ?>" name="fs_for_kitchen_user_type[]" id="fs_for_kitchen_user_type" class="form-control">
                                                            <option value="">---Select user---</option>
                                                            <option <?= ($kitchen->user == 1) ? 'selected' : ''  ?> value="1">patients</option>
                                                            <option <?= ($kitchen->user == 2) ? 'selected' : ''  ?> value="2">employee</option>
                                                        </select>
                                                    </td>
                                                    <td><i class="fas fa-trash fsk_delete_row fa-2x"></i></td>
                                                </tr>
                                            <?php endforeach ; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">sanitary for employee</label>
                                        <i class="fas fa-info-circle" title="number of Details of arrangement made for sanitary for employee"></i>
                                        <input type="text" value="<?= $application->detail_arrange_sanitary_employee ?>" class="form-control" name="detail_arrange_sanitary_employee">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">sanitary for patients</label>
                                        <i class="fas fa-info-circle" title="number of Details of arrangement made for sanitary for patients"></i>
                                        <input type="text" value="<?= $application->detail_arrange_sanitary_patients ?>" class="form-control" name="detail_arrange_sanitary_patients">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">Arrangements made for storage</label>
                                        <i class="fas fa-info-circle"  title="Details of arrangements made for storage and service of food."></i>
                                        <input type="text" value="<?= $application->storage_arrangements ?>" class="form-control" name="storage_arrangements">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alien_name">Other purpose</label>
                                        <i class="fas fa-info-circle" title="Whether the nursing home or any premises used in connection therewith are used or are to be used for purposes other than that of carrying on a nursing home."></i>
                                        <textarea class="form-control" name="other_purpose" rows="5"><?= $application->others ?></textarea>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">Maternity beds Number:</label>
                                        <i class="fas fa-info-circle" title="Total Number of beds for Maternity patients"></i>
                                        <input type="text" value="<?= $application->maternity_beds ?>" class="form-control" name="maternity_beds_number">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">patients beds numebr</label>
                                        <i class="fas fa-info-circle" title="Total Number of beds for other patients."></i>
                                        <input type="text" value="<?= $application->patient_beds ?>" class="form-control" name="patients_beds_number">
                                    </div>
                                </div>



                                <div class="col-12">
                                    <label for="alien_name">Nursing Staff Information:</label>
                                    
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
                                            <?php foreach ($nurcing_staff_details as $key => $onenurce) : ?>
                                            <tr id="oneStaffField">
                                                <td>
                                                    <input class="form-control" value="<?= $onenurce->staff_name ?>" type="text" id="staff_name" name="staff_name[]">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="staff_designation[]" id="staff_designation">
                                                        <option value="">---Select staff designation---</option>
                                                        <?php foreach ($designation as $desig) : ?>
                                                            <option <?= ($onenurce->design_id == $desig['design_id']) ? 'selected' : '' ?> value="<?= $desig['design_id'] ?>"><?= $desig['design_title'] ?></option>
                                                        <?php endforeach ; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input class="form-control" value="<?= $onenurce->age ?>" type="number" id="staff_age" name="staff_age[]">
                                                </td>
                                                <td>
                                                    <select class="form-control" name="staff_qualification[]" id="staff_qualification">
                                                        <option value="">---Select staff qualification---</option>
                                                        <?php foreach ($qualification as $quali) : ?>
                                                            <option <?= ($onenurce->qual_id == $quali['qual_id']) ? 'selected' : '' ?> value="<?= $quali['qual_id'] ?>"><?= $quali['qual_title'] ?></option>
                                                        <?php endforeach ; ?>
                                                    </select>
                                                </td>
                                                <td style="vertical-align:inherit;cursor: pointer;"><i class="fas fa-trash fa-lg delete_row fa-2x"></i></td>
                                            </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Accomodation:</label>
                                        <i class="fas fa-info-circle" title="Place of Accomodation."></i>
                                        <textarea class="form-control" name="Accomodation" rows="5"><?= $application->accomodation ?></textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label>Physician / Surgeon Information:</label>
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
                                            <?php foreach ($surgeons as $key => $onesurgeon) : ?>
                                                <tr id="surgeonField">
                                                    <td><input value="<?= $onesurgeon->name ?>" class="form-control" type="text" name="surgeon_name[]" multiple></td>
                                                    <td><input value="<?= $onesurgeon->age ?>" class="form-control" type="number" name="surgeon_age[]" multiple></td>
                                                    <td><input value="<?= $onesurgeon->qualification ?>" class="form-control" type="text" name="surgeon_qualification[]" multiple></td>
                                                    <td><input value="<?= $onesurgeon->visiting ?>" class="form-control" type="text" name="surgeon_is_visiting[]" multiple></td>
                                                    <td><i class="fas fa-trash surgeonDeleteBtn fa-2x"></i></td>
                                                </tr>
                                            <?php endforeach ; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Nursing home is under the supervision</label>
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
                                    <label for="supervision_name">Supervision Details:</label>
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
                                            <?php foreach ($supervision as $key => $onesupervision) : ?>
                                                <tr id="onesupervisionField">
                                                    <td><input value="<?= $onesupervision->name ?>" class="form-control" type="text" name="supervision_name[]" multiple></td>
                                                    <td><input value="<?= $onesupervision->age ?>" class="form-control" type="number" name="supervision_age[]" multiple></td>
                                                    <td><input value="<?= $onesupervision->qualification ?>" class="form-control" type="text" name="supervision_qualification[]" multiple></td> 
                                                    <td><i class="fas fa-trash supervisionDeleteBtn fa-2x"></i></td>
                                                </tr>
                                            <?php endforeach ; ?>
                                        </tbody>
                                    </table>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="alien_name">Proportion of the qualified</label>
                                            <i class="fas fa-info-circle" title="Proportion of the qualified and unqualified nurses on the nursing staff."></i>
                                            <input type="text" value="<?= $application->proportion_of_qualified ?>" class="form-control" name="proportion_of_qualified">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Any midwife is employed</label>
                                        <i class="fas fa-info-circle" title="Whether any unregistered medical practitioner or unqualified midwife is employed for nursing any patient in the nursing home."></i>
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
                                            <?php foreach ($midwife as $key => $onemidwife) : ?>
                                                <tr id="onemidwifeField">
                                                    <td><input value="<?= $onemidwife->name ?>" class="form-control" type="text" name="midwife_name[]" multiple></td>
                                                    <td><input value="<?= $onemidwife->age ?>" class="form-control" type="number" name="midwife_age[]" multiple></td>
                                                    <td><input value="<?= $onemidwife->qualification ?>" class="form-control" type="text" name="midwife_qualification[]" multiple></td> 
                                                    <td><i class="fas fa-trash midwifeDeleteBtn fa-2x"></i></td>
                                                </tr>
                                            <?php endforeach ; ?>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Whether any person of Alien nationality</label>
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
                                            <?php foreach ($aliens as $key => $onealien) : ?>
                                                <tr id="onealienField">
                                                    <td><input value="<?= $onealien->name ?>" class="form-control" type="text" name="alien_name[]" multiple></td>
                                                    <td><input value="<?= $onealien->age  ?>" class="form-control" type="number" name="alien_age[]" multiple></td>
                                                    <td><input value="<?= $onealien->qualification ?>" class="form-control" type="text" name="alien_qualification[]" multiple></td>
                                                    <td><input value="<?= $onealien->nationality ?>" class="form-control" type="text" name="alien_nationality[]" multiple></td>
                                                    <td><i class="fas fa-trash alienDeleteBtn fa-2x"></i></td>
                                                </tr>
                                            <?php endforeach ; ?>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-12">
                                    <label for="alien_name">Fees Charges:</label>
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
                                            <?php foreach ($feescharges as $key => $onefeescharges) : ?>
                                                <tr id="oneFeesField">
                                                    <td><input value="<?= $onefeescharges->sr_no ?>" class="form-control" type="number" name="sr_number[]" multiple></td>
                                                    <td><input value="<?= $onefeescharges->service ?>" class="form-control" type="text" name="fees_service[]" multiple></td>
                                                    <td><input value="<?= $onefeescharges->charges ?>" class="form-control" type="number" name="charges[]" multiple></td>
                                                    <td><i class="fas fa-trash fessDeleteBtn fa-2x"></i></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="alien_name">Other nursing home or business:</label>
                                        <i class="fas fa-info-circle" title="Whether the applicant is interested in any other nursing home or business"></i>
                                        <input type="text" value="<?= $application->other_business_address ?>" class="form-control" name="other_business_address">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-header mt-5">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="card-title">
                                        <label for="email_id" class="text-info">Attachments</label>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="card-body">
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
                            </div>
                        </div> -->

                        <div class="card-body">
                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" <?= ($application->promise == 1) ? 'checked' : ''; ?> class="form-check-input" name="promise" id="promise">
                                    <label class="form-check-label" for="exampleCheck1">I solemnly declare that the above statements are true to the best of my knowledge and belief.</label>
                                </div>
                            </div>
                        </div>


                        <div class="card-footer">
                            <div class="row center">
                                <div class="col-12">
                                    <input type="hidden" value="<?= $application->application_type ?>" name="application_type">
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