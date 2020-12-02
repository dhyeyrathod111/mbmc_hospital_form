  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
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
          <div class="card">
            <div class="card-header">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Edit Application</h1>
                </div>
                <!-- <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Add</li>
                  </ol>
                </div> -->
              </div>
              <!-- <h3 class="card-title">Edit</h3> -->
            </div>

            <div class="row alertdiv">
              <div class="col-12">
                <div class="card-body">
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!-- <h5><i class="icon fas fa-ban"></i> Alert!</h5> -->
                    <p id="alert-danger"></p>
                    
                  </div>
                  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!-- <h5><i class="icon fas fa-check"></i> Alert!</h5> -->
                    <p id="alert-success"></p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form"  class="treeCuttingformEdit" name = "treeCuttingformEdit" id="treeCuttingformEdit" method="post" enctype="multipart/form-data" >
                    <div class="card-body">
                      <div class="row">
                        <div class = "col-12 text-center">
                          <h5 class="text-info text-danger font-weight-bold">Note : For tree cutting and tree transplantation select permission type as other.</h5>
                        </div>
                        <div class = "col-12">
                          <p class = "text-info" style = "font-size: 20px; font-weight: bold">Personal Information</p>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <input type="hidden" name="complainId" value = "<?= $complainData['cutAppId']; ?>">
                            <label for="form_no"><span>Form No</span><span class="red">*</span></label>

                            <?php
                              // if($app_id != null) {
                              //     $app_val = 'MBMC-00000'.$app_id;
                              //     $app_no = ++$app_val;
                              // } else {
                              //   $app_no = 'MBMC-000001';
                              // }
                            // print_r($complainData);exit;
                            $app_no = $complainData['formNo'];
                           ?>
                            <input type="text" class="form-control" value="<?=$app_no; ?>" name="form_no" id="form_no" placeholder="Enter Form No" readonly>
                          </div>
                        </div>

                        <?php // echo "<pre>";print_r($complainData); ?>
                        

                        <!-- <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_date">Application Date<span class="red">*</span></label>
                            <input type="text" class="form-control datepicker" name="application_date" id="application_date" placeholder="Please Select Date" value = "<?= $complainData['applicantDate']; ?>" required="">
                          </div>
                        </div> -->

                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Applicant Name<span class="red">*</span></label>
                            <input type="text" class="form-control" name="applicant_name" id="applicant_name" placeholder="Enter Applicant Name" value = "<?= $complainData['applicantName']; ?>" required="">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="mobile_no">Applicant Mobile No<span class="red">*</span></label>
                            <input type="number" min="0" class="form-control" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter Mobile No" value = "<?= $complainData['mobile']; ?>" required="">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label  for="applicant_email">Applicant Email Id<span class="red"> *</span></label>
                            <input type="email" class="form-control" name="applicant_email" id="applicant_email" placeholder="Enter Applicant Email" value = "<?= $complainData['email']; ?>" required="">
                          </div>
                        </div>
                       </div>
                        <hr>
                       <div class = "row">
                        <div class = "col-12">
                          <p class = "text-info" style = "font-size: 20px; font-weight: bold">Survey Details</p>
                        </div>
												<div class="col-4">
                          <div class="form-group">
                            <label for="applicant_address">Applicant Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control" name="applicant_address" id="applicant_address" placeholder="Enter Address" required=""><?= $complainData['address']; ?></textarea> 
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="survey_no">Survey No</label>
                            <input type="text" class="form-control" name="survey_no" id="survey_no" placeholder="Enter Survey No" value = "<?= $complainData['surveyNo']; ?>">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="city_survey_no">City Survey No</label>
                            <input type="text" class="form-control" name="city_survey_no" id="city_survey_no" placeholder="Enter City Survey No." value = "<?= $complainData['citySurveyNo']; ?>">
                          </div>
                        </div>
                        
                        <div class="col-4">
                          <div class="form-group">
                            <label for="ward_no">Ward No<span class="red">*</span></label>
                            <input type="text" class="form-control" name="ward_no" id="ward_no" placeholder="Enter Ward No" value = "<?= $complainData['wardNo']; ?>" required="">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="plot_no">Plot No</label>
                            <input type="text" class="form-control" name="plot_no" id="plot_no" placeholder="Enter Plot No" value = "<?= $complainData['plotNo']; ?>">
                          </div>
                        </div>
                        <!-- <div class="col-4">
                          <div class="form-group">
                            <label for="no_of_trees">No Of Trees In Premises<span class="red">*</span></label>
                            <input type="number" min = "0" name="no_of_trees" class="form-control" id="no_of_trees" placeholder="Enter No Of Trees In Premises" value = "<?= $complainData['noOfTrees']; ?>" required="">
                          </div>
                        </div> -->
						<div class = "col-md-4">
							<div class = "form-group">
								<label for="ownership">Ownership Of Premises / Property Tax Receipt <?= ($complainData['ownership_property_pdf'] != '') ? '' : '<span class = "red">*</span>'; ?> ( <a aria-label = "Document Uploaded" href = "<?= base_url().'uploads/gardenImages/ownership/'.$complainData['ownership_property_pdf'] ?>" data-microtip-position='top' role='tooltip' download><i class = 'fa fa-download' aria-hidden='true'></i></a> )</label>
								<input type="file" name = "ownership_file" id = "ownership_file" class = "form-control">
							</div>
						</div>
                       </div>

                        <div class = "col-12">
                          <hr>
                          <div class = "col-12">
                            <p class = "text-info" style = "font-size: 20px; font-weight: bold">Service Information</p>
                          </div>
                          <div class = "row">
                            <div class = "col-10">
                              <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_date">Types of Permission<span class="red">*</span></label>
                            <select name="perType" id="perType" class='selectpicker form-control'data-live-search='true' required="">
                                <option value="0">Select Permission Types</option>
                  
                                <?php foreach ($permissionTypes as $key => $vPermission) : ?>
                                    <?php if($key != 1 &&  $key != 0) : ?>
                                        <option <?php echo ($vPermission['garper_id'] == $complainData['permission_type']) ? 'Selected' : '' ?> value="<?php echo $vPermission['garper_id'] ?>"><?php echo $vPermission['permission_type'] ?></option>
                                    <?php endif ; ?>
                                <?php endforeach ; ?>

                                <input type="hidden" id="permission_type_data" value="<?php echo $complainData['permission_type'] ?>" name="">

                            </select>
                          </div>
                        </div>
                            </div>
                            <!--<div class = "col-2">-->
                              <!--<button type="button" class = "form-control btn btn-md btn-info addRow">Add</button>-->
                              <?php if (!empty($process_status)) : ?>
                                <div class = "col-2">
                                  <button type="button" class = "form-control btn btn-md btn-info addRow">Add</button>
                                </div>
                              <?php endif ; ?>
                            <!--</div>-->
                            <!-- <div class = "col-2">
                              <button type="button" class = "form-control btn btn-md btn-danger">Delete</button>
                            </div> -->
                          </div>
                          <br>
                          <table class = "table">
                              <thead>
                                <tr class = "text-center">
                                    <th>Tree No.</th>
                                    <th>Tree Name</th>
                                    <th class = "activity">Tree activity</th>
                                    <th class = "condition">Condition</th>
                                    <th class = "noOfTrees">No of Trees</th>
                                    <th>Reason For Permission</th>
                                    <th>Tree Photo</th>
                                    <?php echo ($this->authorised_user['is_visitor'] == 1) ? '<th class = "refund_th">Refund</th>' : '' ; ?>
                                    <th>Action</th>
                                </tr>
                              </thead>
                              <tbody class = "tableBody">
                                <?php
                                  if (!empty($gardenData)) { 
                                  foreach( $gardenData as $keyGarden => $valueGarden){
                                ?>
                                  <tr class="garden_row">
                                    <td>
                                      <!-- Dhyey Rathod new code -->
                                        <input type="hidden" class="form-control text-center" readonly value="<?php echo $valueGarden['tree_no'] ?>" name="tree_no[]"><?php echo $valueGarden['tree_no'] ?>

                                    </td>
                                    <input type = "hidden" class="gardenId_array" name = "gardenId[]" value = "<?= $valueGarden['gardenId']; ?>">
                                    <td class = 'text-center name'>
                                      <select class='selectpicker form-control' id='treeName' name='treeName[]' data-live-search='true' required="">
                                        <option value="">Select Tree</option>
                                        
                                      <?php
                                        foreach($treeData as $key => $valueTree){
                                          if($valueTree['tree_id'] == $valueGarden['tree_id']){
                                            echo "<option value='".$valueGarden['tree_id']."' selected=''>".$valueTree['treeName']."</option>";
                                          }else{
                                            echo "<option value = '".$valueTree['tree_id']."'>".$valueTree['treeName']."</option>";
                                          }
                                        }
                                      ?>
                                      </select>
                                    </td>
                                    <td class = 'text-center activity'>
                                      <select class='selectpicker form-control' id='processName' name='processName[]' data-live-search='true' required="">
                                        <option value="0">Select Process</option>
                                        <?php foreach ($permissionTypes as $key => $vPermission) : ?>
                                            <?php if($key != 2 &&  $key != 3) : ?>
                                                <option <?php echo ($valueGarden['permission_id'] == $vPermission['garper_id']) ? 'selected' : '' ?> value="<?php echo $vPermission['garper_id'] ?>"><?php echo $vPermission['permission_type'] ?></option>
                                            <?php endif ; ?>
                                        <?php endforeach ; ?>    
                                      </select>
                                    </td>

                                    <td class = "text-center condition">
                                        <select class = "selectpicker form-control condition" name = "condition[]" data-live-search = "true" required>
                                            <option value="0">Select Condition</option>
                                            <option <?php echo ($valueGarden['conditionStatus'] == 1) ? 'selected' : '' ; ?> value="1">Hazardous</option>
                                            <option <?php echo ($valueGarden['conditionStatus'] == 2) ? 'selected' : '' ; ?> value="2">Non Hazardous</option>
                                        </select>
                                    </td>
                                    <td class = 'text-center noOfTrees'>
                                      <input type = 'number' min = "0" name = 'total_trees[]' id = 'total_trees' placeholder='Action On No Of Trees' class="form-control" value = "<?= $valueGarden['no_of_trees'] ?>" required="">
                                    </td>
                                    <td class = 'text-center permission'>
                                      <input type='text' class='form-control' name='reason_for_permission[]' id='reason_for_permission' placeholder='Enter Reason For Permission' value = "<?= $valueGarden['reason_permission'] ?>" required="">
                                    </td>
                                    <td class = 'text-center photo'>
                                      <input id='file-upload' name='filesNew[]' class = 'form-control' type='file'>
                                      <br>
                                      <span class = 'file_name' >
                                        <a href = "<?= base_url().'uploads/gardenImages/'.$valueGarden['enc_image'] ?>" download><i class = 'fa fa-download' aria-hidden='true'></i></a>
                                        <!-- <image src = "<?= base_url().'uploads/gardenImages/'.$valueGarden['enc_image'] ?>" style = "height: 100px; width: 100px"/> -->
                                      </span>
                                    </td>
                                    
                                    <?php if ($this->authorised_user['is_visitor'] == 1) : ?>
                                        <td class="refund_checkbox"><input type="checkbox" class="refund_selecter" value="<?php echo $valueGarden['gardenId']; ?>" style="zoom:2" <?php echo ($valueGarden['refundable'] == 1) ? 'checked' : '' ; ?>></td>
                                    <?php endif ; ?>

                                    <td class = 'text-center action'><span style = 'font-size: 25px; cursor:pointer' class = 'delete' data-id = '<?= $keyGarden; ?>' data-gardenId = "<?= $valueGarden['gardenId'];?>"><i class='fas fa-trash'></i></span>
                                    </td>
                                  </tr>
                                 <?php
                                  } }
                                 ?> 
                              </tbody>
                            </table>  
                        </div>  
                       </div>
                       <div class = "row">
                        <div class="col-4" style = "margin-bottom: 40px">
                          <?php
                            if($complainData['declarationGardenSuprintendent'] == '1'){
                          ?>   
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="0" name = "declaration" id="declaration" checked="">
                                <label class="form-check-label" for="declaration">
                                  <STRONG>Declaration To Garden Superitendent</STRONG>
                                </label>
                              </div>
                          <?php    
                            }else{
                          ?>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="0" name = "declaration" id="declaration" checked>
                              <label class="form-check-label" for="declaration">
                                <STRONG>Declaration To Garden Superitendent</STRONG>
                              </label>
                            </div>
                          <?php 
                            }
                          ?>
                        </div>
												<?php 
													if($complainData['blueprint'] != ''){
												?>	
														<div class="col-4 blueprint">
															<div class="form-group">
																<label for="blueprint">Upload Blueprint ( <a aria-label='Blueprint Uploaded' href = "<?= base_url().'uploads/gardenImages/'.$complainData['blueprint']; ?>" data-microtip-position='top' role='tooltip'  download><i class="fa fa-download" aria-hidden="true"></i></a> )</label>
																<input type="file" class="form-control" name="blueprint" id="blueprint" >
															</div>
														</div>	
												<?php
													}else{
												?>
													<div class="col-4 blueprint">
														<div class="form-group">
															<label for="blueprint">Upload Blueprint</label>
															<input type="file" class="form-control" name="blueprint" id="blueprint" >
														</div>
													</div>
												<?php
													}
												?>
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

<!-- Dhyey Rathod -->
<!-- new code -->
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/createComplain.js" id="createComplain" is_visitor="<?php echo $this->authorised_user['is_visitor'] ?>" is_user = "<?= $this->authorised_user['is_user']; ?>" is_type = 'edit'></script>
  
</script>


<!-- page script -->
</body>
</html>
