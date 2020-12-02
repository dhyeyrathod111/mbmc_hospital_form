  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users Apllication Table</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users Apllication Table</li>
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
              <h3 class="card-title">Apllication Details</h3>
            </div>

            <div class="row alertdiv">
              <div class="col-12">
                <div class="card-body">
                  <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                    <p id="alert-danger"></p>
                    
                  </div>
                  <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    <p id="alert-success"></p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-10">
                    <!-- <a type="button" onclick="changeStatus('1','1')" class="btn btn-block btn-danger">ADD</a> -->
                </div>
                <div class="col-2">
                    <a type="button" data-toggle="modal" data-target="#modal-add" class="btn btn-block btn-info">ADD</a>
                </div>
              </div>

              <div class="modal fade" id="modal-add">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Add Role</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form class="role-form" id="role-form" role="form">
                      <div class="modal-body">
                          <div class="card card-primary">
                              <div class="card-body">
                                <div class="form-group">
                                  <label for="exampleInputPassword1">Role</label>
                                  <input type="text" class="form-control" id="role_title" name ="role_title" placeholder="Enter the role">
                                </div>
                              </div>
                           
                          </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                    </form>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
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

<!-- DataTables -->
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<!-- page script -->
<script>
  function changeStatus(role_id = null ,status = null) {
    // alert(role_id + ' '+ status);
    $.ajax({
            type: 'POST',
            url: base_url +'updateRole',
            dataType: "Json",
            data:{'role_id':role_id,'status':status},
            success: function(res) {
                console.log(res.status);
                $('html,body').animate({scrollTop: 0});
                // $('#modal-add').modal('toggle');
                if(res.status =='1') {
                    $('.alert-success').show();
                    $('#alert-success').html(res.messg);
                    // $('.alert-success').hide('2000');
                  role_table.draw();
                } else if(res.status =='2'){
                    $('.alert-danger').show();
                    $('#alert-danger').html(res.messg);
                    // $('.alert-danger').hide('2000');
                } 
            },
        });
  }
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
            "url": "<?php echo base_url('/getlist'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false,
            
        }]
    }).draw();

    // setInterval( function () {
    //     role_table.ajax.reload();
    // }, 3000 );

    $( "#role-form" ).validate({
      rules: {
        
          role_title: {
              required: true,
          },
      },
      messages: {
        role_title: "Please provide role title.",
      },

      errorPlacement: function ( error, element ) {
        console.log(element);
        error.addClass( "ui red pointing label transition" );
        error.insertAfter( element.after() );
      },

      invalidHandler: function(event, validator) {
        // 'this' refers to the form
        var errors = validator.numberOfInvalids();
        console.log(errors);
        if(errors) {
            var message = errors == 1
            ? 'You missed 1 field. It has been highlighted'
            : 'You missed ' + errors + ' fields. They have been highlighted';
            $("div.error span").html(message);
            $("div.error").show();
        } else {
            $("div.error").hide();
        }
      },
      submitHandler: function(form,e) {
        e.preventDefault();
        console.log('Form submitted');
        $.ajax({
            type: 'POST',
            url: base_url +'saveRole',
            dataType: "Json",
            data:$('#role-form').serialize(),
            success: function(res) {
                console.log(res.status);

                $('#modal-add').modal('toggle');
                if(res.status =='1') {
                    $('.alert-success').show();
                    $('#alert-success').html(res.messg);
                    // $('.alert-success').hide('2000');
                  role_table.draw();
                } else if(res.status =='2'){
                    $('.alert-danger').show();
                    $('#alert-danger').html(res.messg);
                    // $('.alert-danger').hide('2000');
                } 
            },
        });
        // return false;
      }
    });
  });
</script>
</body>
</html>
