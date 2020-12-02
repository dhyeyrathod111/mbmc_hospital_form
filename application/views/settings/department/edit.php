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
            <h1>Edit Department</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Department</li>
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

                        <?php //echo "<pre>";print_r($dept);exit(); ?>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Department Title<span class="red">*</span></label>
                            <input type="hidden" class="form-control" value="<?=($dept['dept_id']) ? $dept['dept_id']: '' ?>" name="dept_id" id="dept_id">
                            <input type="text" class="form-control" id="dept_title" value="<?=($dept['dept_title']) ? $dept['dept_title']: '' ?>" name ="dept_title" placeholder="Enter the Department Title" required>
                          </div>
                        </div>
												<div class = "col-4">
													<div class = "form-group">
														<label for="dept_email">Department Mail Id <span class = "red">*</span></label>
														<input type="email" class = "form-control" name = "dept_mail" id = "dept_mail" placeholder = "Please Enter Department Mail Id" value = "<?= $dept['department_mail_id'] ?>" required>
													</div>
												</div>  
                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Department Desc<span class="grey">&nbsp;(optional)</span></label>
                            <input type="text" class="form-control" id="dept_desc" value="<?=($dept['dept_desc']) ? $dept['dept_desc']: '' ?>" name ="dept_desc" placeholder="Enter the Description" required>
                          </div>
                        </div>   
                      </div>
											<div class = "col-12">
												<hr>
												<div class="col-md-12">
													<p class="text-info" style="font-size: 20px; font-weight: bold">Edit Approval Permissions Access</p>
													<div class = "row">
														<div class="col-md-10"></div>
														<div class="col-md-2">
															<a type="button" id="add-role-btn" class="add-btn btn btn-block btn-info">Add</a>        
														</div>
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
														<tbody class = "tableBody">
														<?php 
															if($perData != ''){
																$srNo = 1;
																foreach($perData as $kper => $valPer){
															?>
																	<tr class = "text-center">
																		<td><?= $srNo; ?></td>
																		<td class = "activity">
																			<select class="selectpicker form-control roleDrop" id="role" name="role[]" data-live-search="true" required="" tabindex="-98">
																				<option value = "0">Select Role</option>
																			<?php
																				foreach($roles as $kroles => $vroles){
																					if($vroles['role_id'] == $valPer['role_id']){
																						echo "<option value = '".$vroles['role_id']."' selected>".$vroles['role_title']."</option>";
																					}else{
																						echo "<option value = '".$vroles['role_id']."'>".$vroles['role_title']."</option>";
																					}
																				}
																			?>
																			</select>
																		</td>
																		<td class = "text-center activity">
																		 <div class = "form-check">
																		 		<input type="checkbox" name="check" class = "check" <?= ($valPer['payable_status'] == '1') ? 'checked' : '' ?>>
																		 </div>
																		</td>
																		<td class="text-center action">
																			<span style="font-size: 25px; cursor:pointer" class="deleteRow" data-id="0" data-accessId = '<?= $valPer['access_id']; ?>'><i class="fas fa-trash"></i></span>
                                    </td>
																	</tr>
															<?php		
																	$srNo++;
																}
															}
														?>
														</tbody>
													</table>
												</div>
											</div>
                    </div>
                    <div class="card-footer">
                      <div class="row center">
                        <div class="col-12">
                          <input type="hidden" id="dept_id" value="<?php echo $dept['dept_id'] ?>" name="dept_id">
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/department.js"></script>
<!-- page script -->
</body>
</html>
