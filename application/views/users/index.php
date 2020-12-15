  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users Master</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users </li>
            </ol>
          </div>
        </div>
      </div><!- - /.container-fluid - ->
    </section>
 -->
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
                    <a href="<?=base_url()?>users/add"  class="add-btn btn btn-block btn-info mb-2">ADD</a>
                </div>
              </div>

              <div class="modal fade" id="modal-add">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Add User</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form id="register-form" class="form" role="form">
                      <div class="modal-body">
                          <div class="card card-primary">
                              <div class="card-body">
                                <div class="form-group">
                                  <label for="dept_title">User name</label>
                                  <input type="text" name="user_name"  id="user_name" class="form-control" placeholder="User name">
                                </div>
                                <div class="form-group">
                                  <label for="dept_title">Email Id</label>
                                  <input type="email" name="email_id" id="email_id" class="form-control" placeholder="Email Id">
                                </div>
                                <div class="form-group">
                                  <label for="role_id" class="text-info">Select Role</label>
                                  <select class="selectpicker form-control" id="role_id" name="role_id" data-live-search="true">
                                    <option value="">---Select Role---</option>
                                    <?php
                                    // echo'<pre>';print_r($roles);exit;
                                      foreach ($roles as $key => $val) {
                                        // echo'<pre>';print_r($val['role_id']);exit;
                                        echo '<option value="'.$val['role_id'].'">'.$val['role_title'].'</option>';
                                      }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="dept_id" class="text-info">Select Department</label>
                                  <select class="selectpicker form-control" id="dept_id" name="dept_id" data-live-search="true">
                                    <option value="">---Select Department---</option>
                                    <?php
                                    // echo'<pre>';print_r($roles);exit;
                                      foreach ($department as $key => $dept) {
                                        // echo'<pre>';print_r($val['role_id']);exit;
                                        echo '<option value="'.$dept['dept_id'].'">'.$dept['dept_title'].'</option>';
                                      }
                                    ?>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="user_mobile" class="text-info">Mobile No</label>
                                  <input type="number" name="user_mobile" id="user_mobile" class="form-control" placeholder="Mobile No">
                                </div>
                                <div class="form-group">
                                  <label for="password" class="text-info">Password</label>
                                  <input type="password" name="password" id="password" class="form-control" placeholder="Password">
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

              <table id="users_table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>User Id</th>
                  <th>User Name</th>
                  <th>User Email</th>
                  <th>User Mobile</th>
                  <th>Role</th>
                  <th>Department</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                 <tbody>
                </tbody>
                <tfoot>
                <tr>
                  <th>Sr No</th>
                  <th>User Id</th>
                  <th>User Name</th>
                  <th>User Email</th>
                  <th>User Mobile</th>
                  <th>Role</th>
                  <th>Department</th>
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
    users_table = $('#users_table').DataTable({
      // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('users/getlist'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false,
            
        }]
    }).draw();

    $( "#register-form" ).validate({
    rules: {
      
      user_name: "required",
      role_id: "required",

      email_id: {
          required: true,
          email: true,

      },

      user_mobile: {
          required: true,
          maxlength: 10,
          // maxlength: 10
      },

      password: "required",

    },
    messages: {
      email_id: "Please Provide email Id",
      user_mobile: {
        required: "Please Provide Mobile No.",
        maxlength: "Your Mobile No. must be of 10 digits"
      },
      user_name: "Please Provide user name",
      password: "Please Provide password",
      role_id: "Please Select role",
    },

    errorPlacement: function ( error, element ) {
      console.log(error);
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
          url: base_url +'addusers',
          dataType: "Json",
          data:$('#register-form').serialize(),
          success: function(res) {
              console.log(res.messg);
              $('#modal-add').modal('toggle');
              if(res.status =='1') {
                $('.alert-success').show();
                $('#alert-success').html(res.messg);
                setInterval( function () {
                    window.location = base_url + 'users';
                }, 5000 );
                // $('.alert-success').hide('2000');
                users_table.draw();
              } else if(res.status =='2'){
                $('.alert-danger').show();
                $('#alert-danger').html(res.messg);
                // $('.alert-danger').hide('2000');
              }  
          },
      });
      return false;
    }
  });
  });

  function changeStatus(obj) {

    user_id = $(obj).attr('data-user');
    status = $(obj).attr('data-status');

    if(status == '1') {
      title = 'Are you Sure you want to deactived the user ?';
    } else {
      title = 'Are you sure you want to activate the user ?';
    }

    url = base_url +'users/update';
    data = {'user_id':user_id,'status':status};
    ele = users_table;
    
    status_response(title,url,data,ele);
    
  }
</script>
</body>
</html>
