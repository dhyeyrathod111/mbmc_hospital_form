  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <form role="form" class="price-form" id="price-form" method="post">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="email_id">Department Name<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="dept_id" name="dept_id" data-live-search="true">
                              <option value="">--- Select Department ---</option>
                              <?php
                                foreach ($department as $key => $dept) {
                                  echo '<option value="'.$dept['dept_id'].'">'.$dept['dept_title'].'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="sku_id">Sku Name<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="sku_id" name="sku_id" data-live-search="true">
                              <option value="">--- Select Sku Name ---</option>
                            </select>
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="email_id">Unit<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="unit_id" name="unit_id" data-live-search="true">
                              <option value="">--- Select Unit ---</option>
                              <?php
                                foreach ($unit as $key => $val) {
                                  echo '<option value="'.$val['unit_id'].'">'.$val['unit_value'].' '.$val['unit_label'].'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>                        
                      </div><br>
                      <div class = "row">
                          <div class="col-4">
                           <div class="form-group">
                            <label for="amount">Amount<span class="red">*</span></label>
                              <input type="hidden" class="form-control" value="" name="price_id" id="price_id">
                             <input type="text" class="form-control" id="amount" name ="amount" placeholder="Enter the road title">
                           </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="row center">
                        <div class="col-12">
                          <a href="<?= base_url()?>price" class="btn btn-lg btn-info white">Cancel</a>
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
  var base_url = "<?=base_url()?>";
</script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/price.js"></script>


<!-- page script -->
</body>
</html>
