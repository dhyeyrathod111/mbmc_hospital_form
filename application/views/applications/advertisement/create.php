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
    		<div class="col-md-12">
    			<div class="card">

		            <div class = "row">
		            	<div class="col-md-12">
		            		<form role="form"  class="advertisementForm" name = "advertisementForm" id= "advertisementForm" method="post" action = "" enctype="multipart/form-data" >
		            			<div class="card-header">
			                       <div class="row">
			                        <div class="col-md-12">
			                          <h3 class="card-title">
			                            <label for="email_id" class="text-info">Personal Information</label>
			                          </h3>
			                        </div>
			                      </div>
			                    </div>

			                    <div class="card-body">
                      				<div class="row">
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
						                        <input type="text" class="form-control" value="<?= $app_no; ?>" name="form_no" id="form_no" placeholder="Enter Form No" readonly>
					                        </div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="name"><span>Name</span><span class="red">*</span></label>
					                    		<input type="text" name="name" id = "name" class = "form-control" placeholder="Please Enter Name" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="address"><span>Address</span><span class = "red">*</span></label>
					                    		<textarea name="address" id = "address" class = "form-control" placeholder = "Please Enter Address" required=""></textarea>
					                    	</div>
					                    </div>	
                      				</div>
                      			</div>

                      			<div class="card-header">
			                       <div class="row">
			                        <div class="col-12">
			                          <h3 class="card-title">
			                            <label for="hoarding_info" class="text-info">Hoarding Information</label>
			                          </h3>
			                        </div>
			                      </div>
			                    </div>

			                    <div class = "card-body">
			                    	<div class="row">
			                    		<div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="hoarding_address"><span>Hoarding Location Address</span><span class = "red">*</span></label>
					                    		<textarea name="hoarding_address" id = "hoardin_address" class = "form-control" placeholder="Please Enter Hoarding Location Address" required=""></textarea>
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="adv_type"><span>Type Of Advertisement</span><span class = "red">*</span></label>
					                    		<select name="adv_type" id = "adv_type" class = "form-control" required="">
					                    			<option value="0">Select Type</option>
					                    			
					                    			<?php
					                    				if(!empty($adv_type)){
						                    				foreach($adv_type as $kType => $vType){
						                    					echo "<option value = '".$vType['adv_id']."'>".$vType['name']."</option>";
						                    				}
						                    			}	
					                    			?>

					                    		</select>
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<label for="illuminate"><span>Illuminate</span><span class = "red">*</span></label>

					                    	<select name="illuminate" id = "illuminate" class = "form-control" required="">
					                    		<option value="0">Select Illuminate</option>
					                    		<?php
					                    			if(!empty($illuminate)){
					                    				foreach ($illuminate as $kill => $vill) {
					                    					echo "<option value = '".$vill['ill_id']."'>".$vill['name']."</option>";
					                    				}
					                    			}
					                    		?>
					                    	</select>

					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="hoarding_length"><span>Hoarding Length</span><span class = "red">*</span></label>
					                    		<input type="number" name="hoarding_length" id = "hoarding_length" class = "form-control" placeholder = "Please Enter Hoarding Length" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="hoarding_breadth"><span>Hoarding Breadth</span><span class = "red">*</span></label>
					                    		<input type="number" name="hoarding_breadth" id = "hoarding_breadth" class = "form-control" placeholder = "Please Enter Hoarding Breadth" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="road_height"><span>Road Height</span><span class = "red">*</span></label>
					                    		<input type="number" name="road_height" id = "road_height" class = "form-control" placeholder = "Please Enter Height Of Road" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="serchena"><span>Serchana</span><span class = "red">*</span></label>
					                    		<input type="text" name="serchana" id = "serchana" class = "form-control" placeholder = "Please Enter serchana" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="hoarding_loc"><span>Hoarding Location</span><span class = "red">*</span></label>
					                    		<input type="text" name="hoarding_loc" id = "hoarding_loc" class = "form-control" placeholder="Please Enter Hoarding Location" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="start_date"><span>Start Date</span><span class = "red">*</span></label>
					                    		<input type="text" name="start_date" id = "start_date" class = "form-control datepicker" placeholder="Please Select Start Date" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="no_od_days"><span>No. Of Days</span><span class = "red">*</span></label>
					                    		<input type="text" name="no_of_days" id = "no_of_days" class = "form-control" placeholder="Please Enter No. Of Days" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="end_date"><span>End Date</span><span class = "red">*</span></label>
					                    		<input type="text" name="end_date" id = "end_date" class = "form-control datepicker" placeholder="Please Select End Date" required="" readonly="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="rate"><span>Rate</span><span class = "red">*</span></label>
					                    		<input type="number" name="rate" id = "rate" class = "form-control" placeholder="Please Enter Rate" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="amount"><span>Amount</span><span class = "red">*</span></label>
					                    		<input type="number" name="amount" id = "amount" class = "form-control" placeholder="Please Enter Amount" required="">
					                    	</div>
					                    </div>

			                    	</div>
			                    </div>

			                    <div class="card-header">
			                       <div class="row">
			                        <div class="col-md-12">
			                          <h3 class="card-title">
			                            <label for="other Details" class="text-info">Other Details</label>
			                          </h3>
			                        </div>
			                      </div>
			                    </div>

			                    <div class = "card-body">
			                    	<div class = "row">
			                    		<div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="req_type"><span>Request Type</span><span class = "red">*</span></label>

					                    		<select name="req_type" id = "req_type" class = "form-control" required="">
					                    			<option value="0">Select Request Type</option>
					                    			<?php
					                    				foreach ($req_type as $kreq => $vreq) {
					                    					echo "<option value = '".$vreq['req_id']."'>".$vreq['name']."</option>";
					                    				}
					                    			?>
					                    		</select>
					                    	</div>
					                    </div>

					                    <div class = "col-md-4 companyDetails" style = "display: none;">
					                    	<div class = "form-group">
					                    		<label for="comp_address"><span>Company Address 1</span><span class = "red">*</span></label>
					                    		<textarea name="comp_add1" id = "comp_add1" class = "form-control" placeholder="Enter Company Address 1"></textarea>
					                    	</div>
					                    </div>

					                    <div class = "col-md-4 companyDetails" style = "display: none;">
					                    	<div class = "form-group">
					                    		<label for="comp_address2"><span>Company Address 2</span></label>
					                    		<textarea name="comp_add2" id = "comp_add2" class = "form-control" placeholder="Enter Company Address 2"></textarea>
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="pan"><span>PanCard</span><span class = "red">*</span></label>
					                    		<input type="file" name="pancard" id = "pancard" class = "form-control" placeholder="Please Select PanCard" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="aadhar"><span>Aadhar</span><span class = "red">*</span></label>
					                    		<input type="file" name="aadhar" id = "aadhar" class = "form-control" placeholder="Please Select Aadhar" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="society_type"><span>Society Notice</span><span class = "red">*</span></label>
					                    		<select name="society_notice" id = "society_notice" class = "form-control" required="">
					                    			<option value="1">No</option>
					                    			<option value="2">Yes</option>
					                    		</select>
					                    	</div>
					                    </div>

					                    <div class = "col-md-4 uploadNotice" style = "display: none">
					                    	<div class = "form-group">
					                    		<label for="society_not_upload"><span>Uplaod Society Notice</span><span class = "red">*</span></label>
					                    		<input type="file" name="soc_not" id = "soc_not" class = "form-control" placeholder="Please Select Society Notice">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="own_ho_name"><span>Owner Hoarding Name</span><span class = "red">*</span></label>
					                    		<input type="text" name="owner_hoarding_name" id = "owner_hoarding_name" class = "form-control" placeholder="Please Enter Hoarding Owner Name" required="">
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="own_ho_add"><span>Owner Hoarding Address</span><span class = "red">*</span></label>
					                    		<textarea name="owner_hoarding_add" id = "owner_hoarding_add" class = "form-control" placeholder="Please Enter Hoarding Owner Address" required=""></textarea>
					                    	</div>
					                    </div>

					                    <div class = "col-md-4">
					                    	<div class = "form-group">
					                    		<label for="noc"><span>Noc</span><span class = "red">*</span></label>
					                    		<select name="noc" id = "noc" class = "form-control" required="">
					                    			<option value="1">No</option>
					                    			<option value="2">Yes</option>
					                    		</select>
					                    	</div>
					                    </div>

					                    <div class = "col-md-4 uploadNoc" style = "display: none;">
					                    	<div class = "form-group">
					                    		<label for="noc_up"><span>Upload Noc</span><span class = "red">*</span></label>
					                    		<input type="file" name="upload_noc" id = "upload_noc" class = "form-control" placeholder="Please Select Noc">
					                    	</div>
					                    </div>
			                    	</div>
			                    </div>

		            			<div class="card-footer">
			                       <div class="row center">
			                         <div class="col-12">
			                            <a href="<?= base_url()?>advertisement" class="btn btn-lg btn-info white">Cancel</a>
			                            <button type="submit" class="btn btn-lg btn-primary right">Submit</button>
			                        </div>
			                      </div>
			                      
			                    </div>

		            		</form>
		            	</div>
		            </div>

		        </div>    
    		</div>
    	</div>
    </section>

  </div>

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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/advertisement.js" id = "createAdvertisement" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>
<!-- page script -->
</body>
</html>