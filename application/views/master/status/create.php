  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <form role="form" class="status-form" id="status-form" method="post">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Status Title<span class="red">*</span></label>
                             <input type="hidden" class="form-control" value="" name="status_id" id="status_id" placeholder="Enter full name">
                            <input type="text" class="form-control" name="status_title" id="status_title" placeholder="Enter status title">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="email_id">Department Name<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="dept_id" name="dept_id" data-live-search="true">
                              <option value="">--- Select Department ---</option>
                              <?php
                                foreach ($department as $key => $dept) {
                                  echo '<option value="'.$dept['dept_id'].'">'.$dept['dept_title'].'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="mobile_no">Role Name<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="role_id" name="role_id" data-live-search="true">
                              <option value="">---Select Role ---</option>
                              <?php
                                foreach ($roles as $key => $val) {
                                  echo '<option value="'.$val['role_id'].'">'.$val['role_title'].'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="row center">
                        <div class="col-12">
                          <a href="<?= base_url()?>status" class="btn btn-lg btn-info white">Cancel</a>
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
<script type="text/javascript">
  var base_url = "<?=base_url()?>";
</script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/status.js"></script>
<!-- page script -->
</body>
</html>
