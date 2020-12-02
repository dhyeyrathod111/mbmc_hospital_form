  <!-- Header -->
  <?php $this->load->view('includes/header'); ?>
  <!-- End Header -->

  <!-- Side Bar -->
  <?php $this->load->view('includes/sidenav'); ?>
  <!-- End Side Bar -->

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

  <div class = "content-wrapper">
  	
    <!-- Main -->
    <section class="content">
    	<div class="row">
    		<div class="col-12">
    			<div class="card">
    				<div class="card-header">
					<div class="row mb-2">
			          <div class="col-sm-6">
			            <h1>Add Application</h1>
			          </div>
			          <div class="col-sm-6">
			            <ol class="breadcrumb float-sm-right">
			              <li class="breadcrumb-item"><a href="#">Home</a></li>
			              <li class="breadcrumb-item active">Create Application</li>
			            </ol>
			          </div>
			        </div>
		              <h3 class="card-title">Create License Form</h3>
		            </div>

		            <!-- Alert Div -->
		            <!-- <div class="row alertdiv">
		              <div class="col-12">
		                <div class="card-body">
		                  <div class="alert alert-danger alert-dismissible">
		                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                    <p id="alert-danger"></p>
		                    
		                  </div>
		                  <div class="alert alert-success alert-dismissible">
		                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		                    <p id="alert-success"></p>
		                  </div>
		                </div>
		              </div>
		            </div> -->
		            <!-- End ALert Div -->

		            <div class = "row">
		            	<div class="col-12">
		            		<!-- <div class="card card-primary"> -->
		            			<form role="form"  class="editApplicationForm" name = "editApplicationForm" id= "editApplicationForm" method="post" action = "" enctype="multipart/form-data" >
		            				<div class="card-body">
		            					<div class="row">
		            						<div class = "col-12">
					                          <p class = 'text-info' style = "font-size: 20px; font-weight: bold">Application Details</p>
					                        </div>
					                        <div class="col-4">
					                        	<div class="form-group">
					                        		<label for="form_no"><span>Form No</span><span class="red">*</span></label>
					                        		<?php
						                              // if(!empty($appId)) {

						                              // 	  $app_val = $appId['application_id'];
						                              // 	  $app_val++;	
						                              //     $app_no = 'MBMC-00000'.$app_val;
						                                  
						                              // } else {
						                              //   $app_no = 'MBMC-000001';
						                              // }
						                            $app_no = $appData[0]['form_no'];
						                           ?>
						                           <input type="hidden" name="tradeFact_id" value = "<?= $appData[0]['tradefac_lic_id'] ?>">
						                           <input type="text" class="form-control" value="<?= $app_no; ?>" name="form_no" id="form_no" placeholder="Enter Form No" readonly>
					                        	</div>	
					                        </div>
					                        <!-- End Col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="new_renewal">New Or Renewal</label><span class="red">*</span>
					                        	<input type="hidden" name="licType" id = "licType" value = "<?= $appData[0]['new_renewal'] ?>">
					                        	<input type="hidden" name="existNo" id = "existNo" value = "<?= $appData[0]['existing_no'] ?>">
					                        	<select name="licenseType" id = "licenseTypeEdit" class="form-control">

					                        	<?php
					                        		$ltArray = array('0' => 'Select License Type','1' => 'New', '2' => 'Renewal');
					                        		foreach ($ltArray as $key => $vallt) {
					                        			if($key == $appData[0]['new_renewal']){
					                        				echo "<option value = '".$key."' selected = ''>".$vallt."</option>";
					                        			}else{
					                        				echo "<option value = '".$key."'>".$vallt."</option>";
					                        			}
					                        		}
					                        	?>	
					                        	</select>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="applicationDate">Existing No</label>
					                        	<input type="text" class="form-control datepicker" name="existingNo" id="existingNo" value = "<?= $appData[0]['existing_no'] ?>" placeholder="Please Select Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4' style = "display: none">
					                          <div class = "form-group">
					                        	<label for="applicationDate">Application Date</label><span class="red">*</span>
					                        	<input type="text" class="form-control datepicker" name="applicationDate" id="applicationDate" placeholder="Please Select Date" value="<?= $appData[0]['application_date'] ?>" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="Name">Name</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="appName" id="appName" placeholder="Please Enter Name" value = "<?= $appData[0]['name']; ?>" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="shopno">Shop No</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="shopNo" id="shopNo" placeholder="Please Enter Shop No" value = "<?= $appData[0]['shop_no']; ?>" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="address">Address</label><span class="red">*</span>
					                        	<textarea name="Address" id = "Address" class = "form-control" placeholder="Please Enter Address"><?= $appData[0]['address']; ?></textarea>
					                          </div>
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="propertyNo">Property No</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="propertyNo" id="propertyNo" placeholder="Please Enter Property No" value = "<?= $appData[0]['property_no']; ?>" required="">
					                          </div>
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="shopName">Shop Name</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="shopName" id="shopName" value = "<?= $appData[0]['shop_name'] ?>" placeholder="Please Enter Shop Name" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="businessType">Type Of Business</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="businessType" id="businessType" placeholder="Please Enter Business Type" value = "<?= $appData[0]['type_of_business']; ?>" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="propertyType">Type Of Property</label>
					                        	<select name="propertyType" class="form-control">
					                        	<?php
					                        		$propArray = array('0' => 'Select Property Type', '1'=> 'Purchased', '2' => 'Leave And License', '3' => 'Deed');

					                        		foreach($propArray as $keyProp => $valProp){
					                        			if($keyProp == $appData[0]['type_of_property']){
					                        				echo "<option value = '".$keyProp."' selected = ''>".$valProp."</option>";
					                        			}else{
					                        				echo "<option value = '".$keyProp."'>".$valProp."</option>";
					                        			}
					                        		}
					                        	?>	
					                        		<option value="0">Select Property Type</option>
					                        		<option value="1">Purchased</option>
					                        		<option value="2">Leave And License</option>
					                        		<option value="3">Deed</option>
					                        	</select>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="propertyDate">Property Date</label>
					                        	<input type="text" class="form-control datepicker" name="propertyDate" value = "<?= $appData[0]['property_date']?>" id="propertyDate" placeholder="Select Property Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="aadhar">Aadhar No</label>
					                        	<input type="text" name="aadharNo" id = "aadharNo" class = "form-control" placeholder="Enter Aadhar No" value = "<?= $appData[0]['aadhar_no'] ?>" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="pan">Pan</label>
					                        	<input type="text" name="panNo" id = "panNo" class = "form-control" placeholder="Enter Pan No" value = "<?= $appData[0]['pan_no'] ?>" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="dateNoObj">Date Soc No Obj</label>
					                        	<input type="text" class="form-control datepicker" name="noObjDate" id="noObjDate" placeholder="Select NoObj Cert Date" value = "<?= $appData[0]['date_no_obj'] ?>" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="foodLicDate">Date Of Food Lic</label>
					                        	<input type="text" class="form-control datepicker" name="foodLicDate" id="foodLicDate" value="<?= $appData[0]['date_food_lic'] ?>" placeholder="Select Food Lic Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="dateNoObj">Date Of Property Tax</label>
					                        	<input type="text" class="form-control datepicker" name="propTaxDate" id="propTaxDate" value="<?= $appData[0]['date_property_tax'] ?>" placeholder="Select Property Tax Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="dateNoObj">Date Of Establishment</label>
					                        	<input type="text" class="form-control datepicker" name="establishmentDate" id="establishmentDate" placeholder="Select Establishment Date" value = "<?= $appData[0]['date_establishment'] ?>" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="dateNoObj">Date Of Assurance</label>
					                        	<input type="text" class="form-control datepicker" name="assuranceDate" id="assuranceDate" value="<?= $appData[0]['date_assurance'] ?>" placeholder="Select Assurance Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->
		            					</div>	
		            					<br>
		            					<center>
		            						<span class = "btn btn-lg btn-info cancel">Cancel</span>
	            							<input type="submit" class = "btn btn-lg btn-primary" value = 'Submit'>
	            							
	            						</center>

		            				</div>

		            				<!-- End Card Body -->
		            			</form>	
		            			<!-- END Form -->
		            		<!-- </div>	 -->
		            	</div>	
		            </div>
		            <!-- End row -->
    			</div>	
    			<!-- END Card -->
    		</div>
    		<!-- End Col -->
    	</div>
    	<!-- End Row -->
    </section>	
    <!-- End Main -->

  </div>
  <!-- End Wrapper -->
<?php $this->load->view('includes/footer');?>
</div>

<!-- DataTables -->
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/tradeFacLic.js"></script>
  
</script>

<!-- page script -->
</body>
</html>
