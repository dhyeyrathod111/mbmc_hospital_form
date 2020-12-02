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
		            			<form role="form"  class="createApplicationForm" name = "createApplicationForm" id= "createApplicationForm" method="post" action = "" enctype="multipart/form-data" >
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
					                        	<label for="applicationDate">license No <span class="red">*</span></label>
					                        	<input type="text" class="form-control" name="licenseNo" id="licenseNo" value = "<?= rand(1000, 9999); ?>" placeholder="Please Enter license No" required="" readonly="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <!-- <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="applicationDate">Application Date</label>
					                        	<input type="text" class="form-control datepicker" name="applicationDate" id="applicationDate" placeholder="Please Select Date" required="">
					                          </div>	
					                        </div> -->
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="applicationDate">License Type <span class="red">*</span></label>
					                        	<select name="licenseType" id = "licenseType" class="form-control">
					                        		<option value="0">Select License Type</option>
					                        		<?php
					                        			foreach($licType as $key => $value)
					                        			{
					                        				echo "<option value = '".$value['lic_type_id']."'>".$value['lic_name']."</option>";
					                        			}
					                        		?>
					                        	</select>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="applicationDate">Name <span class="red">*</span></label>
					                        	<input type="text" class="form-control" name="appName" id="appName" placeholder="Please Enter Name" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="applicationDate">Stall Address <span class="red">*</span></label>
					                        	<textarea name="stallAddress" id = "stallAddress" class = "form-control" placeholder="Please Enter Stall Address"></textarea>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="applicationDate">Date For Renewal <span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="renewalDate" id="renewalDate" placeholder="Please Select Date" required="" readonly="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="applicationDate">Expiry Date <span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="expiryDate" id="expiryDate" placeholder="Please Select Date" required="" readonly="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="earLicense">Earlier License <span class="red">*</span></label>
					                        	<input type="file" class="form-control" name="licenseCopy" id="licenseCopy" placeholder="Please Select File" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="aadhar">Aadhar <span class="red">*</span></label>
					                        	<input type="file" class="form-control" name="aadhar" id="aadhar" placeholder="Please Select aadhar" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="pan">Pan <span class="red">*</span></label>
					                        	<input type="file" class="form-control" name="pan" id="pan" placeholder="Please Select Pan" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->
		            					</div>	
		            					<br>
		            					<center>
	            							<span class = "btn btn-lg btn-danger cancel">Cancel</span>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/tempLic.js" id = "createTemp" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>
  
</script>

<!-- page script -->
</body>
</html>
