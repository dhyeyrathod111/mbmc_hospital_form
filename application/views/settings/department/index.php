  

  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <style type="text/css">
    .custom-head{
      margin: -4% 0% 0% -12% !important;
    }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
   <!--  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Department Master</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Department</li>
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
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row">
                <div class="col-10">
                    <!-- <a type="button" onclick="changeStatus('1','1')" class="btn btn-block btn-danger">ADD</a> -->
                </div>
                <div class="col-2">
                    <a href= "<?=base_url()?>dept/create" class="btn btn-block btn-info mb-2">ADD</a>
                </div>
              </div>
              <table id="dept_table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Department Id</th>
                  <th>Department Title</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
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

    <div class="modal fade bd-example-modal-lg" id="dept_user_present" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLabel">
                        You can not change department status.
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body user_list">
                    
                </div>
            </div>
        </div>
    </div>


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
    dept_table = $('#dept_table').DataTable({
      // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('dept/getlist'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false,
            
        }]
    }).draw();
  });
  


    $(document).on("click",".btn_chnage_dept",function(event) {
        event.preventDefault();
        let user_id = $.isNumeric($(this).attr('user_id')) ? $(this).attr('user_id') : '';
        let new_dept_id = $.isNumeric($(this).closest("tr").find('select').val()) ? $(this).closest("tr").find('select').val() : '';
        if (user_id != '' && new_dept_id  != '') {
            $.ajax({
                type: "POST",
                url: base_url + '/change_user_dept',
                data: {user_id:user_id,new_dept_id:new_dept_id},
                success: responce => {
                    if (responce.status == true) {
                        notify_success(responce.message);
                        $('.user-tr-'+responce.user_id).remove();
                        if ($('#user_list_table tr').length == 1) {
                            $('#dept_user_present').modal('hide');
                        }
                    } else {
                        notify_error(responce.message);    
                    }
                },
                error: responce => {
                    notify_error();
                }
            });
        } else {
            notify_error('Please select any department befor submit.');
        }
    });


    function check_user_present(dept_id = null ,status = null) {
        if (status == 1) {
            $.ajax({
                type: "POST",
                url: base_url + '/user_exist_in_dept',
                data: {dept_id:dept_id,status:status},
                success: responce => {  
                    if (responce.status === true) {
                        $('.user_list').html(responce.html_str);
                        $('#dept_user_present').modal('show');
                    } else {
                        change_status(responce.perms.dept_id,responce.perms.status);
                    }
                },
            });
        } else {
            change_status(dept_id,status);
        }
    }

    function change_status(dept_id = null ,status = null) {
        if(status == '1') {
            title = 'Are you Sure you want to deactivate the department ?';
        } else {
            title = 'Are you Sure you want to activate the department ?';
        }
        url = base_url +'dept/update';
        data = {'dept_id':dept_id,'status':status};
        ele = dept_table;
        status_response(title,url,data,ele);
    }


    function notify_success(message) {

        let html_str = '<div class="alert alert-info text-center" role="alert">'+ message +'</div>';
        $('#alert_message').show();
        $('#alert_message').html(html_str);
        setTimeout(function(){ $('#alert_message').hide() }, 3000);
    }
    function notify_error(message = '') {
        if (message === '') {
            message = "Sorry, we have to face some technical issues please try again later."
        } 
        let html_str = '<div class="alert alert-warning text-center" role="alert">'+ message +'</div>';
        $('#alert_message').show();
        $('#alert_message').html(html_str);
        setTimeout(function(){ $('#alert_message').hide() }, 3000);
    }
</script>
</body>
</html>
