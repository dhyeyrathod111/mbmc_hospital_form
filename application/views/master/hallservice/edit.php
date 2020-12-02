  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Edit Hall Service</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Hall Service</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="row alertdiv">
              <div class="col-12">
                <div class="card-body">
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i><p id="alert-danger"></p></h5>
                    
                    
                  </div>
                  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i><p id="alert-success"></p></h5>
                    
                  </div>
                </div>
              </div>
            </div>
            
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <form role="form" class="hall-service-form" id="hall-service-form" method="post">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-10"></div>
                      </div>
                       <div class="row">
                        <div class="col-12">
                          <h3 class="card-title">
                          </h3>
                        </div>
                      </div>
                    </div>

                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="sku_id">Sku<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="sku_id" name="sku_id" data-live-search="true">
                              <option value="">--- Select Sku ---</option>
                              <?php
                                foreach ($sku as $key => $val) {
                                  if($val['sku_id'] == $asset['sku_id']) {
                                    $select = "selected='selected'";
                                  } else {
                                     $select = "";
                                  }
                                  echo '<option value="'.$val['sku_id'].'" '.$select.'>'.$val['sku_title'].'</option>';
                                }
                              ?>
                            </select>
                            <input type="hidden" id="asset_id" name ="asset_id" value="<?= ($asset['asset_id']) ? $asset['asset_id']: ''?>">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="asset_name">Service name<span class="red">*</span></label>
                             <input type="text"  value="<?= ($asset['asset_name']) ? $asset['asset_name']: ''?>" class="form-control" id="asset_name" name ="asset_name" placeholder="Enter the service title">
                          </div>
                        </div> 

                        <div class="col-4">
                          <div class="form-group">
                            <label for="asset_unit_id">Unit<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="asset_unit_id" name="asset_unit_id" data-live-search="true">
                              <option value="">--- Select Unit ---</option>
                              <?php //echo'<pre>';print_r($unit);exit;
                                foreach ($unit as $key => $unit) {
                                  
                                  if($unit['unit_id'] == $asset['asset_unit_id']) {
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
                            <label for="amount">Service unit cost<span class="red">*</span></label>
                             <input type="text" value="<?= ($asset['asset_unit_cost']) ? $asset['asset_unit_cost']: ''?>" class="form-control" id="asset_unit_cost" name ="asset_unit_cost" placeholder="Enter the service unit cost">
                          </div>
                        </div> 

                        <div class="col-4">
                          <div class="form-group">
                            <label for="penalty_charges">Service penalty charges<span class="red">*</span></label>
                             <input type="text" value="<?= ($asset['penalty_charges']) ? $asset['penalty_charges']: ''?>" class="form-control" id="penalty_charges" name ="penalty_charges" placeholder="Enter the Service penalty charges">
                          </div>
                        </div> 
                      </div>
                    </div>

                    <div class="card-footer">
                      <div class="row center">
                        <div class="col-10">
                          <a href="<?= base_url()?>hall-service" class="btn btn-lg btn-info white">Cancel</a>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/hall_service.js"></script>
<!-- page script -->
</body>
</html>
