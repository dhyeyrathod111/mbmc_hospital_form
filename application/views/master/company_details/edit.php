  <?php $this->load->view('includes/header'); ?>
  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <form role="form" class="company_details_form" id="company_details_form" method="post">
                    <div class="card-body">
                      <div class="row">
                        
					            	<div class="col-4">
                          <div class="form-group">
                            <label for="company_name">Company Name<span class = "red">*</span></label>
                             <input type = "hidden" class = "form-control" value = "<?= $company_details[0]['company_id']; ?>" name="company_id" id="company_id">
                             <input type = "text" class = "form-control" id = "company_name" name = "company_name" placeholder="Enter Name Of Company Name" value = "<?= $company_details[0]["company_name"]; ?>" required>
                          </div>
                        </div>

                      </div>
  					
                        <div class = 'row'>
                          <table class = "table table-row table-striped">
                            <thead>
                              <tr class = "text-center">
                                <th>Sr No</th>
                                <th>Address</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody> 
                              <?php
                              $srNo = 1;
                                foreach($company_address as $key => $val){
                                  echo "<tr class = 'text-center'><td>".$srNo."</td><td class = 'address'>".$val['company_address']."</td><td><span aria-label = \"Delete\" data-microtip-position=\'top\' role=\"tooltip\" class = 'delete' data-addressid = '".$val['address_id']."' style = 'cursor:pointer;'><i class='nav-icon fas fa-trash'></i></span>&nbsp<span aria-label = \"Edit\" data-microtip-position=\'top\' role=\"tooltip\" class = 'edit' data-addressid = '".$val['address_id']."' style = 'cursor:pointer'><i class='nav-icon fas fa-edit'></i></span></td></tr>";
                                  $srNo++;
                                }
                              ?>
                            </tbody>
                          </table>
                        </div>
					  
                    </div>
                    <div class="card-footer">
                      <div class="row center">
                        <div class="col-12">
                          <a href = "<?= base_url()?>company_details/" class = "btn btn-lg btn-info white">Cancel</a>
                          <button type = "submit" class = "btn btn-lg btn-primary right">Submit</button>
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

		<div class="modal" id="editModal">
  		<div class="modal-dialog">
    		<div class="modal-content">

					<!-- Modal Header -->
					<div class="modal-header">
						<h4 class="modal-title">Edit Address</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					<!-- Modal body -->
					<div class="modal-body">
						<textarea name="addressEdit" id="addressEdit" class = "form-control"></textarea>
					</div>

					<!-- Modal footer -->
					<div class="modal-footer">
						<span class = "btn btn-info editSubmit">Submit</span>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>

				</div>
			</div>
		</div>

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
  var base_url = "<?= base_url(); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/company_details.js"></script>


<!-- page script -->
</body>
</html>
