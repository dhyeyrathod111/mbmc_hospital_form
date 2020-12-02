  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <style type="text/css">
    
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Mandap Permission Application</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Mandap Permission Application</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid - ->
    </section> -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="modal fade" id="modal-calendar">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Bookng date</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <!-- <div id="calendar"></div> -->
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
            <!-- /.card-header -->
            <div class="row ">
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
                        <div class="col-4">
                          <div class="form-group">
                            <label for="alert_mobile_no">Applicant Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address"></textarea> 
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Booking info -->
                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Booking Details</label>
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="reason">Booking Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control" name="booking_address" id="booking_address" placeholder="Enter booking address"></textarea> 
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
                            <label for="booking_date">Date of booking<span class="red">*</span></label>
                            <input type="text" class="form-control datepicker" name="booking_date" id="booking_date" placeholder="Select a booking date">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="mandap_size">Mandap Size<span class="red">*</span></label>
                            <input type="text" class="form-control" name="mandap_size" id="mandap_size" placeholder="Enter mandap size">
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
                            <label for="address_proof">request letter<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="request_letter" id="request_letter" class="custom-file-input">
                                <label class="custom-file-label" for="request_letter">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="request_letter_name"  class="text-info"> Please select a document </label>
                            <input type="hidden" name="request_letter_name" id="request_letter_name_id">
                          </h3>
                        </div>
                      </div>

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
                            <input type="hidden" name="id_proof_name" id="id_proof_name_id">
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
                            <label for="" id="police_noc_name"  class="text-info"> 
                              Please select a document 
                            </label>
                            <input type="hidden" name="police_noc_name" id="police_noc_name_id">
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
                            <a href="<?= base_url()?>mandap" class="btn btn-lg btn-info white">Cancel</a>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/mandap.js" id = "createMandap" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>
<script type="text/javascript">
</script>

<!-- page script -->
</body>
</html>
