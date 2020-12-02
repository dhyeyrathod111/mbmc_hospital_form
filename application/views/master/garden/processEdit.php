  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1>Edit</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Edit Process</li>
                  </ol>
                </div>
              </div>
              <h3 class="card-title">Edit</h3>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <!-- /.card-header -->
                  <!-- form start -->


                    <div class="card-body">
                      <div class="row">
                        
                          <div class = "col-4"></div>
                          <div class = "col-4">
                            <label for="lic_name"><span>Process Name Edit</span><span class="red">*</span></label>
                            <input type="hidden" name="processid_edit" id = "processid_edit" value = "<?= $processName[0]['processId']; ?>">
                            <input type="text" name="processName_edit" id = "processName_edit" class = "form-control" placeholder="Enter Process Name" value = "<?= $processName[0]['processName']; ?>" required="">
                          </div>  
                          <div class = "col-4"></div>
                          <div class = "col-4"></div>
                          <div class = "col-4">
                            <center>
                              <span class = "btn btn-md btn-primary saveProcess" id = "saveProcess" style = "margin-top: 10px;cursor:pointer;">Add</span>
                              <a href = "<?= base_url() ?>garden/addProcess" class = "btn btn-md btn-danger cancelEdit" id = "cancelEdit" style = "margin-top: 10px;cursor:pointer;">Cancel</a>
                            </center>      
                          </div>
                          <div class = "col-4"></div>
                       </div>
                    </div> 

                </div>
              </div>
            </div>        
          </div>
        </div>
      </div>
    </section>

    </div>      
     <?php $this->load->view('includes/footer');?>
</div>    
<!-- DataTables -->
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/createComplain.js"></script>
  
</script>

<!-- page script -->
</body>
</html>  