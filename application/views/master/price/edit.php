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
                       <!--  <div class="col-4">
                          <div class="form-group">
                            <label for="dept_id">Department Name<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="dept_id" name="dept_id" data-live-search="true">
                              <option value="">--- Select Department ---</option>
                              <?php
                                foreach ($department as $key => $dept) {
                                  if($price['dept_id'] == $dept['dept_id']) {
                                    $select = "selected='selected'";
                                  } else {
                                     $select = "";
                                  }
                                  echo '<option value="'.$dept['dept_id'].'" '.$select.'>'.$dept['dept_title'].'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div> -->

                        <!-- <div class="col-4">
                          <div class="form-group">
                            <label for="dept_id">Sku <span class="red">*</span></label>
                            <select class="selectpicker form-control" id="dept_id" name="dept_id" data-live-search="true">
                              <option value="">--- Select Sku ---</option>
                              <?php
                                foreach ($sku as $key => $sku) {
                                  if($price['sku_id'] == $dept['sku']) {
                                    $select = "selected='selected'";
                                  } else {
                                     $select = "";
                                  }
                                  echo '<option value="'.$dept['dept_id'].'" '.$select.'>'.$dept['dept_title'].'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>
 -->
                        <div class="col-4">
                          <div class="form-group">
                            <label for="unit_id">Unit<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="unit_id" name="unit_id" data-live-search="true">
                              <option value="">--- Select Unit ---</option>
                              <?php //echo'<pre>';print_r($unit);exit;
                                foreach ($unit as $key => $unit) {
                                  
                                  if($unit['unit_id'] == $price['unit_id']) {
                                    $select = "selected='selected'";
                                  } else {
                                     $select = "";
                                  }

                                  echo '<option value="'.$unit['unit_id'].'" '.$select.'>'.$unit['unit_value'].' '.$unit['unit_label'].'</option>';
                                }
                              ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="amount">Amount<span class="red">*</span></label>
                            <input type="hidden" class="form-control" name="price_id" id="price_id" value="<?= ($price['price_id']) ? $price['price_id']: ''?>" >
                            <input type="hidden" class="form-control" name="dept_id" id="dept_id" value="<?= ($price['dept_id']) ? $price['dept_id']: ''?>" >
                            <input type="hidden" class="form-control" name="sku_id" id="sku_id" value="<?= ($price['sku_id']) ? $price['sku_id']: ''?>" >
                             <input type="text" class="form-control" id="amount" name ="amount" value="<?= ($price['amount']) ? $price['amount']: ''?>"  placeholder="Enter amount">
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
