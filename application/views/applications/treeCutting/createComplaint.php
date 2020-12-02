  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

	<style>
		.noOfTrees{
			display: none;
		}
	</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        
      </div>
    </section> -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <!-- <div class="card"> -->
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <form role="form"  class="treeCuttingform" name = "treeCuttingform" id="treeCuttingform" method="post" enctype="multipart/form-data" >
                    <div class="card-body">
                      <div class="row">
                        <div class = "col-12 text-center">
                          <h5 class="text-info text-danger font-weight-bold">Note : For tree cutting and tree transplantation select permission type as other.</h5>
                        </div>
                        <div class = "col-md-12">
                          <p class = 'text-info' style = "font-size: 20px; font-weight: bold">Personal Information</p>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="form_no"><span>Form No</span><span class="red">*</span></label>

                            <?php
                              if(!empty($appId)) {
                                  $app_val = $appId['application_id'];
                                  $app_val++; 
                                  $app_no = 'MBMC-00000'.$app_val;
                                  
                              } else {
                                $app_no = 'MBMC-000001';
                              }
                            // $app_no = 'MBMC-000001';
                           ?>
                            <input type="text" class="form-control" value="<?=$app_no; ?>" name="form_no" id="form_no" placeholder="Enter Form No" readonly>
                          </div>
                        </div>
                        
                        
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="applicant_name">Applicant Name<span class="red">*</span></label>
                            <input type="text" class="form-control" name="applicant_name" id="applicant_name" placeholder="Enter Applicant Name" required="">
                          </div>
                        </div>
                        <br>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="mobile_no">Applicant Mobile No<span class="red">*</span></label>
                            <input type="number" min="0" class="form-control" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter Mobile No" required="">
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label  for="applicant_email">Applicant Email Id<span class="red"> *</span></label>
                            <input type="email" class="form-control" name="applicant_email" id="applicant_email" placeholder="Enter Applicant Email" required="">
                            <small>Documents notifications will be sent on this email ID.</small>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class = "row">
                        <div class = "col-md-12">
                          <p class = 'text-info' style = "font-size: 20px; font-weight: bold">Survey Details</p>
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="applicant_address">Applicant Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address" required=""></textarea> 
                          </div>
                        </div>  
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="survey_no">Survey No</label>
                            <input type="text" class="form-control" name="survey_no" id="survey_no" placeholder="Enter Survey No">
                          </div>
                        </div>
                        
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="city_survey_no">City Survey No</label>
                            <input type="text" class="form-control" name="city_survey_no" id="city_survey_no" placeholder="Enter City Survey No.">
                          </div>
                        </div>
                        
                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="ward_no">Ward No<span class="red">*</span></label>
                            <input type="text" class="form-control" name="ward_no" id="ward_no" placeholder="Enter Ward No" required="">
                          </div>
                        </div>

                        <div class="col-md-4">
                          <div class="form-group">
                            <label for="plot_no">Plot No</label>
                            <input type="text" class="form-control" name="plot_no" id="plot_no" placeholder="Enter Plot No" >
                          </div>
                        </div>

                        <!-- <div class="col-md-4">
                          <div class="form-group">
                            <label for="no_of_trees">No Of Trees In Premises<span class="red">*</span></label>
                            <input type="number" min = "0" name="no_of_trees" class="form-control" id="no_of_trees" placeholder="Enter No Of Trees In Premises" required="">
                          </div>
                        </div> -->

                        <div class = "col-md-4">
                              <div class = "form-group">
                                <label for="ownership_details">Ownership Of Premises / Property Tax Receipt <span class = "red">(Only PDF allowed)</span> <span class="red">*</span></label>
                                <input type="file" name="ownership_file" id="ownership_file" placeholder = "Please Upload Ownership Of Premises / Property Tax Receipt" class = "form-control" required = "">
                              </div>
                        </div>

												<!-- <div class = "col-md-4">
														<div class = 'form-group'>
																<label for="letter">Letter<span class = "red">*</span></label>
																<input type="file" name = "letter" id = 'letter' class = "form-control" required>
														</div>
												</div> -->
                        
                        <div class = "col-md-12">
                          <hr>
                          <div class = "col-md-12">
                            <p class = 'text-info' style = "font-size: 20px; font-weight: bold">Service Information</p>
                          </div>
                          <div class = "row">
                            <div class = "col-md-10">
                              <div class="col-md-4">
                          <div class="form-group">
                            <input type="hidden" name = "blueprint_status" id = "blueprint_status">
                            <label for="applicant_date">Types of Permission<span class="red">*</span></label>
                            <select name = "perType" id = "perType" class='selectpicker form-control'data-live-search='true' required="">
                              <option value = "0">Select Permission Type</option>
                              <?php 
                                $otherPer = array('1','2');
                                foreach($permission_type as $key_per => $val_per){
                                  if(!in_array($val_per['garper_id'], $otherPer)){
                                    echo "<option value = '".$val_per['garper_id']."' data-blueprint = '".$val_per['is_blueprint']."'>".$val_per['permission_type']."</option>";
                                  }
                                }
                              ?>
                            </select>
                            <div id = "per_type_error"></div>
                          </div>
                        </div>
                            </div>
                            <div class = "col-md-2">
                              <button type="button" class = "form-control btn btn-md btn-info addRow">Add</button>
                            </div>
                            <!-- <div class = "col-2">
                              <button type="button" class = "form-control btn btn-md btn-danger">Delete</button>
                            </div> -->
                          </div>
                          <br>
                          <table class = "table table-responsive-sm">
                              <thead>
                                <tr class = "text-center">
																	<th>Tree No.</th>
                                  <th>Tree Name</th>
                                  <th class = "activity">Tree Activity</th>
																	<th class = "condition">Condition</th>
                                  <th class = "noOfTrees">No of Trees</th>
                                  <th>Reason For Permission</th>
                                  <th>Tree Photo</th>
																	<?php 
																		if($is_visitor == '1'){
																			echo "<th>Refund</th>";
																		}
																	?>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody class = "tableBody">
                                  <tr>
																		<td class = "text-center treeNo" style = "padding-top:20px">
																			<input type = 'hidden' name = "tree_no[]" value = "<?= $tree_number[0]['random']; ?>">
																			<?= $tree_number[0]['random']; ?>
																		</td>
                                    <td class = 'text-center name'>
                                      <select class='selectpicker form-control treename' id='treeName' name='treeName[]' data-live-search='true' required="">
                                        <option value="0">Select Tree</option>
                                      <?php
                                        foreach($treeData as $key => $valueTree){
                                          echo "<option value = '".$valueTree['tree_id']."'>".$valueTree['treeName']."</option>";
                                        }
                                      ?>
                                      </select>
                                    </td>
                                    <td class = 'text-center activity'>
                                      <select class='selectpicker form-control processName' id='processName' name='processName[]' data-live-search='true' required="">
                                        <option value="0">Select Activity</option>
                                        option
                                        <?php
                                          // foreach ($processData as $keyProcess => $valueProcess) {
                                          //   echo "<option value = '".$valueProcess['processId']."'>".$valueProcess['processName']."</option>";
																					// }
																					foreach($permission_type as $key_per => $val_per){
																						if(in_array($val_per['garper_id'], $otherPer)){
																							echo "<option value = '".$val_per['garper_id']."' data-blueprint = '".$val_per['is_blueprint']."'>".$val_per['permission_type']."</option>";
																						}
																					}
                                        ?>
                                      </select>
                                    </td>
																		<td class = "text-center condition">
																					<select class = "selectpicker form-control condition" name = "condition[]" data-live-search = "true" required>
																						<option value="0">Select Condition</option>
																						<option value="1">Hazardous</option>
																						<option value="2">Non Hazardous</option>
																					</select>
																		</td>
                                    <td class = 'text-center noOfTrees'>
                                      <input type = 'number' min = "0" name = 'total_trees[]' class = "form-control total_trees" id = 'total_trees' placeholder='Action On No Of Trees' required="">
                                    </td>
                                    <td class = 'text-center permission'>
                                      <input type='text' class='form-control' name='reason_for_permission[]' id='reason_for_permission' placeholder='Enter Reason' required="">
                                    </td>
                                    <td class = 'text-center photo'>
                                      <input id='file-upload' name='files[]' class = 'form-control uploadFiles' type='file' required="">
                                      <span class = 'file_name' ></span>
                                    </td>
																		<?php
																		 if($is_visitor == '1'){
																			echo "<td class = 'text-center refundSection'>
																					<input type = 'checkbox' name = 'refund' required>
																			</td>";
																		 }
																		?>
                                    <td class = 'text-center action'><span style = 'font-size: 25px; cursor:pointer' class = 'delete' data-id = '0'><i class='fas fa-trash'></i></span>
                                    </td>
                                  </tr>
                              </tbody>
                            </table>  
                        </div>  

                        <div class="col-md-4" style = "margin-bottom: 40px">
                          <div class="form-check">
                            <input type="checkbox" name = "declaration" id="declaration"/>
                            <label for="declaration"> Declaration To Garden Superitendent</label>
                          </div>
                        </div>

                        <div class="col-md-4 blueprint">
                          <div class="form-group">
                            <label for="blueprint">Upload Blueprint<span class = "red">*</span></label>
                            <input type="file" class="form-control" name="blueprint" id="blueprint" required>
                          </div>
                        </div>

                        
                      </div>
                      <!-- ENd Row -->
                    </div> 
                    <!-- End Card Body -->
                    <div class="card-footer">
                      <center>
                        <button type="button" class="btn btn-info cancel">Cancel</button>
                        &nbsp
                        <button type="submit" class="btn btn-primary right submit">Submit</button>
                      </center>
                    </div>
                  </form>
                </div>  
              </div>
            </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->


    <!-- terms modal -->
<div class="modal terms-modal" id="myModal" >
  <div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Terms & Conditions</h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<!-- <form class="remarks-form" id="remarks-form" role="form" method="POST"> -->
						<div class="modal-body">
								<div class="card card-primary" style = "height:70vh">
										<div class="card-body" style = "overflow:scroll;">
											<div class="modal-terms-content">
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
												<br>
												<p><strong>Information Modal</strong><br>
												Information Modal is a text oriented modal used majorly to provide more information on any topic like profile information of any user.</p>
											</div>
										</div>
								</div>
						</div>
						<div class="modal-footer justify-content-end">
							<!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> -->
							<button  class="btn btn-danger btn-md reject">I Reject</button>
							<button  class="btn btn-info btn-md agree">I Agree</button>
						</div>
					<!-- </form> -->
				</div>
				<!-- /.modal-content -->
			</div>             
  </div>
</div>  
  <!-- /.content-wrapper -->
   <?php $this->load->view('includes/footer');?>

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- DataTables -->

<!-- terms modal script -->
    <!-- <script>
        $(document).ready(function () {
            $('input[type="checkbox"]').on('change', function (e) {
                if (e.target.checked) {
                    $('#myModal').modal();
                }
            });
        })
    </script> -->
<script>
	var is_visitor = "<?= $is_visitor; ?>";
</script>

<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/createComplain.js" id = "createComplain" is_visitor = "<?= $is_visitor; ?>" is_user = "<?= $this->authorised_user['is_user']; ?>" is_type = 'add'></script>
  
</script>

<!-- page script -->
</body>
</html>
