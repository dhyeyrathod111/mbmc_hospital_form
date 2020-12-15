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
           <!--  <div class="card-header">
              <h3 class="card-title">Sku Price Details</h3>
            </div> -->
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-10">
                  <!-- <a type="button" onclick="changeStatus('1','1')" class="btn btn-block btn-danger">ADD</a> -->
                </div>
                <div class="col-2">
                    <a href="<?=base_url()?>hall-service/create" class="add-btn btn btn-block btn-info mb-2">ADD</a>
                </div>
              </div>
              
              <table id="hall_service_table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Service Id</th>
                  <th>Service Title</th>
                  <th>Sku Title</th>
                  <th>Unit Title</th>
                  <th>Service Unit cost</th>
                  <th>Penalty Charges</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                  <tr>
                  <th>Sr No</th>
                  <th>Service Id</th>
                  <th>Service Title</th>
                  <th>Sku Title</th>
                  <th>Unit Title</th>
                  <th>Service Unit cost</th>
                  <th>Penalty Charges</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </tfoot>
                <tbody>
                </tbody>
                 
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
    hall_service_table = $('#hall_service_table').DataTable({
      // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('hall-service/getlist'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false,
            
        }]
    }).draw();

    
  });

  function changeStatus(asset_id = null ,status = null) {
    // status_response(title,url);
    if(status == '1') {
      title = 'Are you Sure to deactived the hall service ?';
    } else {
      title = 'Are you Sure to actived the hall service ?';
    }

    url = base_url +'hall-service/update',
    data = {'asset_id':asset_id,'status':status};
    ele = hall_service_table;
    status_response(title,url,data,ele);

    // alert(role_id + ' '+ status);
    // $.ajax({
    //         type: 'POST',
    //         url: url,
    //         dataType: "Json",
    //         data:,
    //         success: function(res) {
    //             console.log(res.status);
    //             $('html,body').animate({scrollTop: 0});
    //             // $('#modal-add').modal('toggle');
    //             if(res.status =='1') {
    //                 $('.alert-success').show();
    //                 $('#alert-success').html(res.messg);
    //                 // $('.alert-success').hide('2000');
    //               price_table.draw();
    //             } else if(res.status =='2'){
    //                 $('.alert-danger').show();
    //                 $('#alert-danger').html(res.messg);
    //                 // $('.alert-danger').hide('2000');
    //             } 
    //         },
    //     });
  }
</script>
</body>
</html>
