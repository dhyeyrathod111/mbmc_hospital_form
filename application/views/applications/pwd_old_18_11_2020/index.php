  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <style type="text/css">
    .modal-dialog {
      max-width: 1000px !important;
    }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>PWD Applications</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">PWD Applications</li>
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
              <h3 class="card-title">Application Details</h3>
            </div> -->
            <!-- <div class="row alertdiv">
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
            </div> -->

            <!-- /.card-header -->
            <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
              <div class="row">
                <div class="col-10">
                  <!-- <a type="button" onclick="changeStatus('1','1')" class="btn btn-block btn-danger">ADD</a> -->
                </div>
                <div class="col-2">
                    
                </div>
              </div>
               <!-- Button Row -->
               <div class="card">
                <!-- <div class="card-header">
                  <h3 class="card-title">
                    <label for="email_id" class="text-info">Custom Search Filters</label>
                  </h3>
                </div> -->
                 <div class="card-body">
                    <div class = "row">
                      <div class="col-12">
                        <div class="row">
                          <div class = "col-4">
                            <label for = "from_date">From Date</label>
                            <input type="text" autocomplete="off" name="fromDate" id = "fromDate" class = "form-control datepicker" placeholder="Please Select Date">
                          </div>
                          <div class = "col-4">
                            <label for = "to_date">To Date</label>
                            <input type="text" autocomplete="off" name="toDate" id = "toDate" class = "form-control datepicker" placeholder="Please Select Date">
                          </div>
                          
                          <div class = "col-4">
                            <label for = "approval_status">Approval Status</label>
                            <select name="approval_status" class = "form-control" id = "approval_status">
                              <option selected value="new">New</option>
                              <!-- <option value="0">All</option> -->
                              <option value="2">Approved</option>
                              <option value="3">Rejected</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      
                      <!-- <div class="col-2 right">
                        <a type="button" href="<?=base_url()?>pwd/create" class="add-btn btn btn-block btn-info">ADD</a>
                      </div> -->

                      <!-- <div class = "col-3">
                        <label for="approval">Approval</label>
                        
                        <select name="approval" id = "approval" class = "form-control">
                          <option value="0">All</option>
                          <option value="1">Active</option>
                          <option value="2">Deactive</option>
                        </select>
                      </div> -->
                      
                      
                    </div>
                 </div>
               </div>
              
              

              <div class="modal fade" id="modal-remarks">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Remarks History</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <!-- <form class="remarks-form" id="remarks-form" role="form" method="POST"> -->
                      <div class="modal-body">
                          <div class="card card-primary">
                              <div class="card-body">
                                <table id="remarks-table" class="table table-bordered table-hover">
                                <thead>
                                  <tr>
                                    <th>Remarks Id</th>
                                    <th>Remarks</th>
                                    <th>Remarks By</th>
                                    <th>Remarks Date</th>
                                  </tr>
                                </thead>
                                <tbody id="remarks-body">
                                  
                                </tbody>
                                </table>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> -->
                        <!-- <button type="submit" class="btn btn-primary">Save</button> -->
                      </div>
                    <!-- </form> -->
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

              <!-- doc modal -->
              <div class="modal fade" id="modal-doc">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Documents List</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <!-- <form class="remarks-form" id="remarks-form" role="form" method="POST"> -->
                      <div class="modal-body">
                          <div class="card card-primary">
                              <div class="card-body">
                                <table id="remarks-table" class="table table-bordered table-hover">
                                <thead>
                                  <tr>
                                    <th>Sr No.</th>
                                    <th>Document Name</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody id="doc-body">
                                  
                                </tbody>
                                </table>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer justify-content-between"></div>
                    <!-- </form> -->
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>

              <!-- modal for status and remarks update -->
              <div class="modal fade" id="modal-status">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title">Add Remarks</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form class="remarks-form" id="remarks-form" role="form" method="POST">
                      <div class="modal-body">
                          <div class="card card-primary">
                              <div class="card-body">
                                <div class="text-center text-danger">
                                  <h5 style="font-weight:bold;" id="remark_alert"></h5>
                                </div>
                                <div class="form-group">
                                  <label for="road_type">Status<span class="red">*</span></label>
                                  <select class="selectpicker form-control" id="status" name="status" data-live-search="true">
                                    <option value="">---Select Status---</option>
                                  </select>
                                </div>

                                <div class="form-group">
                                  <label for="road_type">Ward select<span class="red">*</span></label>
                                  <select class="selectpicker form-control" id="ward_selectre" name="ward_select" data-live-search="true">
                                    <option value="">---Select Ward---</option>
                                  </select>
                                </div>

                                <input type="hidden" id="role_id" name="role_id">


                                <div class="form-group">
                                  <label for="remarks">Remarks</label>
                                  <input type="hidden" id="pwd_id" name="pwd_id">
                                  <input type="hidden" id="dept_id" name="dept_id">
                                  <input type="hidden" id="app_id" name="app_id">
                                  <input type="hidden" id="ward_id" name="ward_id">
                                  <textarea class="form-control" id="remarks" name ="remarks" placeholder="Enter the Remarks Title"></textarea> 
                                </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary remark_save_btn">Save</button>
                        
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <table id="pwd_table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Application No</th>
                  <th>Applicant Name</th>
                  <th>Applicant Email Id</th>
                  <th>Applicant Mobile No</th>
                  <!-- <th>Applicant Alternate Mobile No</th> -->
                  <!-- <th>Applicant Address</th> -->
                  <!-- <th>Letter No</th> -->
                  <!-- <th>Letter Date</th> -->
                  <!-- <th>Company Name </th> -->
                  <!-- <th>Landline No</th> -->
                  <!-- <th>Contact Person</th> -->
                  <!-- <th>Road Name</th>
                  <th>Road Type</th>
                  <th>Start Point</th>
                  <th>End Point</th>
                  <th>Total Length</th> -->
                  <th>Refrence number</th>
                  <th>Company Name</th>
                  <th>Days of work</th>
                  <th>Generate Letter</th>
                  <th>Remarks</th>
                  <th>Status</th>
                  <th>Date Added</th>
                  <th>Action </th>
                </tr>
                </thead>
                
                
                  <th>Sr No</th>
                  <th>Application No</th>
                  <th>Applicant Name</th>
                  <th>Applicant Email Id</th>
                  <th>Applicant Mobile No</th>
                  <!-- <th>Applicant Alternate Mobile No</th> -->
                  <!-- <th>Applicant Address</th> -->
                  <!-- <th>Letter No</th> -->
                  <!-- <th>Letter Date</th> -->
                  <!-- <th>Company Name </th> -->
                  <!-- <th>Landline No</th> -->
                  <!-- <th>Contact Person</th> -->
                  <!--  <th>Road Name</th>
                  <th>Road Type</th>
                  <th>Start Point</th>
                  <th>End Point</th>
                  <th>Total Length</th> -->
                  <th>Refrence number</th>
                  <th>Company Name</th>
                  <th>Days of work</th>
                  <th>Generate Letter</th>
                  <th>Remarks</th>
                  <th>Status</th>
                  <th>Date Added</th>
                  <th>Action </th>
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


    <div class="modal fade" id="joint_visit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Joint visit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="joint_visit_form" action="<?= base_url('pwd/joint_visit_process') ?>">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name_of_road">Expanded Length<span class="red">*</span></label>
                                    <input type="number" class="form-control hasDatepicker" name="jv_length" id="jv_length" placeholder="Enter lenght" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name_of_road">Meeting Date<span class="red">*</span></label>
                                    <input type="datetime" readonly class="form-control"  name="jv_date" id="jv_date" placeholder="Enter Date" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-12 text-center font-weight-bold text-danger">
                                <p id="joint_visit_remark_alert"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-center" style="justify-content: center;">
                        <input type="hidden" value="" id="jv_model_app_id" name="app_id">
                        <button type="submit" id="joint_visit_submit_button" class="btn btn-primary">Send joint visit letter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

	<div class="modal fade" id="extention_approvel_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">                
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Extention</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <!-- <div id="alert_message"></div> -->
                        <h6 class="font-weight-bold">Date:</h6>
                        <p id="approvel_date_selecter"></p>
                        <h6 class="font-weight-bold">Remark:</h6>
                        <p id="approvel_remark_selecter"></p>
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <input type="hidden" id="extention_id" name="extention_id">
                    <input type="hidden" id="pwd_app_id" name="pwd_app_id">
                    <button type="button" class="btn btn-primary" id="extention_reject_btn">Reject</button>
                    <button type="button" id="extention_approve_btn" class="btn btn-primary float-right">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="payment_verification_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">                
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">payment verification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="alert_message_pv"></div>
                <div class="modal-body" id="paymnet_verification_body">
                    
                </div>
                <div class="modal-footer d-block">
                    <button type="button" is_approve="0" class="pv_action btn btn-primary">Reject</button>
                    <button type="button" is_approve="1" class="pv_action btn btn-primary float-right">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="refrence_order_popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="width: 50%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Refrence Application (create refrence number.)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url('pwd/create_refrence_number') ?>" id="refrence_order_form">
                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="file_close_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="width: 50%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Close application</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center success_close_app_text">
                    <h3>Are you sure?</h3>
                    <h5>Do you want to really close this application. once it close it will not reactivated.</h5>
                </div>
                <div class="modal-footer d-block">
                    <button type="button" class="btn btn-danger">cancel</button>
                    <button type="button" class="btn btn-success float-right file_close_selecter">Close application</button>
                </div>
            </div>
        </div>
    </div>



    <input type="hidden" id="closer_id_holder">
   <?php $this->load->view('includes/footer');?>

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/applications.js" id = "createPwd" is_user = "<?= $this->authorised_user['user_id'] ?>"></script>
<!-- page script -->
<script>
  $(function () {

    pwd_table = $('#pwd_table').DataTable({
      dom: 'lBfrtip',
      buttons: [
        {
          text: 'Export Csv',
          action: function ( e, dt, node, config ) {
            $(".dataTable").table2csv({
              seperator: ',',
              newline: '\n',
              quoteFields:true,
              excludeColumns:'',
              excludeRows:'',
              trimContent:true
            });
          }
        }
      ],
      // Processing indicator
      // Processing indicator
      "processing": true,
       responsive: {
        breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]
      },
      // DataTables server-side processing mode
      "serverSide": true,
      rowCallback: function( row, data ) {
        if (data[13] != ' ') {
          $('td', row).css( "background", "#7fff00" );
        }
      },
      // Initial no order.
      "order": [['1',"desc"]],
      // Load data from an Ajax source
      "ajax": {
          url: "<?php echo base_url('pwd/getlist'); ?>",
          type: "POST",
          data: function(d){
            d.fromDate = $(document).find("#fromDate").val(),
            d.toDate = $(document).find("#toDate").val(),
            d.approval = $(document).find("#approval").val(),
            d.approval_status = $(document).find("#approval_status").val()
          }
      },
      //Set column definition initialisation properties
        columnDefs: [ {
          'targets': [0,8], /* column index [0,1,2,3]*/
          'orderable': false, /* true or false */
      }],
    }); 

    $(document).find(".dt-button").addClass('btn btn-info btn-sm');
    
    $(document).on('change', "#fromDate", function(){
      var toDate = $(document).find('#toDate').val();
      var fromDate = $(this).val();
      if(toDate != ''){
        if(fromDate < toDate){
          pwd_table.draw();
        }else{
          swal("Warning!", "Please Select Correct To Date", 'warning');
        }
      }
    });

    $(document).on('change', "#toDate", function(){
      var fromDate = $(document).find("#fromDate").val();
      var toDate = $(this).val();

      if(fromDate != ''){
        if(fromDate < toDate){
          pwd_table.draw();
        }else{
          swal("Warning!", "Please Select Correct From Date", 'warning');
        }
      }
    });

    $(document).on('change', "#approval", function(){
      pwd_table.draw();
    });

    $(document).on('change', "#approval_status", function(){
      pwd_table.draw();
    });

    setTimeout(()=>{
		$('.extention_approval_selecter').parent().parent().css("background", "#008ae6");
    },500);

  });
</script>
</body>
</html>
