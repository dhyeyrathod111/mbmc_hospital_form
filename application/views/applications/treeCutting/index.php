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
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <!-- <h3 class="card-title"><STRONG>Garden Section</STRONG></h3> -->
            </div>
            <!-- /.card-header -->
            <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
              <!-- Button Row -->

              <div class = "card">
                <div class = "card-body">
                  <div class = "row">
                    <div class = "col-md-4">
                      <label for = "from_date">From Date</label>
                      <input type="text" name="fromDate" id = "fromDate" class = "form-control datepicker" placeholder="Please Select Date">
                    </div>

                    <div class = "col-md-4">
                      <label for = "to_date">To Date</label>
                      <input type="text" name="toDate" id = "toDate" class = "form-control datepicker" placeholder="Please Select Date">
                    </div>    

                    <div class = "col-md-4">
                      
                    </div>
                  </div>
                  <br>
                  <div class = "row">
                    <div class = "col-md-4">
                      <label for="approval">Approval</label>
                      <select name="approval" id = "approval" class = "form-control">
                        <option value="0">All</option>
                        <option value="1">Active</option>
                        <option value="2">InActive</option>
                      </select>   
                    </div>
                    <div class = "col-md-4">
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
                    <div class = "col-md-2">
                      
                    </div>
                    <div class="col-md-2">
                      <a type="button" href="<?=base_url()?>garden/create" class="add-btn btn btn-block btn-info">Add</a>        
                    </div>
                  </div> 
                  
                </div>
              </div>
              
              <div class = "row">
              <table id="garden_table" class="table table-bordered table-hover">
                <thead>
                <tr class = "text-center header">
                  <th>Sr No</th>
                  <th>Form No</th>
                  <th>Applicant Name</th>
                  <th>Applicant Mobile</th>
                  <th>Applicant Email</th>
                  <!-- <th>Applicant Address</th> -->
                  <!-- <th>Survey No</th> -->
                  <!-- <th>City Survey No.</th> -->
                  <th>Ward No</th>
                  <!-- <th>Plot No</th> -->
                  <!-- <th>No. Of Trees In Premises</th> -->
                  <th>Permission Type</th>
                  <th>Ownership / Property Tax Receipt</th>
                  <th>BluePrint</th>
                  <th>Documents</th>
                  <!-- <th>Declatarion Of Garden Superintendent</th> -->
                  <th>Remarks</th>
                  <th>Approval Status</th>
									<th>Refunds</th>
                  <th>Status</th>
                  <th>Payment</th>
                  <th>Action </th>
                </tr>
                </thead>
                <!-- <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Form No</th>
                    <th>Applicant Name</th>
                    <th>Applicant Mobile</th>
                    <th>Applicant Email</th>
                    
                    <th>Ward No</th>
                    
                    <th>Noc Received</th>
                    <th>Documents</th>
                    
                    <th>Remarks</th>
                    <th>Approval Status</th>
                    <th>Status</th>
                    <th>Action </th>
                  </tr>
                </tfoot> -->
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

    <!-- Approval Modal -->
     <div class="modal fade" id="modal-approval">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Approval</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- <form class="remarks-form" id="remarks-form" role="form" method="POST"> -->
              <div class="modal-body">
                  <div class="card card-primary">
                      <div class="card-body">
                        <form role="form"  class="approvalForm" name = "approvalForm" id="approvalForm" method="post" enctype="multipart/form-data" >
                          <div class = "row">
                                <div class = 'col-2'></div>
                                <div class = "col-4 app_status">
                                  
                                </div>
															<div class = "col-4">
                                  <input type="hidden" name="complain_id_app" id = "complain_id_app">
                                  <textarea name="remarks" id = "remarks" class = "form-control" placeholder="Enter Remarks"></textarea>
                                </div>
                                <br>
                              <div class="col-12 appneddeposite" style = "margin-top: 12px;">
                                <table class = 'table table-bordered'>
																	<thead>
																		<th></th>
																		<th>No. Of Trees</th>
																		<th>Total</th>
																	</thead>
																	<tbody class = "depBody"></tbody>
																</table>
                              </div>
															
															<div class = "col-12 approvePassing text-center" style = "margin-top: 10px; font-weight: bold;">
																<div class="form-check-inline">
																	<label class="form-check-label">
																		<input type="radio" class="form-check-input" name="optradio" checked>Final Approve And Pass For Payment
																	</label>
																</div>
																<div class="form-check-inline">
																	<label class="form-check-label">
																		<input type="radio" class="form-check-input" name="optradio">Pass To Commissioner For Final Approval.
																	</label>
																</div>
															</div>
                              <div class = 'col-12'>
                                <br>
                                <center>
                                  <button type = "submit" class="btn btn-success approve" style = "cursor: pointer;">Submit</button>
                                  <!-- <span class="btn btn-primary reject" style = "cursor: pointer;">Reject</span> -->
                                </center>
                              </div>
                              <div class = "error" style = "display: none">
                                 
                              </div>  
                          </div>
                        </form>
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
    <!-- END Approval Modal -->

    <div class="modal fade" id="modal-docs">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Garden Data</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- <form class="remarks-form" id="remarks-form" role="form" method="POST"> -->
              <div class="modal-body">
                  <div class="card card-primary">
                      <div class="card-body">
                        <table id="docs-table" class="table table-bordered table-hover">
                        <thead>
                          <tr class = 'text-center'>
                            <th>SrNo</th>
														<th>Tree No.</th>
                            <th>Tree Name</th>
                            <th>Permission Type</th>
                            <th>No Of Trees</th>
														<th>Condition Status</th>
														<th>Reason Permission</th>
                            <th>Image</th>
                          </tr>
                        </thead>
                        <tbody id="docs-body">
                          
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

      <!-- Remarks -->
      <div class="modal fade" id="modal-remarks">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Remarks Data</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- <form class="remarks-form" id="remarks-form" role="form" method="POST"> -->
              <div class="modal-body">
                  <div class="card card-primary">
                      <div class="card-body">
                        <table id="docs-table" class="table table-bordered table-hover">
                        <thead>
                          <tr class = 'text-center'>
                            <th>SrNo</th>
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
      <!-- End Remarks -->

			<!-- Refunds Modal -->
			

			<div class="modal fade" id="modal-refunds">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Refunds Section</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- <form class="remarks-form" id="remarks-form" role="form" method="POST"> -->
              <div class="modal-body" style = "overflow: scroll;">
                        <table id="docs-table" class="table table-bordered table-hover">
													<thead>
														<tr class = 'text-center'>
															<th>SrNo</th>
															<th>Tree No</th>
															<th>Tree Name</th>
															<th>Premission Type</th>
															<th>No Of Trees</th>
															<th>Condition Type</th>
															<th>Reason For Permission</th>
															<th>Image</th>
                              <th>Payment</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody id="refunds-body">
														
													</tbody>
                        </table>
              </div>
              <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div> -->
            <!-- </form> -->
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

			<!-- End Refunds Modal -->

  </div>
  <!-- /.content-wrapper -->
   <?php $this->load->view('includes/footer');?>

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- DataTables -->
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/table2csv/src/table2csv.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/createComplain.js" id = "createComplain" is_payable = "<?= $is_payable[0]['payable_status']; ?>"></script>
<!-- page script -->
<script>


$(function () {
  let datatable = $('#garden_table').DataTable({
    "processing": true,
    "serverSide": true,
    "searchable": true,
    "serverMethod": "post",
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
    "ajax": {
      url: base_url + 'garden/getData',
      type: "POST",
      data: function(d){
        d.fromDate = $(document).find("#fromDate").val(),
        d.toDate = $(document).find("#toDate").val(),
        d.approval = $(document).find("#approval").val(),
        d.approval_status = $(document).find("#approval_status").val()
      }
    },
    "columns": [
      {data: 'sr_no'},
      {data: 'formNo'},
      {data: 'applicantName'},
      {data: 'mobile'},
      {data: 'email'},
      {data: 'wardNo'},
      {data: 'permission_type'},
      {data: 'ownership_permission'},
      {data: 'blueprint'},
      {data: 'docs'},
      {data: 'rem'},
			{data: 'app'},
			{data: 'refunds'},
      {data: 'status'},
      {data: 'payment'},
      {data: 'action'}
    ]
  });

  $(document).find(".dt-button").addClass('btn btn-info btn-sm');

  //Table Change section
    $(document).on('change', "#fromDate", function(){
      var toDate = $(document).find('#toDate').val();
      var fromDate = $(this).val();
      if(toDate != ''){
        if(fromDate < toDate){
          datatable.draw();
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
          datatable.draw();
        }else{
          swal("Warning!", "Please Select Correct To Date", 'warning');
        }
      }
    });

    $(document).on('change', "#approval", function(){
      datatable.draw();
    });

    $(document).on('change', "#approval_status", function(){
      datatable.draw();
    });
    //End

		var isPayable = "<?= $is_payable[0]['payable_status']; ?>";

		if(isPayable == '1'){
			$(".approvePassing").show();
		}else{
			$(".approvePassing").hide();
		}

});

</script>
</body>
</html>
