  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-10">
                   
                </div>
                <div class="col-2">
                    <a href="<?=base_url()?>status/create" class="add-btn btn btn-block btn-info">ADD</a>
                </div>
              </div>

              <table id="status_table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Status Id</th>
                  <th>Status Title</th>
                  <th>Department</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                  <th>Sr No</th>
                  <th>Status Id</th>
                  <th>Status Title</th>
                  <th>Department</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
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

<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<!-- page script -->
<script>
  
  $(function () {
    status_table = $('#status_table').DataTable({
      // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('status/getlist'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false,
            
        }]
    }).draw();
  });

  function changeStatus(status_id = null , status = null) {
    // alert(role_id + ' '+ status);
    if(status == '1') {
      title = 'Are you sure you want to Deactivate the app status ?';
    } else {
      title = 'Are you sure you want to activate the app status ?';
    }

    url = base_url +'status/update';
    data = {'status_id':status_id,'status':status};
    ele = status_table;
    
    status_response(title,url,data,ele);
  }
</script>
</body>
</html>
