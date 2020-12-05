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
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
              <div class="row">
                <div class="col-10">
                  <!-- <a type="button" onclick="changeStatus('1','1')" class="btn btn-block btn-danger">ADD</a> -->
                </div>
                
              </div>
               <!-- Button Row -->
               <div class="card">
                <div class="card-body">
                    <div class = "row">
                      <div class = "col-3">
                        <label for = "from_date">From Date</label>
                        <input type="text" name="fromDate" id = "fromDate" class = "form-control datepicker" placeholder="Please Select Date">
                      </div>
                      <div class = "col-3">
                        <label for = "to_date">To Date</label>
                        <input type="text" name="toDate" id = "toDate" class = "form-control datepicker" placeholder="Please Select Date">
                      </div>
                      <!-- <div class = "col-3">
                        <label for="approval">Approval</label>
                        
                        <select name="approval" id = "approval" class = "form-control">
                          <option value="0">All</option>
                          <option value="1">Active</option>
                          <option value="2">Deactive</option>
                        </select>
                      </div> -->
                      <div class = "col-4">
                        <label for = "approval_status">Approval Status</label>
                        <select name="approval_status" class = "form-control" id = "approval_status">
                          <option value="0">All</option>
                          <?php
                            foreach($appStatus as $keyVal => $valApp){
                              echo  "<option value='".$valApp['status_id']."'>".$valApp['status_title']."</option>";
                            }
                          ?>
                        </select>
                      </div>
                      <div class="col-2">
                        <a type="button" href="<?=base_url()?>lab/create" class="add-btn btn btn-block btn-info">ADD</a>
                      </div>
                    </div>
                 </div>
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
                                <div class="form-group">
                                  <label for="road_type">Status<span class="red">*</span></label>
                                  <select class="selectpicker form-control" id="status" name="status" data-live-search="true">
                                    <option value="">---Select Status---</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="remarks">Remarks</label>
                                  <input type="hidden" id="hospital_id" name="hospital_id">
                                  <input type="hidden" id="dept_id" name="dept_id">
                                  <input type="hidden" id="sub_dept_id" name="sub_dept_id">
                                  <input type="hidden" id="app_id" name="app_id">
                                  <textarea class="form-control" id="remarks" name ="remarks" placeholder="Enter the Remarks Title"></textarea> 
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

              <table id="lab_table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Application No</th>
                  <th>Applicant Name</th>
                  <th>Applicant Email Id</th>
                  <!-- <th>Applicant Mobile No</th>
                  <th>Applicant Qualification</th> -->
                  <th>Lab Name</th>
                 <!--  <th>Immunization</th>
                  <th>Immunization Details</th>
                  <th>Bio Waste validity</th> -->
                  <!-- <th>Documents</th> -->
                  <th>Remarks</th>
                  <th>Status</th>
                  <th>Action </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                  <th>Sr No</th>
                  <th>Application No</th>
                  <th>Applicant Name</th>
                  <th>Applicant Email Id</th>
                  <!-- <th>Applicant Mobile No</th>
                  <th>Applicant Qualification</th> -->
                  <th>Lab Name</th>
                 <!--  <th>Immunization</th>
                  <th>Immunization Details</th>
                  <th>Bio Waste validity</th> -->
                  <!-- <th>Documents</th> -->
                  <th>Remarks</th>
                  <th>Status</th>
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
   <?php $this->load->view('includes/footer');?>

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/lab.js"></script>
<!-- page script -->
<script>
  $(function () {
    lab_table = $('#lab_table').DataTable({
      // Processing indicator
      "processing": true,
      // DataTables server-side processing mode
      "serverSide": true,
      // Initial no order.
      "order": [],
      // Load data from an Ajax source
      "ajax": {
        url: "<?php echo base_url('lab/getlist'); ?>",
        type: "POST",
        data: function(d){
          d.fromDate = $(document).find("#fromDate").val(),
          d.toDate = $(document).find("#toDate").val(),
          d.approval = $(document).find("#approval").val(),
          d.approval_status = $(document).find("#approval_status").val()
        }

      },
      //Set column definition initialisation properties
      "columnDefs": [{ 
          "targets": [0],
          "orderable": false,
          
      }]

    });

    $(document).on('change', "#fromDate", function(){
      var toDate = $(document).find('#toDate').val();
      var fromDate = $(this).val();
      if(toDate != ''){
        if(fromDate < toDate){
          lab_table.draw();
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
          lab_table.draw();
        }else{
          swal("Warning!", "Please Select Correct From Date", 'warning');
        }
      }
    });

    $(document).on('change', "#approval", function(){
      lab_table.draw();
    });

    $(document).on('change', "#approval_status", function(){
      lab_table .draw();
    });
      
  });

</script>
</body>
</html>
