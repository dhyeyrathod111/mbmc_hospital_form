   <?php $this->load->view('includes/header'); ?>
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
  					<div class="card-header"></div>
  					<div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
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
				                    <label for="approval">Approval</label>
					                 <select name="approval" id = "approval" class = "form-control">
					                    <option value="0">All</option>
					                    <option value="1">Approved</option>
					                    <option value="2">Rejected</option>
					                 </select>
				                  </div>
				                </div>

				                <br>

				                <!-- <div class = "row justify-content-end">
				                	<div class="col-2">
					                    <a type="button" href="<?=base_url()?>templic/createLic" class="add-btn btn btn-block btn-info">Add</a>
					                </div>
				                </div> -->

  							</div>
  						</div>

  						<br>

  						<table id="reports_table" class="table table-bordered table-hover" style = "width: 100%">
  							<thead>
  								<tr class = "text-center">
  									<th>Sr NO</th>
  									<th>Application Id</th>
  									<th>Department</th>
  									<th>Final Approval</th>
  									<th>Remarks</th>
  									<th>Status</th>
  									<th>Approved Date</th>
  									<th>Action</th>
  								</tr>
  							</thead>
  						</table>	
  					</div>
  				</div>
  			</div>
  		</div>
  	</section>

  	<div class="modal fade" id="modal-payment">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Payment</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>	
            <form id = "paymentForm" method="post" accept-charset="utf-8">
            	<div class="modal-body">
            		<div class="card card-primary">
            			<div class="card-body">
            				<div class="row justify-content-center">
            					<div class = "col-3">
            						<input type="hidden" name="rowId" id = "rowId" class = "form-control">
            						<div class="form-group">
            							<label for="paymentType">Payment Type<span class="red">*</span></label>
            							<select name = "paymentType" id = "paymentType" class = "form-control" required="">
            								<option value = "0">Select PaymentType</option>
            								<option value = "1">Cash</option>
            								<option value = "2">Online Payment</option>
            							</select>
            						</div>
            					</div>
            					<div class = "col-3">
            						<div class="form-group">
            							<label for="amount">Amount<span class="red">*</span></label>
            							<input type = "number" min = "0" step="0.01" name = "amount" id = "amount" class = "form-control" placeholder = "Enter Amount">
            						</div>
            					</div>	
            				</div>
            			</div>
            			<div class = "card-footer">
            				<div class = "row justify-content-center">
            					<button type="button" class="btn btn-danger" data-dismiss="modal" style = "margin-right: 10px;">Cancel</button>
        						<button type = "submit" class="btn btn-primary">Save</button>
            				</div>
            			</div>
            		</div>
            	</div>
            </form>
          </div>
        </div>
    </div>        

  </div>

  <?php $this->load->view('includes/footer');?>
</div>

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
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/reports.js"></script>

<script>
	$(function () {
		
		var datatable = $('#reports_table').DataTable({
			"processing": true,
		    "serverSide": true,
		    "searchable": true,
		    'serverMethod': 'post',
		    "ajax": {
		      url: base_url + 'reports/getData',
		      type: "POST",
		      data: function(d){
		        d.fromDate = $(document).find("#fromDate").val(),
		        d.toDate = $(document).find("#toDate").val(),
		        d.approval = $(document).find("#approval").val()
		      }
		    },
		    "columns": [
      			{ data: 'sr_no'},
      			{ data: 'application_id'},
      			{ data: 'department'},
      			{ data: 'final_approval'},
      			{ data: 'remarks'},
      			{ data: 'status'},
      			{ data: 'approved_date'},
      			{ data: 'action'},
      		]	
		});

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
	          swal("Warning!", "Please Select Correct From Date", 'warning');
	        }
	      }
	    });

	    $(document).on('change', "#approval", function(){
	      datatable.draw();
	    });
	});
</script>