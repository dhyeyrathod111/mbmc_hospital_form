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
            
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Application</li>
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
		              <h3 class="card-title">Edit License Form</h3>
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
		            			<form role="form"  class="createApplicationFormEdit" name = "createApplicationFormEdit" id= "createApplicationFormEdit" method="post" action = "" enctype="multipart/form-data" >
		            				<div class="card-body">
		            					<div class="row">
		            						<div class = "col-12">
					                          <p class = 'text-info' style = "font-size: 20px; font-weight: bold">Application Details</p>
					                        </div>
					                        <div class="col-4">
					                        	<div class="form-group">
					                        		<label for="form_no"><span>Form No</span><span class="red">*</span></label>
					                        		<?php

						                              $app_no = $appData[0]['form_no'];
						                            // $app_no = 'MBMC-000001';
						                           ?>
						                           <input type="text" class="form-control" value="<?=$app_no; ?>" name="form_no" id="form_no" value = "<?= $app_no ?>" placeholder="Enter Form No" readonly>

						                           <input type="hidden" name="film_id" value = "<?= $appData[0]['film_id'] ?>">
					                        	</div>	
					                        </div>
					                        <!-- End Col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="cntctPersonName">Contact Person Name<span class="red">*</span></label>
					                        	<input type="text" class="form-control" name="cntctPersonName" id="cntctPersonName" value = "<?= $appData[0]['contact_person'] ?>" placeholder="Please Enter Contact Person Name" required="">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="reasonForLic">Reason For License<span class="red">*</span></label>
					                        	<textarea name="reasonForLicense" id = "reasonForLicense" class = "form-control" placeholder="Please Enter Reason"><?= $appData[0]['reason_for_lic']; ?></textarea>
					                          </div>	
					                        </div>
					                        <!-- END col4 -->


					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="placeShooting">Place Of Shooting<span class="red">*</span></label>
					                        	<input type="text" class="shootingPlace form-control" name="shootingPlace" id="shootingPlace" value = "<?= $appData[0]['place_of_shooting'] ?>" placeholder="Enter Place Of Shooting" required="">

					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="policeNoc">Police Noc<span class="red">*</span>&nbsp&nbsp&nbsp<a href = "<?= base_url().'uploads/filmImages/'.$appData[0]['police_noc'] ?>" download><i class="fa fa-download" aria-hidden="true"></i></a></label>
					                        	<input type="file" class="form-control" name="policeNoc" id="policeNoc" placeholder="Please Police Noc">
					                        	<input type="hidden" name="oldNoc" value = "<?= $appData[0]['police_noc'] ?>">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="aadhar">Aadhar<span class="red">*</span>&nbsp&nbsp&nbsp<a href = "<?= base_url().'uploads/filmImages/'.$appData[0]['aadhar'] ?>" download><i class="fa fa-download" aria-hidden="true"></i></a></label>
					                        	<input type="file" class="form-control" name="aadhar" id="aadhar" placeholder="Please Select aadhar" >
					                        	<input type="hidden" name="oldAadhar" value = "<?= $appData[0]['aadhar'] ?>">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="pan">Pan<span class="red">*</span>&nbsp&nbsp&nbsp<a href = "<?= base_url().'uploads/filmImages/'.$appData[0]['pan'] ?>" download><i class="fa fa-download" aria-hidden="true"></i></a></label>
					                        	<input type="file" class="form-control" name="pan" id="applicationDate" placeholder="Please Select Pan">
					                        	<input type="hidden" name="oldPan" value = "<?= $appData[0]['pan'] ?>">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="from">Period From<span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="periodFrom" id="periodFrom" placeholder="Please Select Date" required="" value = "<?= $appData[0]['period_from']; ?>">
					                          </div>	
					                        </div>
					                        <!-- END col4 -->

					                        <div class = 'col-4'>
					                          <div class = "form-group">	
					                        	<label for="to">Period To<span class="red">*</span></label>
					                        	<input type="text" class="form-control datepicker" name="periodTo" id="periodTo" placeholder="Please Select Date" required="" value = "<?= $appData[0]['period_to']; ?>">
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/filmLic.js"></script>
  
</script>

<!-- page script -->
</body>
</html>
