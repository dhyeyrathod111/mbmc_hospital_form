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
                  <h1>Create Licence Type</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Add Licence Type</li>
                  </ol>
                </div>
              </div>
              <h3 class="card-title">Edit Licence Type</h3>
            </div>
            <!-- <div class="row alertdiv">
              <div class="col-12">
                <div class="card-body">
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p id="alert-danger"></p>
                    
                  </div>
                  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <p id="alert-success"></p>
                  </div>
                </div>
              </div>
            </div> -->

            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <!-- /.card-header -->
                  <!-- form start -->


                    <div class="card-body">
                      <div class="row">
                        
                          <div class = "col-4">
                            <label for="lic_name"><span>Licence Name Edit</span><span class="red">*</span></label>
                            <input type="hidden" name="licId_edit" id = "licId_edit" value = "<?= $licType[0]['lic_type_id']; ?>">
                            <input type="text" name="licName_edit" id = "licName_edit" class = "form-control" placeholder="Enter Licence Name" value = "<?= $licType[0]['lic_name']; ?>" required="">
                          </div>  
                      </div>
                          
                    </div>

                    <div class = "card-footer">
                      <center>
                        <span class = "btn btn-lg btn-info white cancelEdit" id = "cancelEdit" style = "margin-top: 10px;cursor:pointer;">Cancel</span>
                        <span class = "btn btn-lg btn-primary right save" id = "save" style = "margin-top: 10px;cursor:pointer;">Submit</span>
                      </center>
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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/tempLic.js"></script>
  
</script>

<!-- page script -->
</body>
</html>  