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
            <h1>Create Tree</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add New Tree</li>
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
              <h3 class="card-title">Add New Tree</h3>
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
                  <form role="form" class="treeCuttingform" id="treeCuttingform"  method="post" enctype="multipart/form-data">

                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="form_no"><span>Tree Name</span><span class="red">*</span></label>
                            <input type="text" name="treeName" id = "treeName" class = "form-control" placeholder="Enter Tree Name" required="">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <br>
                            <span class = "btn btn-md btn-primary" id = "addTree" style = "margin-top: 10px;">Submit</span>
                          </div>
                        </div>
                      </div>
                    </div> 
                  </form>

                  <table class = "table">
                    <thead>
                      <tr class = "text-center">
                        <th>Sr.No</th>
                        <th>Tree Name</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $srNo = 1;
                        foreach ($treeData as $treekey => $treeValue) {
                      ?>
                            <tr class = "text-center">
                              <input type="hidden" name="treeId" id = "treeId" value = "<?= $treeValue['tree_id']?>">
                              <input type="hidden" name="actualStatus" id = "actualStatus" value = "<?= $treeValue['status']; ?>">
                              <td><?= $srNo; ?></td>
                              <td><?= $treeValue['treeName']; ?></td>
                              <td><?php
                                  if($treeValue['status'] == 1){
                                    echo "<span class = 'btn btn-success btn-small treeStatus' style = 'cursor: pointer'>Active</span>";
                                  }else{
                                    echo "<span class = 'btn btn-danger btn-small treeStatus' style = 'cursor: pointer'>InActive</span>";
                                  }
                              ?>
                              </td>
                              <td>
                                <?php
                                if($treeValue['status'] == 1){
                                 ?>
                                  <span style = "font-size: 25px; cursor: pointer" class = "editTree">
                                    <i class="fas fa-edit"></i>
                                  </span>
                                 <?php 
                                }
                                ?>

                                <span style = "font-size: 25px; cursor: pointer" class = "deleteTree">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </td>
                            </tr>
                      <?php    
                          $srNo++;                        
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
            <h4 class="modal-title">Edit Tree</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body">
                <div class="card card-primary">
                    <div class="card-body">
                      <div class="form-group">
                        <label for="road_type">Tree Name<span class="red">*</span></label>
                        <input type="hidden" name="treeId_edit" id = "treeId_edit">
                        <input type="text" name="treeName_edit" class = "form-control" id = "treeName_edit" placeholder="Enter Tree Name">
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
    $("#addTree").click(function(){
      var treeName = $("#treeName").val();
      var pattern = /^[a-zA-Z\s]+$/;
      var status = true;

      if(!pattern.test(treeName) && treeName == ''){
          status = false;
          return false;
      }

      if(status && treeName != ''){
        
        $.ajax({
          url: "<?= base_url() ?>garden/treeSubmit",
          type: "POST",
          dataType: "json",
          data: {'treeName': treeName},
          async: true,
          success: function(res){
            // console.log(res);
            if(res.success == '1'){
              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("Tree Created SuccessFully");

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
    });//End Add Tree

    $(".deleteTree").click(function(){
      var treeId = $(this).parent().parent().find("#treeId").val();

      if(treeId != ''){
        
        $.ajax({
          url: "<?= base_url(); ?>garden/deleteTree",
          type: "POST",
          dataType: "json",
          data: {'treeId': treeId},
          // async: true,
          success: function(res){
            if(res.success == '1'){
              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("Tree Deleted SuccessFully");

              setTimeout(function(){
                $(".alert-success").css({'display':'none'});
                location.reload(true);
              },2000);
            }else{
              $(".alert-danger").css({'display':'block'});
              $(".alert-danger").find("#alert-danger").text("Tree Deletion Failed");

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

    $(".treeStatus").click(function(){
      var treeId = $(this).parent().parent().find('#treeId').val();
      var actualStatus = $(this).parent().parent().find('#actualStatus').val();
      
      if(treeId != ''){
        
        $.ajax({
          url: "<?= base_url(); ?>garden/deactivateTree",
          type: "POST",
          dataType: "json",
          data: {'treeId': treeId, 'actualStatus': actualStatus},
          // async: true,
          success: function(res){
            if(res.success == '1'){
              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("Tree Deleted SuccessFully");

              setTimeout(function(){
                $(".alert-success").css({'display':'none'});
                location.reload(true);
              },2000);
            }else{
              $(".alert-danger").css({'display':'block'});
              $(".alert-danger").find("#alert-danger").text("Tree Deletion Failed");

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
    });//Change Tree Status

    $(".editTree").click(function(){
      var treeId = $(this).parent().parent().find('#treeId').val();

      $(document).find("#treeId_edit").val(treeId);

      $('#modal-status').modal('show');
    });//open edit model

    $(".save").click(function(){
      var treeNameEdit = $("#treeName_edit").val();
      var treeIdEdit = $(this).parent().parent().find("#treeId_edit").val();
      var pattern = /^[a-zA-Z\s]+$/;
      var status = true;

      if(!pattern.test(treeNameEdit) && treeNameEdit == ''){
          status = false;
          return false;
      }

      if(status){

        $.ajax({
          url: "<?= base_url() ?>garden/treeEdit",
          type: "POST",
          dataType: "json",
          data: {'treeName': treeNameEdit, 'treeId': treeIdEdit},
          async: true,
          success: function(res){
            // console.log(res);
            if(res.success == '1'){
              $(".alert-success-edit").css({'display':'block'});
              $(".alert-success-edit").find("#alert-success").text("Tree Edited SuccessFully");

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
  });//End ready function
</script>
<!-- page script -->
</body>
</html>