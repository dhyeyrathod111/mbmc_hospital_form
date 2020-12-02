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
            <h1>Create Tree Cutting Process</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add New Process</li>
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
            <div class="card-header">
              <h3 class="card-title">Add New Process</h3>
            </div>
            <div class="row alertdiv">
              <div class="col-12">
                <div class="card-body">
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!-- <h5><i class="icon fas fa-ban"></i> Alert!</h5> -->
                    <p id="alert-danger"></p>
                    
                  </div>
                  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <!-- <h5><i class="icon fas fa-check"></i> Alert!</h5> -->
                    <p id="alert-success"></p>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" class="addProcess" id="addProcess" method="post" enctype="multipart/form-data">

                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="form_no"><span>Process Name</span><span class="red">*</span></label>
                            <input type="text" name="processName" class = "form-control" id = "processName" placeholder="Enter Process Name">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <br>
                            <span class = "btn btn-md btn-primary submit" style = "margin-top: 10px;">Submit</button>
                          </div>
                        </div>
                      </div>
                    </div>        
                  </form>

                  <table class = "table">
                    <thead>
                      <tr class = "text-center">
                        <th>Sr.No</th>
                        <th>Process Name</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $srNo = 1;
                        if(!empty($processData))
                        {
                        foreach ($processData as $keyprocess => $processValue) {
                      ?>
                          <tr class = "text-center">
                            <input type="hidden" name="processId" id = "processId" value = "<?= $processValue['processId']; ?>">
                            <input type="hidden" name="actualStatus" id = "actualStatus" value = "<?= $processValue['status']; ?>">
                            <td><?= $srNo; ?></td>
                            <td><?= $processValue['processName'] ?></td>
                            <td>
                              <?php
                                if($processValue['status'] == '1'){
                                   echo "<span class = 'btn btn-success btn-small processStatus' style = 'cursor: pointer'>Active</span>"; 
                                }else{
                                  echo "<span class = 'btn btn-danger btn-small processStatus' style = 'cursor: pointer'>InActive</span>";
                                }
                              ?>
                            </td>
                            <td>
                              <?php
                                if($processValue['status'] == 1){
                                 ?>
                                  <span style = "font-size: 25px; cursor: pointer" class = "editProcess">
                                    <i class="fas fa-edit"></i>
                                  </span>
                                 <?php 
                                }
                                ?>
                              <span style = "font-size: 25px;cursor: pointer" class = "deleteProcess">
                                <i class="fas fa-trash"></i>
                              </span>
                            </td>
                          </tr>
                      <?php    
                        $srNo++;
                        }
                       }else{
                        echo "<tr class = 'text-center'><td colspan = '4'>No Data Found</td></tr>";
                       } 
                      ?>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>        
          </div>
        </div>
      </div>
    </section>

      <div class="modal fade" id="modal-status">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Process</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
                <div class="card card-primary">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="road_type">Process Name<span class="red">*</span></label>
                        <input type="hidden" name="processId_edit" id = "processId_edit">
                        <input type="text" name="processName_edit" class = "form-control" id = "processName_edit" placeholder="Enter Process Name">
                      </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button class="btn btn-primary save">Save</button>
              
            </div>
            <div class = "row">
              <div class="alert alert-danger-edit alert-dismissible" style = "display: none">
                <!-- <h5><i class="icon fas fa-ban"></i> Alert!</h5> -->
                <p id="alert-danger"></p>
                
              </div>
              <div class="alert alert-success-edit alert-dismissible" style = "display: none;">
                <!-- <h5><i class="icon fas fa-check"></i> Alert!</h5> -->
                <p id="alert-success"></p>
              </div>
            </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/applications.js"></script>
  
</script>

<script>
  $(document).ready(function(){
    $(".submit").click(function(){
      var processName = $("#processName").val();
      var pattern = /^[a-zA-Z\s]+$/;
      var status = true;

      if(!pattern.test(processName) && processName == ''){
          status = false;
          return false;
      }

      if(status){
        $.ajax({
          url: "<?= base_url() ?>garden/processSubmit",
          type: "POST",
          dataType: "json",
          data: {'processName': processName},
          async: true,
          success: function(res){
            // console.log(res);
            if(res.success == '1'){
              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("Process Created SuccessFully");

              setTimeout(function(){
                $(".alert-success").css({'display':'none'});
                location.reload(true);
              },2000);
              
            }else{
              $(".alert-danger").css({'display':'block'});
              $(".alert-danger").find("#alert-danger").text("Some Error Occured Please Try Again");

              setTimeout(function(){
                $(".alert-danger").css({'display':'none'});
              },1000);
            }
          },
        });
      }else{
        $(".alert-danger").css({'display':'block'});
        $(".alert-danger").find("#alert-danger").text("Please Enter Proper Value");

        setTimeout(function(){
          $(".alert-danger").css({'display':'none'});
        },1000);
      }
    });

    $(".deleteProcess").click(function(){
      var processId = $(this).parent().parent().find("#processId").val();

      if(processId != ''){
        alert(processId);
        $.ajax({
          url: "<?= base_url(); ?>garden/processDelete",
          type: "POST",
          dataType: "json",
          data: {'processId': processId},
          // async: true,
          success: function(res){
            if(res.success == '1'){
              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("Process Deleted SuccessFully");

              setTimeout(function(){
                $(".alert-success").css({'display':'none'});
                location.reload(true);
              },2000);
            }else{
              $(".alert-danger").css({'display':'block'});
              $(".alert-danger").find("#alert-danger").text("Process Deletion Failed");

              setTimeout(function(){
                $(".alert-danger").css({'display':'none'});
              },1000);
            }
          }
        });

      }else{
        $(".alert-danger").css({'display':'block'});
        $(".alert-danger").find("#alert-danger").text("Some Error Occured");

        setTimeout(function(){
          $(".alert-danger").css({'display':'none'});
        },1000);
      }
    });//End Delete Tree

    $(".processStatus").click(function(){
      var processId = $(this).parent().parent().find('#processId').val();
      var actualStatus = $(this).parent().parent().find('#actualStatus').val();
      if(processId != ''){
        
        $.ajax({
          url: "<?= base_url(); ?>garden/processdeactivate",
          type: "POST",
          dataType: "json",
          data: {'processId': processId, 'actualStatus': actualStatus},
          async: true,
          success: function(res){
            if(res.success == '1'){
              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("Status Changed SuccessFully");

              setTimeout(function(){
                $(".alert-success").css({'display':'none'});
                location.reload(true);
              },2000);
            }else{
              $(".alert-danger").css({'display':'block'});
              $(".alert-danger").find("#alert-danger").text("Process Status Changing Failed");

              setTimeout(function(){
                $(".alert-danger").css({'display':'none'});
              },1000);
            }
          }
        });

      }else{
        $(".alert-danger").css({'display':'block'});
        $(".alert-danger").find("#alert-danger").text("Some Error Occured");

        setTimeout(function(){
          $(".alert-danger").css({'display':'none'});
        },1000);
      }
    });

    $(".editProcess").click(function(){
      var processId = $(this).parent().parent().find('#processId').val();

      $(document).find("#processId_edit").val(processId);

      $('#modal-status').modal('show');
    });//open edit model

    $(".save").click(function(){
      var processNameEdit = $("#processName_edit").val();
      var processIdEdit = $(this).parent().parent().find("#processId_edit").val();
      var pattern = /^[a-zA-Z\s]+$/;
      var status = true;

      if(!pattern.test(processNameEdit) && processNameEdit == ''){
          status = false;
          return false;
      }

      if(status){

        $.ajax({
          url: "<?= base_url() ?>garden/processEdits",
          type: "POST",
          dataType: "json",
          data: {'processName': processNameEdit, 'processId': processIdEdit},
          async: true,
          success: function(res){
            // console.log(res);
            if(res.success == '1'){
              $(".alert-success-edit").css({'display':'block'});
              $(".alert-success-edit").find("#alert-success").text("Process Edited SuccessFully");

              setTimeout(function(){
                $(".alert-success-edit").css({'display':'none'});
                location.reload(true);
              },2000);
              
            }else{
              $(".alert-danger-edit").css({'display':'block'});
              $(".alert-danger-edit").find("#alert-danger").text("Some Error Occured Please Try Again");

              setTimeout(function(){
                $(".alert-danger-edit").css({'display':'none'});
              },1000);
            }
          },
        });

      }else{
        $(".alert-danger-edit").css({'display':'block'});
        $(".alert-danger-edit").find("#alert-danger").text("Please Enter Proper Value");

        setTimeout(function(){
          $(".alert-danger-edit").css({'display':'none'});
        },1000);
      }

    });

  });
</script>

<!-- page script -->
</body>
</html>