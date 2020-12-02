  <!-- Header -->
  <?php $this->load->view('includes/header'); ?>
  <!-- End Header -->

  <!-- Side Bar -->
  <?php $this->load->view('includes/sidenav'); ?>
  <!-- End Side Bar -->

  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>

  <div class = "content-wrapper">
  	<!-- Header -->
  	<section class="content-header">
      <div class="container-fluid">
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
      </div><!-- /.container-fluid -->
    </section>
    <!-- End Header -->

    <!-- Main -->
    <section class="content">
    	<div class="row">
    		<div class="col-12">
    			<div class="card">
    				<div class="card-header">
		              <h3 class="card-title">Create License Form</h3>
		            </div>

		            <!-- Alert Div -->
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
		            <!-- End ALert Div -->

		            <div class = "row">
		            	<div class="col-12">
		            		<!-- <div class="card card-primary"> -->
		            			<form role="form"  class="createTFForm" name = "createTFForm" id= "createTFForm" method="post" action = "" enctype="multipart/form-data" >
		            				<div class="card-body">
		            					<div class="row">
		            						<div class = "col-12">
					                          <p class = 'text-info' style = "font-size: 20px; font-weight: bold">Application Details</p>
					                        </div>
					                        <div class="col-4">
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
					                        <!-- End Col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="new_renewal">New Or Renewal</label><span class="red">*</span>
					                        	<select name="licenseType" id = "licenseType" class="form-control" required="">
					                        		<option value="0">Select License Type</option>
					                        		<option value="1">New</option>
					                        		<option value="2">Renewal</option>
					                        	</select>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="applicationDate">Existing No <span class="red">*</span></label>
					                        	<input type="number" min = "0" class="form-control" name="existingNo" id="existingNo" placeholder="Please Enter No" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4' style = "display: none">
					                          <div class = "form-group">
					                        	<label for="applicationDate">Application Date</label><span class="red">*</span>
					                        	<input type="text" class="form-control datepicker" name="applicationDate" id="applicationDate" placeholder="Please Select Date" value="<?= date("Y-m-d H:i:s")?>" required="" readonly = "">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="Name">Name</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="appName" id="appName" placeholder="Please Enter Name" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="shopno">Shop No</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="shopNo" id="shopNo" placeholder="Please Enter Shop No" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="address">Address</label><span class="red">*</span>
					                        	<textarea name="Address" id = "Address" class = "form-control" placeholder="Please Enter Address" required=""></textarea>
					                          </div>
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="propertyNo">Property No</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="propertyNo" id="propertyNo" placeholder="Please Enter Property No" required="">
					                          </div>
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="shopName">Shop Name</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="shopName" id="shopName" placeholder="Please Enter Shop Name" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="businessType">Type Of Business</label><span class="red">*</span>
					                        	<input type="text" class="form-control" name="businessType" id="businessType" placeholder="Please Enter Business Type" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="propertyType">Type Of Property <span class="red">*</span></label>
					                        	<select name="propertyType" id = "propertyType" class="form-control" required="">
					                        		<option value="0">Select Property Type</option>
					                        		<?php
					                        			foreach ($property_type as $keyProp => $valProp) {
					                        				echo "<option value = '".$valProp['prop_type_id']."'>".$valProp['prop_type_name']."</option>";
					                        			}
					                        		?>
					                        	</select>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="propertyDate">Property Date <span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="propertyDate" id="propertyDate" placeholder="Select Property Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="aadhar">Aadhar No <span class="red">*</span></label>
					                        	<input type="text" name="aadharNo" id = "aadharNo" class = "form-control" placeholder="Enter Aadhar No" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="pan">Pan <span class="red">*</span></label>
					                        	<input type="text" name="panNo" id = "panNo" class = "form-control" placeholder="Enter Pan No" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="dateNoObj">Date Soc No Obj <span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="noObjDate" id="noObjDate" placeholder="Select NoObj Cert Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="dateNoObj">Date Of Food Lic <span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="foodLicDate" id="foodLicDate" placeholder="Select Food Lic Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="dateNoObj">Date Of Property Tax <span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="propTaxDate" id="propTaxDate" placeholder="Select Property Tax Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="dateNoObj">Date Of Establishment <span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="establishmentDate" id="establishmentDate" placeholder="Select Establishment Date" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="dateNoObj">Date Of Assurance <span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="assuranceDate" id="assuranceDate" placeholder="Select Assurance Date" required="">
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/tradeFacLic.js" id = "createTrade" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>
  
</script>

<!-- page script -->
</body>
</html>
