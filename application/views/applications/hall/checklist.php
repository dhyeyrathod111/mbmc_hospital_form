  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <style type="text/css">
    
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Hall Checklist</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Hall Checklist</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid - ->
    </section> -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <form role="form" class="hall-asset-form" id="hall-asset-form" method="post">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-12">
                          <div class="card-header">
                            <h3 class="card-title">
                              <label for="email_id" class="text-info">Booking Details</label>
                            </h3>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="row">
                            <div class="col-4">
                              <div class="form-group">
                                <label for="application_no"><span>Application No</span><span class="red">*</span></label>
                                <input type="text" class="form-control" value="MBMC-00000<?=$app_details['app_id']?>" name="application_no" id="application_no" placeholder="Enter Application no" readonly>
                                <input type="hidden" class="form-control" name="app_id"  id="app_id" value="<?=$app_details['app_id']?>">
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-10">
                        </div>
                       <!--  <div class="col-2">
                          <a href="<?= base_url()?>hall" class="btn btn-lg btn-info white">ADD</a>
                          <button type="submit" class="btn btn-lg btn-primary right">Delete</button>
                        </div> -->
                        <div class="col-12">
                          <div class="card-header">
                            <h3 class="card-title">
                              <label for="email_id" class="text-info">Item Details</label>
                            </h3>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                      <table id="list" class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Services</th>
                            <th>Unit</th>
                            <th>Per unit charge</th>
                            <th>Consumed unit</th>
                            <th>Damaged </th>
                            <th>Penalty Charges</th>
                            <th>Penalty Cost</th>
                            <th>Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            $i = 1;

                            foreach ($asset as $key => $val) { ?>
                              <tr>
                                <td><?=$i++;?></td>

                                <td><?=$val['asset_name']?></td>

                                <td>
                                  <div class="form-group">
                                    <span class="badge bg-info"><?=$val['unit_label']?></span>
                                      <input type="hidden" class="form-control" name="asset_id[]"  id="asset_id" value="<?=$val['asset_id']?>">
                                    </div>
                                </td>

                                <td>  
                                  <div class="form-group unit_cost">
                                    <input type="text" class="form-control " name="asset_unit_cost[]" id="asset_unit_cost_<?=$val['asset_id']?>" value="<?=$val['asset_unit_cost']?>" >
                                    </div>
                                </td>

                                <td>
                                  <div class="form-group">
                                    <input type="number" class="form-control asset_used_unit" name="asset_used_unit[]" data-id="<?=$val['asset_id']?>" id="asset_used_unit_<?= $val['asset_id']?>"
                                    value="0">
                                  </div>
                                </td>

                                <td>
                                  <div class="form-group">
                                  <input type="text" class="form-control defected_services" name="defected_services[]" data-id="<?=$val['asset_id']?>" id="defected_services_<?= $val['asset_id']?>" value="0">
                                </div>
                                </td>

                                <td>
                                  <div class="form-group">
                                  <input type="text" class="form-control" name="penalty_charges[]" id="penalty_charges_<?= $val['asset_id']?>" value="<?=$val['penalty_charges']?>" readonly="true">
                                </div>
                                </td>

                                <td>
                                  <div class="form-group">
                                  <input type="text" class="form-control" name="penalty_cost[]" id="penalty_cost_<?= $val['asset_id']?>" value="0" readonly="true">
                                </div>
                                </td>

                                <td>
                                  <div class="form-group">
                                  <input type="text" class="form-control" name="cost[]" id="cost_<?= $val['asset_id']?>" value="0" readonly="true">
                                </div>
                                </td>

                              </tr>
                          <?php  }?>
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                       <div class="row center">
                         <div class="col-10">
                            <a href="<?= base_url()?>hall" class="btn btn-lg btn-info white">Cancel</a>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/hall.js"></script>
<script type="text/javascript">
</script>

<!-- page script -->
</body>
</html>
