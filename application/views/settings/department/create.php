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
            <h1>Add Department</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Department</li>
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
                  <form role="form" class="dept-form" id="dept-form" method="post">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Department Title<span class="red">*</span></label>
                            <input type="hidden" class="form-control" value="" name="dept_id" id="dept_id">
                            <input type="text" class="form-control" id="dept_title" name ="dept_title" placeholder="Enter the Department Title">
                          </div>
                        </div>
												<div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Department Mail Id<span class="red">*</span></label>
                            <input type="email" class="form-control" id="dept_email" name ="dept_email" placeholder="Enter the Department Mail Id">
                          </div>
                        </div>  
                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Department Desc<span class="grey">&nbsp;(optional)</span></label>
                            <input type="text" class="form-control" id="dept_desc" name ="dept_desc" placeholder="Enter the Description">
                          </div>
                        </div>                       
                      </div>
                      <div class="col-md-12">
                          <hr>
                          <div class="col-md-12">
                            <p class="text-info" style="font-size: 20px; font-weight: bold">Approval Permissions Access</p>
                          </div>
                          <div class="row">
                            <div class="col-md-10">
                              
                            </div>
                            <div class="col-md-2">
															<a type="button" id="add-role-btn" class="add-btn btn btn-block btn-info">Add</a>        
														</div>
                            <!-- <div class = "col-2">
                              <button type="button" class = "form-control btn btn-md btn-danger">Delete</button>
                            </div> -->
                          </div>
                          <br>
                          <table class="table table-responsive-sm">
                              <thead>
                                <tr class="text-center">
																	<th>Sr No</th>
                                  <th>Role</th>
                                  <th>Is payable</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody class="tableBody">
                                  <tr>
																		<td class="text-center activity">
																		1
																		</td>
                                    <td class="text-center activity">
                                      <div class="dropdown bootstrap-select form-control">
																				<select class="selectpicker form-control roleDrop" id="role" name="role[]" data-live-search="true" required="" tabindex="-98">
                                        	<option value="0">Select Role</option>
																					<?php
																						foreach($roles as $kroles => $vroles){
																							echo "<option value = '".$vroles['role_id']."'>".$vroles['role_title']."</option>";
																						}
																					?>
																				</select></div>
                                    </td>
                                    <td class="text-center activity">
                                     <div class="form-check">
																			 <label>
																				<input type="checkbox" name="check" class = "check">
																			 </label>
        														  </div>
                                    </td>
                                    <td class="text-center action">
																			<span style="font-size: 25px; cursor:pointer" class="delete" data-id="0"><i class="fas fa-trash"></i></span>
                                    </td>
                                  </tr>
                              </tbody>
                            </table>  
                        </div>
                    </div>
                    <div class="card-footer">
                      <div class="row center">
                        <div class="col-12">
                          <a href="<?= base_url()?>departments" class="btn btn-lg btn-info white">Cancel</a>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/department.js"></script>


<!-- page script -->
</body>
</html>
