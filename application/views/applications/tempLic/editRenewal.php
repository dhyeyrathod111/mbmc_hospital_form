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
              <li class="breadcrumb-item active">Renew Application</li>
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
		              <h3 class="card-title">Edit Renewal Form</h3>
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
		            			<form role="form"  class="renewApplicationForm" name = "renewApplicationForm" id="renewApplicationForm" method="post" enctype="multipart/form-data" >
		            				<div class="card-body">
		            					<div class="row">
		            						<div class = "col-12">
					                          <p class = 'text-info' style = "font-size: 20px; font-weight: bold">Application Details</p>
					                        </div>
					                        <div class="col-4">
					                        	<div class="form-group">
					                        		<label for="form_no"><span>Form No</span><span class="red">*</span></label>
					                        	  <input type="hidden" name="lic_id" value = "<?= $appData[0]['lic_id'] ?>">	
						                           <input type="text" class="form-control" value="<?= $appData[0]['form_no']; ?>" name="form_no" id="form_no" placeholder="Enter Form No" readonly>
					                        	</div>	
					                        </div>
					                        <!-- End Col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="applicationDate">license No <span class="red">*</span></label>
					                        	<input type="text" class="form-control" name="licenseNo" id="licenseNo" placeholder="Please Enter license No" value = "<?= $appData[0]['license_no']; ?>" required="" readonly="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <!-- <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="applicationDate">Application Date</label>
					                        	<input type="text" class="form-control datepicker" name="applicationDate" id="applicationDate" value="<?= $appData[0]['created_date'] ?>" placeholder="Please Select Date" required="">
					                          </div>	
					                        </div> -->
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">
					                        	<label for="applicationDate">License Type <span class="red">*</span></label>
					                        	<select name="licenseType" class="form-control">
					                        		<option value="0">Select License Type</option>
					                        		<?php
					                        			foreach($licType as $key => $value)
					                        			{
					                        				if($value['lic_type_id'] == $appData[0]['lic_type'])
					                        				{
					                        					echo "<option value = '".$value['lic_type_id']."' selected>".$value['lic_name']."</option>";
					                        				}else{
					                        					echo "<option value = '".$value['lic_type_id']."'>".$value['lic_name']."</option>";	
					                        				}
					                        				
					                        			}
					                        		?>
					                        	</select>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="applicationDate">Name <span class="red">*</span></label>
					                        	<input type="text" class="form-control" name="appName" id="appName" placeholder="Please Enter Name" value = "<?= $appData[0]['name']; ?>" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="applicationDate">Stall Address <span class="red">*</span></label>
					                        	<textarea name="stallAddress" id = "stallAddress" class = "form-control" placeholder="Please Enter Stall Address"><?= $appData[0]['stall_address']; ?></textarea>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->
					                        		
			                        		<div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="applicationDate">Date For Renewal <span class="red">*</span></label>
					                        	<input type="hidden" name="prevRenDate" value = "<?= $appData[0]['renewalDate']; ?>">
					                        	<input type="text" class="form-control datepicker" name="renewalDate" id="renewalDate" value = "<?= $appData[0]['renewalDate']; ?>" placeholder="Please Select Date" required="" readonly = "">
					                          </div>	
					                        </div>
							               
					                        <!-- END col4 -->
	
			                        		<div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="expiryDate">Expiry Date <span class="red">*</span></label>
					                        	<input type="hidden" name="prevExpDate" value = "<?= $appData[0]['expiryDate']; ?>">
					                        	<input type="text" class="form-control datepicker" name="expiryDate" id="expiryDate" placeholder="Please Select Date" value = "<?= $appData[0]['expiryDate']; ?>" required="" readonly="">
					                          </div>	
					                        </div>
							       			<!-- END col4 -->
							       			<div class = 'col-4'></div>
							       		  		
					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="earLicense">Earlier License <?php echo '<a href = "'.base_url().'uploads/licImages/'.$appData[0]['earlierLic'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>'; ?></label>
					                        	<input type="file" class="form-control" name="licenseCopy" id="licenseCopy" placeholder="Please Select File">
					                          </div>	
					                          <div>
					                          	<?php
					                          	// 	echo "<iframe name='earlic' id='earlic' src = ".base_url().'uploads/licImages/'.$appData[0]['earlierLic']." style='top:0px; left:0px; height:100%;width:100%'></iframe>"
					                          		
					                          	?>
					                          </div>
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="aadhar">Aadhar <?php echo '<a href = "'.base_url().'uploads/aadharImages/'.$appData[0]['aadhar'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>' ?></label>
					                        	<input type="file" class="form-control" name="aadhar" id="aadhar" placeholder="Please Select aadhar">
					                          </div>
					                          <div>
					                          	<?php
					                          	// 	echo "<iframe name='aadhar' id='aadhar' src = ".base_url().'uploads/aadharImages/'.$appData[0]['aadhar']." style='top:0px; left:0px; height:100%;width:100%'></iframe>"
					                          		
					                          	?>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="pan">Pan <?php echo '<a href = "'.base_url().'uploads/panImages/'.$appData[0]['pan'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>'; ?></label>
					                        	<input type="file" class="form-control" name="pan" id="pan" placeholder="Please Select Date">
					                          </div>
					                          <div>
					                          	<?php
					                          	// 	echo "<iframe name='pan' id='pan' src = ".base_url().'uploads/panImages/'.$appData[0]['pan']." style='top:0px; left:0px; height:100%;width:100%'></iframe>"
					                          		
					                          	?>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->
		            					</div>	
		            					<br>
		            					<center>
		            						<a class = "btn btn-lg btn-danger" href = "<?= base_url(); ?>templic/">Cancel</a>
	            							<button type="submit" class = "btn btn-lg btn-primary">Submit</button>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/tempLic.js"></script>
  
</script>

<!-- page script -->
</body>
</html>
