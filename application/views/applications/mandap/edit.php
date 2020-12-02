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
      </div><!-- /.container-fluid - - >
    </section> -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- <div class="card-header">
              <h3 class="card-title">Application Details</h3>
            </div> -->

            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <!-- form start -->
                  <form role="form" class="mandap-form" id="mandap-form" method="post" enctype="multipart/form-data">
                    <div class="card-header">
                     <!--  <div class="row">
                        <div class="col-10">
                        </div>
                        <div class="col-2">
                          <a href="<?= base_url()?>hall" class="btn btn-lg btn-info white">Cancel</a>
                          <button type="submit" class="btn btn-lg btn-primary right">Submit</button>
                        </div>
                      </div> -->
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

                            <input type="hidden" value="<?=($users['app_id'] != null) ? $users['app_id'] :'1' ?>" name="app_id" id="app_id">
                            <input type="hidden" value="<?=($users['id'] != null) ? $users['id'] :'1' ?>" name="id" id="id">
                            <?php
                              if($users['app_id'] != null) {
                                  $app_val = 'MBMC-00000'.$users['app_id'];
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
                            <input type="text" class="form-control" value="<?=($users['applicant_name'] != null) ? $users['applicant_name'] :'' ?>" name="applicant_name" id="applicant_name" placeholder="Enter full name">
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
                            <input type="text" class="form-control" value="<?=($users['applicant_mobile_no'] != null) ? $users['applicant_mobile_no'] :'' ?>" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter mobile no">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label  for="alert_mobile_no">Alternate Mobile no<span class="grey"> (optional)</span></label>
                            <input type="text" class="form-control" value="<?=($users['applicant_alternate_no'] != null) ? $users['applicant_alternate_no'] :'' ?>" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile no">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="alert_mobile_no">Applicant Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control" name="applicant_address" id="applicant_address" value="" placeholder="Enter Address">
                              <?=($users['applicant_address'] != null) ? $users['applicant_address'] :'' ?>
                                
                              </textarea> 
                          </div>
                        </div>
                      </div>
                    </div>  

                    <!-- company info -->
                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Booking Details</label>
                      </h3>
                    </div>
                    <?php 
                      // echo'<pre>';print_r($users);//exit;
                      // echo'<pre>';print_r($sku_price);exit;
                    ?>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="reason">Booking Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control" value="<?=($users['booking_address'] != null) ? $users['booking_address'] :'' ?>" name="booking_address" id="booking_address" placeholder="Enter booking address"><?=($users['booking_address'] != null) ? $users['booking_address'] :'' ?></textarea> 
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="booking_date">Date of booking<span class="red">*</span></label>
                            <input type="text" class="form-control" value="<?=($users['booking_date'] != null) ? $users['booking_date'] :'' ?>" name="booking_date" id="booking_date" placeholder="Select a booking date">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="exampleCheck1">Booking Reason<span class="red">*</span></label>
                            <input type="text" class="form-control" value="<?=($users['reason'] != null) ? $users['reason'] :'' ?>"  name="reason" id="reason" placeholder="Enter landline no">
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
                            <label for="request_letter">Request Letter<span class="red">*</span></label>
                              <div class="form-group">
                                <div class="custom-file">
                                 <input type="file" name="request_letter" id="request_letter" value="<?=($users['request_letter'] != null) ? $users['request_letter'] :'' ?>"  class="custom-file-input">
                                 <input type="hidden" name="request_letter_name" id="request_letter_name_id" value="<?= $users['request_letter_name']?>" class="custom-file-input">
                                  <label class="custom-file-label" for="request_letter">Choose file</label>
                                </div>
                              </div>
                            </div>
                        </div> 
                        <div class="col-6">
                          <h3 class="card-title link-margin">
                            <label for="" id="request_letter_name" class="text-info"> <a href="<?=($users['request_letter'] != null) ? $users['request_letter'] :'' ?>"><?= $users['request_letter_name']?> </a>
                            </label>
                          </h3>
                        </div>
                      </div>
                      <div class="row">   
                        <div class="col-6">
                          <div class="form-group">
                            <label for="request_letter">Id Proof<span class="red">*</span></label>
                              <div class="form-group">
                                <div class="custom-file">
                                 <input type="file" name="id_proof" id="id_proof" value="<?=($users['id_proof'] != null) ? $users['id_proof'] :'' ?>"  class="custom-file-input">

                                 <input type="hidden" name="id_proof_name" id="id_proof_name_id" value="<?= $users['id_proof_name']?>" class="custom-file-input">

                                  <label class="custom-file-label" for="id_proof_name">Choose file</label>
                                </div>
                              </div>
                            </div>
                        </div> 
                        <div class="col-6">
                          <h3 class="card-title link-margin">
                            <label for="" id="id_proof_name" class="text-info"> <a href="<?=($users['id_proof'] != null) ? $users['id_proof'] :'' ?>"><?= $users['id_proof_name']?> </a>
                            </label>
                          </h3>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="police_noc">police NOC<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="police_noc" id="police_noc" value="" class="custom-file-input">

                                 <input type="hidden" name="police_noc_name" id="police_noc_name_id" value="<?= $users['police_noc_name']?>" class="custom-file-input">

                                <label class="custom-file-label" for="police_noc">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="police_noc_name"  class="text-info"><a href="<?=($users['police_noc'] != null) ? $users['police_noc'] :'' ?>"><?= $users['police_noc_name']?></a>
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
                       <div class="row center">
                         <div class="col-10">
                            <a href="<?= base_url()?>hall" class="btn btn-lg btn-info white">Cancel</a>
                            <button type="submit" class="btn btn-lg btn-primary right">Submit</button>
                        </div>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/mandap.js"></script>
  
</script>
<!-- page script -->
</body>
</html>
