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
            <h1>Add User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add User</li>
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
              
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <div id="alert_message"></div>
                  <form role="form" class="register-form" id="register-form" method="post">
                    
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Username <span class="red">*</span></label>
                            <input type="text" name="user_name"  id="user_name" class="form-control" placeholder="User name">
                            <input type="hidden" class="form-control" value="" name="user_id" id="user_id">
                          </div>
                        </div>  
                        
												<div class="col-4">
                          <div class="form-group">
                            <label for="email_id">Email Id<span class="red">*</span></label>
                            <input type="email" name="email_id" id="email_id" class="form-control" placeholder="Email Id">
                          </div>
                        </div>

												<!-- Department Dropdown -->
												<div class="col-4">
                          <div class="form-group">
                            <label for="dept_id">Select Department<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="dept_id" name="dept_id" data-live-search="true" required>
                              <option value=''>---Select Department---</option>
                              <?php
                              // echo'<pre>';print_r($roles);exit;
                                foreach ($department as $key => $dept) {
                                  // echo'<pre>';print_r($val['role_id']);exit;
                                  echo '<option value="'.$dept['dept_id'].'">'.$dept['dept_title'].'</option>';
                                }
                              ?>
                            </select>
                            <div id="dept_error"></div>
                          </div>
                        </div>
												<!-- End Department Dropdown -->

												<!-- Role Section -->
                        <div class="col-4">
                          <div class="form-group">
                            <label for="role_id">Select Role<span class="red">*</span></label>
                              <select class="selectpicker form-control" id="role_id" name="role_id" data-live-search="true" required>
                                <option value=''>---Select Role---</option>
                              </select>
                              <div id="role_error"></div>
                          </div>
                        </div> 
												<!-- End Role Section -->

                        <div class="col-4">
                          <div class="form-group">
                            <label for="user_mobile">Mobile No<span class="red">*</span></label>
                            <input type="number" name="user_mobile" id="user_mobile" class="form-control" placeholder="Mobile No">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="password">Password<span class="red">*</span></label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                          </div>
                        </div>

                        <div class="col-3"></div>
                        <div class="col-6 word_container">
                          <div class="form-group">
                            <label for="password">Word<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="word_select" name="word_id" data-live-search="true" required>
                              <option value=''>---Select word---</option>
                            </select>
                              <div id="word_error"></div>
                          </div>
                        </div>

												<!-- is_visitor -->
												<div class = "col-4 is_visitor_section" style = "display: none;">
													<div class="form-check" style = "font-size: 17px">
														<label class="form-check-label">
															<input type="checkbox" class="form-check-input" name = "is_visitor" style = "height: 17px; width: 17px" value = '1'><b>Site Visit</b>
														</label>
													</div>
												</div>
												<!-- End is_visitor -->

                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="row center">
                        <div class="col-12">
                          <a href="<?= base_url()?>users" class="btn btn-lg btn-info white">Cancel</a>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/custom.js" id = "usersPage" is_type = "add"></script>


<!-- page script -->
</body>
</html>
