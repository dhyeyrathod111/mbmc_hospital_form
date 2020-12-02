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
            <h1>Add Storage Details</h1>
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
		              <h3 class="card-title">Create Form</h3>
		            </div>

		            <div class = "row">
		            	<div class="col-12">
		            		<form role="form"  class="createStorage" name = "createStorage" id= "createStorage" method="post" action = "" enctype="multipart/form-data" >
		            			<div class="card-body">
		            				<div class="row">
		            					<div class = "col-12">
				                          <p class = 'text-info' style = "font-size: 20px; font-weight: bold">Storage Details</p>
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
				                        	<label for="renewalLic">Renewal License</label><span class="red">*</span>
				                        	<select name="renewalLic" id = "renewalLic" class = "form-control" required="">
				                        		<option value = "0">Select Licence Type</option>
				                        		<option value="1">Yes</option>
				                        		<option value="2">No</option>
				                        	</select>
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4 oldLicSec' style = "display: none;">
				                          <div class = "form-group">
				                        	<label for="oldLicNo">Old License No</label><span class="red">*</span>
				                        	<input type="text" name="old_lic_no" id = "old_lic_no" class = "form-control" placeholder="Enter Old Lic No" required="">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4 LicSec' style = "display: none;">
				                          <div class = "form-group">
				                        	<label for="LicNo">License No</label><span class="red">*</span>
				                        	<input type="text" name="lic_no" id = "lic_no" class = "form-control" placeholder="Enter Old Lic No" value="<?= rand(1000,9999); ?>" required="">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="name">Name</label><span class="red">*</span>
				                        	<input type="text" class="form-control" name="name" id="name" placeholder="Please Enter Name" required="">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="address1">Address 1</label><span class="red">*</span>
				                        	<textarea name="address_1" id = "address_1" placeholder="Enter Address 1" class = "form-control" required=""></textarea>
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="address2">Address 2</label>
				                        	<textarea name="address_2" id = "address_2" placeholder="Enter Address 2" class = "form-control"></textarea>
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="teleph/one">Telephone</label>
				                        	<input type="text" name="telephone" class = "form-control" id = "telephone" placeholder="Enter Telephone Number">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="mobile">Mobile No</label><span class="red">*</span>
				                        	<input type="text" name="mobile" class = "form-control" id = "mobile" placeholder="Enter Mobile Number" required="">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="godaddress1">Godown Address 1</label><span class="red">*</span>
				                        	<textarea name="godaddress_1" id = "godaddress_1" placeholder="Enter Godown Address 1" class = "form-control" required=""></textarea>
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="godaddress2">Godown Address 2</label>
				                        	<textarea name="godaddress_2" id = "godaddress_2" placeholder="Enter Godown Address 2" class = "form-control"></textarea>
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="productName">Product Name</label><span class="red">*</span>
				                        	<input type="text" name="productName" id = "productName" class = "form-control" required="">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="godArea">Godown Area</label><span class="red">*</span>
				                        	<input type="text" name="godownArea" id = "godownArea" class = "form-control" placeholder="Enter Godown Area">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="godType">Type Of Godown</label><span class="red">*</span>
				                        	<input type="text" name="godownType" id = "godownType" class = "form-control" placeholder="Enter Type Of Godown">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="startDate">Start Date</label><span class="red">*</span>
				                        	<input type="text" name="startDate" id = "startDate" class = "form-control datepicker" readonly="">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="otherLic">Other Muncipal Lic</label><span class="red">*</span>
				                        	<input type="text" name="otherMunLic" id = "otherMulLic" class = "form-control" placeholder="Enter Other Mullcipal Lic">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="explosive">Explosive</label><span class="red">*</span>
				                        	<select name="explosive" id = "explosive" class = "form-control" required="">
				                        		<option value = "0">Select Explosive</option>
				                        		<option value="1">Yes</option>
				                        		<option value="2">No</option>
				                        	</select>
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="pendingDues">Pending Dues</label><span class="red">*</span>
				                        	<input type="text" name="pendingDues" id = "pendingDues" class = "form-control" placeholder="Enter Pending Dues" required="">
				                          </div>	
				                        </div>
				                        <!-- END col4 -->

				                        <div class = 'col-4'>
				                          <div class = "form-group">
				                        	<label for="dissapp">Disapprove Earlier</label><span class="red">*</span>
				                        	<select name="disapproveEarlier" id = "disapproveEarlier" class = "form-control">
				                        		<option value = "-1">Select Approval</option>
				                        		<option value="0">Yes</option>
				                        		<option value="1">No</option>
				                        	</select>
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
		            		</form>
		            	</div>
		            </div>
    			</div>
    		</div>
    	</div>
    </section>

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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/storage.js" id = "createStorage" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>
  
</script>