  <?php $this->load->view('includes/header'); ?>
  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <form role="form" class="company_details_form" id="company_details_form" method="post">
                    <div class="card-body">
                      <div class="row">
                        
												<div class="col-4">
                          <div class="form-group">
                            <label for="company_name">Company Name<span class = "red">*</span></label>
                              <input type = "hidden" class = "form-control" value = "" name="company_id" id="company_id">
                             <input type = "text" class = "form-control" id = "company_name" name = "company_name" placeholder="Enter Name Of Company Name" required>
                          </div>
                        </div>

												<div class="col-4">
                          <div class="form-group">
                            <label for="company_address">Company Address<span class = "red">*</span></label>
                             <textarea name = "company_address" id = "company_address" class = "form-control" placeholder = "Enter Company Address" required></textarea>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="row center">
                        <div class="col-12">
                          <a href = "<?= base_url()?>company_details/" class = "btn btn-lg btn-info white">Cancel</a>
                          <button type = "submit" class = "btn btn-lg btn-primary right">Submit</button>
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
<script type="text/javascript">
  var base_url = "<?= base_url(); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/company_details.js"></script>


<!-- page script -->
</body>
</html>
