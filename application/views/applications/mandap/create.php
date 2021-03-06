<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <form role="form" class="mandap-form" id="mandap-form" method="post" enctype="multipart/form-data">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="card-title">
                                        <label for="email_id" class="text-info">Personal Information</label>
                                    </h3>
                                    <a href="<?= base_url('mandap/user_apps_list') ?>" class="btn btn-info float-right btn-sm">Application List</a>
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
                                        <input type="text" class="form-control" name="applicant_name" id="applicant_name" placeholder="Enter applicant name">
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
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Applicant Address<span class="red">*</span></label>
                                        <textarea type="text" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address"></textarea> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title">
                                <label for="email_id" class="text-info">Booking Details</label>
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Ward</label>
                                        <select class="form-control" name="fk_ward_id" id="ward_select">
                                            <option value="">---Select ward---</option>
                                            <?php foreach ($wards as $ward) : ?>
                                                <option value="<?= $ward->ward_id ?>"><?= $ward->ward_title ?></option>
                                            <?php endforeach ; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="reason">Booking Address<span class="red">*</span></label>
                                        <textarea type="text" class="form-control" rows="1" name="booking_address" id="booking_address" placeholder="Enter booking address"></textarea> 
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="reason">Booking Reason<span class="red">*</span></label>
                                        <input type="text" class="form-control" name="reason" id="reason" placeholder="Enter booking reason">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Mandap type</label>
                                        <select class="form-control" name="mandap_type" id="mandap_type">
                                            <option value="">---Select mandap type---</option>
                                            <?php foreach ($allmandaptype as $mandaptype) : ?>
                                                <option value="<?= $mandaptype->id ?>"><?= $mandaptype->type_name ?></option>
                                            <?php endforeach ; ?> 
                                        </select>
                                    </div>
                                </div>


                                <div class="col-4 mandapsizecontainer" style="display: none;">
                                    <div class="form-group">
                                        <label>Mandap Size (sq.ft.)<span class="red">*</span></label>
                                        <input type="number" class="form-control" name="mandap_size" id="mandap_size" placeholder="Enter mandap size">
                                    </div>
                                </div>

                                <div class="col-4 noofgatecontainer" style="display: none;">
                                    <div class="form-group">
                                        <label>Number of gates<span class="red">*</span></label>
                                        <input type="number" class="form-control" name="no_of_gates" id="no_of_gates" placeholder="Enter mandap size">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="booking_date">No of days</label>
                                        <input type="number" class="form-control datepicker" name="no_of_days" id="no_of_days" placeholder="Select a booking date">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="booking_date">Date of booking<span class="red">*</span></label>
                                        <input type="text" autocomplete="off" readonly class="form-control datepicker" name="booking_date" id="booking_date" placeholder="Select a booking date">
                                    </div>
                                </div>
                                <div class="col-4 multydate_event_container" style="display: none;">
                                    <div class="form-group">
                                        <label for="booking_date">To date</label>
                                        <input type="text" readonly autocomplete="off" class="form-control" name="to_date" id="to_date">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Mandap landmark<span class="red">*</span></label>
                                        <input type="text" autocomplete="off" class="form-control" name="mandap_landmark" id="mandap_landmark" placeholder="Enter the mandap landmark">
                                    </div>
                                </div>


                                <?php if ($roleStacClerk->role_id == $this->authorised_user['role_id']) : ?>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="font-italic">Date of police NOC</label>
                                            <input type="text" readonly class="form-control datepicker" name="date_police_of_noc" id="date_police_of_noc" placeholder="Enter date of police noc">
                                        </div>
                                    </div>
                                    <div class="col-4"></div>
                                    <div class="col-4"></div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label class="font-italic">Date of traffic police NOC</label>
                                            <input type="text" readonly class="form-control datepicker" name="date_traffic_of_noc" id="date_traffic_of_noc" placeholder="Enter date traffic of police noc">
                                        </div>
                                    </div>

                                <?php endif ; ?>
                                
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
                                        <label for="id_proof">Id Proof<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="id_proof" id="id_proof" class="custom-file-input">
                                                <label class="custom-file-label" for="id_proof">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="id_proof_name" class="text-info"> Please select a document</label>
                                    </h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="address_proof">Traffic police noc<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="traffic_police_noc" id="traffic_police_noc" class="custom-file-input">
                                                <label class="custom-file-label" for="traffic_police_noc">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label id="traffic_police_noc_name" class="text-info"> Please select a document </label>
                                    </h3>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="police_noc">Police NOC<span class="red">*</span></label>
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input type="file" name="police_noc" id="police_noc" class="custom-file-input">
                                                <label class="custom-file-label" for="police_noc">Choose file</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6" style="">
                                    <h3 class="card-title link-margin">
                                        <label for="" id="police_noc_name"  class="text-info">Please select a document</label>
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
                                    <a href="<?= base_url('mandap/user_apps_list') ?>" class="btn btn-lg btn-info white">Cancel</a>
                                    <button type="submit" id="mandap_app_submit_btn" class="btn btn-lg btn-primary right">Submit</button>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/mandap.js" id = "createMandap" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>
<script type="text/javascript">
    $( document ).ready(() => {
        $('#ward_select,#mandap_type').selectpicker('destroy');
        $("#to_date").datepicker({dateFormat: 'yy-mm-dd'});
        $("#date_police_of_noc").datepicker({dateFormat: 'yy-mm-dd'});
        $("#date_traffic_of_noc").datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>