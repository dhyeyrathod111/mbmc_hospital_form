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
            <h1>Edit User</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit User</li>
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
                  <form role="form" class="register-form" id="register-form" method="post">
                    <div class="card-body">
                      <div class="row">

                        <div class="col-4">
                          <div class="form-group">
                            <label for="user_name">User name<span class="red">*</span></label>
                            <input type="hidden" name="user_id"  id="user_id" value="<?=($user['user_id']) ? $user['user_id'] : ''?>" class="form-control" placeholder="User name">
                            <input type="text" name="user_name"  id="user_name" value="<?=($user['user_name']) ? $user['user_name'] : ''?>" class="form-control" placeholder="User name">
                          </div>
                        </div>  
                        <div class="col-4">
                          <div class="form-group">
                            <label for="email_id">Email Id<span class="red">*</span></label>
                            <input type="email" name="email_id" id="email_id" value="<?=($user['email_id']) ? $user['email_id'] : ''?>" class="form-control" placeholder="Email Id">
                          </div>
                        </div> 

                        <div class="col-4">
                          <div class="form-group">
                            <label for="dept_id">Select Department<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="dept_id" name="dept_id" data-live-search="true">
                              <option value="">---Select Department---</option>
                              <?php foreach ($department as $dept) : ?>
                                <option <?php echo ($user['dept_id'] == $dept['dept_id']) ? 'selected' : '' ; ?> value="<?php echo $dept['dept_id'] ?>">
                                  <?php echo $dept['dept_title'] ?>
                                </option>
                              <?php endforeach ; ?>
                            </select>
                          </div>
                        </div> 

                        <div class="col-4">
                          <div class="form-group">
                            <label for="role_id">Select Role<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="role_id" name="role_id" data-live-search="true">
                              <option value="">---Select Role---</option>
                              <?php foreach ($roles as $key => $value) : ?>
                                <option <?= ($value->role_id == $user['role_id']) ? 'selected' : ''; ?> value="<?= $value->role_id ?>"><?= $value->role_title ?></option>
                              <?php endforeach ; ?>
                            </select>
                          </div>
                        </div> 
                        

                        <div class="col-4">
                          <div class="form-group">
                            <label for="user_mobile" class="text-info">Mobile No<span class="red">*</span></label>
                            <input type="number" name="user_mobile" id="user_mobile" value="<?=($user['user_mobile']) ? $user['user_mobile'] : ''?>" class="form-control" placeholder="Mobile No">
                          </div>
                        </div> 

                        <div class="col-4 word_container">
                          <div class="form-group">
                            <label for="password">Ward<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="word_select" name="ward_id" data-live-search="true" required>
                            <option value=''>---Select word---</option>
                            <?php foreach ($user_ward as $ward) : ?>
                              <option <?= ($ward->ward_id == $user['ward_id']) ? 'selected' : '' ; ?> value='<?php echo $ward->ward_id ?>'><?php echo $ward->ward_title ?></option>
                            <?php endforeach ; ?>
                            </select>
                              <div id="word_error"></div>
                          </div>
                        </div>

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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/custom.js" id = "usersPage" is_type = 'edit'></script>
<!-- page script -->
</body>
</html>
