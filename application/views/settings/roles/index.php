  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <!--  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Roles Master</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Roles</li>
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
            <!-- <div class="card-header">
              <h3 class="card-title">Roles Details</h3>
            </div> -->

            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-10">
                    <!-- <a type="button" onclick="changeStatus('1','1')" class="btn btn-block btn-danger">ADD</a> -->
                </div>
                <div class="col-2">
                    <a href="<?=base_url()?>role/create"  class="add-btn btn btn-block btn-info">ADD</a>
                </div>
              </div>
              
              <table id="role_table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Role Id</th>
                  <th>Role Title</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                  <th>Sr No</th>
                  <th>Role Id</th>
                  <th>Role Title</th>
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

<!-- page script -->
<script>
  $(function () {
    role_table = $('#role_table').DataTable({
      // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('role/getlist'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false,
            
        }]
    }).draw();
  });

   function changeStatus(role_id = null ,status = null) {
    if(status == '1') {
      title = 'Are you sure you want to Deactivate the role ?';
    } else {
      title = 'Are you sure you want to activate the role ?';
    }

    url = base_url +'role/update';
    data = {'role_id':role_id,'status':status};
    ele = role_table;
    
    status_response(title,url,data,ele);
  }
</script>
</body>
</html>
