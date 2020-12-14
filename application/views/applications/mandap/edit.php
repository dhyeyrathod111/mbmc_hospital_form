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
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="application_no"><span>Application No</span><span class="red">*</span></label>
                                        <input type="hidden" value="<?= $application->id ?>" name="id" id="id">
                                        <input type="text" class="form-control" value="MBMC-00000<?= $application->app_id ?>"  placeholder="Enter Application no" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="applicant_name">Applicant Name<span class="red">*</span></label>
                                        <input type="text" class="form-control" value="<?= $application->applicant_name ?>" name="applicant_name" id="applicant_name" placeholder="Enter applicant name">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email_id">Applicant Email Id<span class="red">*</span></label>
                                        <input type="text" class="form-control" value="<?= $application->applicant_email_id ?>" name="applicant_email_id" id="applicant_email_id" placeholder="Enter email Id">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="mobile_no">Applicant Mobile no<span class="red">*</span></label>
                                        <input type="text" class="form-control" value="<?= $application->applicant_mobile_no ?>" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter mobile no">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label  for="alert_mobile_no">Alternate Mobile no<span class="grey"> (optional)</span></label>
                                        <input type="text" class="form-control" value="<?= $application->applicant_alternate_no ?>" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile no">
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="alert_mobile_no">Applicant Address<span class="red">*</span></label>
                                        <textarea type="text" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address"><?= $application->applicant_address ?></textarea> 
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title">
                                <label class="text-info">Booking Details</label>
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
                                                <option <?= ($ward->ward_id == $application->fk_ward_id) ? 'selected' : '' ; ?>  value="<?= $ward->ward_id ?>"><?= $ward->ward_title ?></option>
                                            <?php endforeach ; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="reason">Booking Address<span class="red">*</span></label>
                                        <textarea type="text" class="form-control" rows="1" name="booking_address" id="booking_address" placeholder="Enter booking address"><?= $application->booking_address ?></textarea> 
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="reason">Booking Reason<span class="red">*</span></label>
                                        <input type="text" value="<?= $application->reason ?>" class="form-control" name="reason" id="reason" placeholder="Enter booking reason">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label>Mandap type</label>
                                        <select class="form-control" name="mandap_type" id="mandap_type">
                                            <option value="">---Select mandap type---</option>
                                            <?php foreach ($allmandaptype as $mandaptype) : ?>
                                                <option <?= ($mandaptype->id == $application->type) ?  'selected' : '' ; ?> value="<?= $mandaptype->id ?>"><?= $mandaptype->type_name ?></option>
                                            <?php endforeach ; ?> 
                                        </select>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="mandap_size">Mandap Size<span class="red">*</span></label>
                                        <input type="text" value="<?= $application->mandap_size ?>" class="form-control" name="mandap_size" id="mandap_size" placeholder="Enter mandap size">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="booking_date">No of days</label>
                                        <input type="number" class="form-control datepicker" value="<?= $application->no_of_days ?>" name="no_of_days" id="no_of_days" placeholder="Select a booking date">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="booking_date">Date of booking<span class="red">*</span></label>
                                        <input type="text" autocomplete="off" value="<?= $application->booking_date ?>" readonly class="form-control datepicker" name="booking_date" id="booking_date" placeholder="Select a booking date">
                                    </div>
                                </div>
                                <div class="col-4 multydate_event_container" style="display: none;">
                                    <div class="form-group">
                                        <label for="booking_date">To date</label>
                                        <input type="text" readonly autocomplete="off" value="<?= $application->to_date ?>" class="form-control" name="to_date" id="to_date">
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

                                    <?php if (!empty($appimages->id_proof)) : ?>
                                        <h3 class="card-title link-margin">
                                            <label id="id_proof_name" class="text-info">
                                                <a target="_blank" href="<?= base_url('uploads/mandap/'.$appimages->id_proof->image_enc_name) ?>"><?= $appimages->id_proof->image_name ?></a>
                                            </label>
                                        </h3>
                                    <?php else : ?>
                                        <h3 class="card-title link-margin">
                                            <label id="id_proof_name" class="text-info"> Please select a document </label>
                                        </h3>
                                    <?php endif ; ?>
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
                                    <?php if (!empty($appimages->traffic_police_noc)) : ?>
                                        <h3 class="card-title link-margin">
                                            <label id="traffic_police_noc_name" class="text-info">
                                                <a target="_blank" href="<?= base_url('uploads/mandap/'.$appimages->traffic_police_noc->image_enc_name) ?>"><?= $appimages->traffic_police_noc->image_name ?></a>
                                            </label>
                                        </h3>
                                    <?php else : ?>
                                        <h3 class="card-title link-margin">
                                            <label id="traffic_police_noc_name" class="text-info"> Please select a document </label>
                                        </h3>
                                    <?php endif ; ?>
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
                                    <?php if (!empty($appimages->police_noc)) : ?>
                                        <h3 class="card-title link-margin">
                                            <label id="police_noc_name" class="text-info">
                                                <a target="_blank" href="<?= base_url('uploads/mandap/'.$appimages->police_noc->image_enc_name) ?>"><?= $appimages->police_noc->image_name ?></a>
                                            </label>
                                        </h3>
                                    <?php else : ?>
                                        <h3 class="card-title link-margin">
                                            <label id="police_noc_name" class="text-info"> Please select a document </label>
                                        </h3>
                                    <?php endif ; ?>
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
        $('#no_of_days').change();
    });
</script>