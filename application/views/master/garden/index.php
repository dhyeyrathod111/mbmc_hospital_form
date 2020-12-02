  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>

  <style>
    .odd {
      text-align: center;
    }

    .even{
      text-align: center;
    }  
  </style>
  <div class="content-wrapper">
    <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Tree</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add New Tree</li>
            </ol>
          </div>
        </div>
      </div>
    </section> -->

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

            <div class="card-body">
              <div class="row">
                <div class="col-10"></div>
                <div class="col-2">
                    <a href="<?=base_url()?>garden/crTreeType" class="add-btn btn btn-block btn-info">ADD</a>
                </div>
              </div>

              <table id="tree_type_table" class="table table-bordered table-hover">
                <thead>
                  <tr class = "text-center">
                    <th>Sr.No</th>
                    <th>Tree Name</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr class = "text-center">
                    <th>Sr.No</th>
                    <th>Tree Name</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>

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

<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<!-- page script -->
<script>
  
  $(function () {
    var tree_type_table = $('#tree_type_table').DataTable({
      // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('garden/getTreeName'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false,
            
        }]
    }).draw();
  });

  $(document).on('click','.treeStatus',function(){
    var id = $(this).data('id');
    var status = $(this).data('status');
    changeStatus(id, status);
  });

  function changeStatus(tree_id = null ,status = null) {
    // status_response(title,url);
    if(status == '1') {
      title = 'Are you Sure to deactived the Tree Name ?';
    } else {
      title = 'Are you Sure to actived Tree Name ?';
    }

    url = base_url +'garden/deactivateTree',
    data = {'treeId':tree_id,'actualStatus':status};
    ele = tree_type_table;
    status_response(title,url,data,ele);
  }

</script>