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
                  <form role="form" class="defectliab_form" id="defectliab_form" method="post">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="liab_name">Defect Liablity Period<span class="red">*</span></label>
                              <input type="hidden" class="form-control" value="" name="liab_id" id="liab_id">
                             <input type="text" class="form-control" id="liab_name" name = "liab_name" placeholder="Enter Name Of Defect Liablity" required>
                          </div>
                        </div>

						<div class = "col-4">
							<div class = "form-group">
								<label for="mul_factor"><span class = "red">*</span></label>
								<input type="number" class = "form-control" id = "mul_factor" name = "mul_factor" placeholder = "Enter Multiplication Factor" min = "0" step = "any" required>
							</div>
						</div>

                        <div class = "col-4">
							<div class = "form-group">
								<label for="date_from">Date From<span class = "red">*</span></label>
								<input type="text" class = "form-control datepicker" id = "date_from" name = "date_from" placeholder = "Enter From Date" readonly required>
							</div>
						</div>

                        <div class = "col-4">
							<div class = "form-group">
								<label for="factor">Date Till</label>
								<input type="text" class = "form-control datepicker" id = "date_till" name = "date_till" placeholder = "Enter To Date" readonly required>
							</div>
						</div>

                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="row center">
                        <div class="col-12">
                          <a href="<?= base_url()?>defect_liab/" class="btn btn-lg btn-info white">Cancel</a>
                          <button type="submit" class="btn btn-lg btn-primary right">Submit</button>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/defectliab.js"></script>


<!-- page script -->
</body>
</html>
