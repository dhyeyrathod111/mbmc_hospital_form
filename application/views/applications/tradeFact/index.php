   <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <style type="text/css">
    .modal-dialog {
      max-width: 1000px !important;
    }
  </style>
  <div class="content-wrapper">  
   	
 	<section class = "content">

 		<div class="row">
 			<div class = "col-12">
 				<div class = "card">
 					<div class="card-header">
            <!-- <div class="row mb-2"> -->
                <!-- <div class="col-sm-6">
                  <h1>Trade Factory license Section</h1>
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Trade Factory license Section</li>
                  </ol>
                </div>
              </div> -->
		          <!-- <h3 class="card-title"><strong>TradeFactory Licence Section</strong></h3> -->
		      </div>

          <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
            <!-- Button Row -->
            <div class = "card">
              <div class = "card-body">
                <div class = "row">
                  <div class = "col-4">
                    <label for = "from_date">From Date</label>
                    <input type="text" name="fromDate" id = "fromDate" class = "form-control datepicker" placeholder="Please Select Date">
                  </div>
                  <div class = "col-4">
                    <label for = "to_date">To Date</label>
                    <input type="text" name="toDate" id = "toDate" class = "form-control datepicker" placeholder="Please Select Date">
                  </div>
                  <div class = "col-4">
                    
                  </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-4">
                      <label for="approval">Approval</label>
                      <select name="approval" id = "approval" class = "form-control">
                        <option value="0">All</option>
                        <option value="1">Active</option>
                        <option value="2">InActive</option>
                      </select>
                    </div>
                    <div class="col-4">
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

                    </div>
                    <div class="col-2">
                      <br>
                        <a type="button" href="<?=base_url()?>tradefactlic/create" class="btn btn-block btn-info">Add</a>
                    </div>
                </div>

              </div>
            </div>
          	<!-- Row End -->
          	<br>

          	<table id="tradeFacApp_table" class="table table-bordered table-hover" style = "width: 100%">
          		<thead>
          				<tr class = "text-center">
          					<th>Sr NO</th>
          					<th>Form No</th>
                    <th>name</th>
          					<!-- <th>shop no</th> -->
          					<!-- <th>address</th> -->
          					<!-- <th>Property no</th> -->
          					<th>Shop Name</th>
          					<th>Type Of Business</th>
          					<th>New_Renewal</th>
          					<!-- <th>Existing No</th> -->
          					<!-- <th>Property type</th> -->
          					<!-- <th>Property Date</th> -->
          					<!-- <th>Aadhar No</th> -->
                    <!-- <th>Pan No</th> -->
                    <!-- <th>No Obj Date</th> -->
                    <!-- <th>Food License Date</th> -->
                    <!-- <th>Property Tax date</th> -->
                    <!-- <th>Establishment Date</th> -->
                    <!-- <th>Date Assurance</th> -->
                    <th>Status</th>
                    <th>Action</th>
          				</tr>	
          			</thead>
                <tfoot>
                  <tr class = "text-center">
                    <th>Sr NO</th>
                    <th>Form No</th>
                    <th>name</th>
                    <!-- <th>shop no</th> -->
                    <!-- <th>address</th> -->
                    <!-- <th>Property no</th> -->
                    <th>Shop Name</th>
                    <th>Type Of Business</th>
                    <th>New_Renewal</th>
                    <!-- <th>Existing No</th> -->
                    <!-- <th>Property type</th> -->
                    <!-- <th>Property Date</th> -->
                    <!-- <th>Aadhar No</th> -->
                    <!-- <th>Pan No</th> -->
                    <!-- <th>No Obj Date</th> -->
                    <!-- <th>Food License Date</th> -->
                    <!-- <th>Property Tax date</th> -->
                    <!-- <th>Establishment Date</th> -->
                    <!-- <th>Date Assurance</th> -->
                    <th>Status</th>
                    <th>Action</th>
                  </tr> 
                </tfoot>
          			<tbody>
          			</tbody>
          	</table>
          	<!-- END Table -->
          </div>
          <!-- End Card body -->
 				</div> 
 				<!-- Card End -->
 			</div>
 			<!-- col12 end -->
 		</div>
 		<!-- End Row -->
 	</section>

  <!-- Modal for remarks -->
  <div class="modal fade" id="modal-remarks">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Remarks Data</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
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
        
          </div>
        </div>
        <!-- /.modal-content -->
       </div>
        <!-- /.modal-dialog -->
  </div>
  <!-- End For remarks -->

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

</div>
<!-- ./wrapper -->
<?php $this->load->view('includes/footer');?>
</div>

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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/tradeFacLic.js"></script>
<!-- page script -->
<script>


$(function () {

  var dataTable = $('#tradeFacApp_table').DataTable({
    "processing": true,
    "serverSide": true,
    "searchable": true,
    'serverMethod': 'post',
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
      url: base_url + 'tradefactlic/getData',
      type: "POST",
      data: function(d){
        d.fromDate = $(document).find("#fromDate").val(),
        d.toDate = $(document).find("#toDate").val(),
        d.approval = $(document).find("#approval").val(),
        d.approval_status = $(document).find("#approval_status").val()
      }
    },
    "columns": [
      { data: 'sr_no'},
      { data: 'form_no'},
      { data: 'name'},
      { data: 'shop_name'},
      { data: 'type_of_business'},
      { data: 'new_renewal'},
      { data: 'status'},
      { data: 'action'},
    ]
  });

  $(document).find(".dt-button").addClass('btn btn-info btn-sm');

  //Table Change section
    $(document).on('change', "#fromDate", function(){
      var toDate = $(document).find('#toDate').val();
      var fromDate = $(this).val();
      if(toDate != ''){
        if(fromDate < toDate){
          dataTable.draw();
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
          dataTable.draw();
        }else{
          swal("Warning!", "Please Select Correct From Date", 'warning');
        }
      }
    });
 
    $(document).on('change', "#approval", function(){
      dataTable.draw();
    });

    $(document).on('change', "#approval_status", function(){
      dataTable.draw();
    });
    //End

});  


</script>
</body>
</html>