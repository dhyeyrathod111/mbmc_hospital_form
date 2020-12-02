<?php $this->load->view('includes/header'); ?>
<!-- Main Sidebar Container -->
<?php $this->load->view('includes/sidenav'); ?>
<style type="text/css">
    .modal-dialog {
        max-width: 1000px !important;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">

					<div class = 'card-header'>
						<div class = "row float-right">
							<a href = "<?= base_url().'pwd/create'?>" class = "btn btn-primary btn-md back">New Application</a>
						</div>
					</div>
					
                    <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
                        <div class="col-12">
							<div class = "card">
								<div class = "card-body">
									<div class = "row">
										<div class = "col-6">
											<label for = "from_date">From Date</label>
											<input type="text" name="fromDate" id = "fromDate" class = "form-control datepicker" placeholder="Please Select Date">
										</div> 
										<div class = "col-6">
											<label for = "to_date">To Date</label>
											<input type="text" name="toDate" id = "toDate" class = "form-control datepicker" placeholder="Please Select Date">
										</div>
									</div>
								</div>
							</div>
							<!-- <div class = "row"> -->
								<table class = "table table-bordered table-hover" id = "userPwd_table">
									<thead>
										<tr class = 'text-center'>
											<th>Sr No</th>
											<th>Application No</th>
											<th>Company Name</th>
											<th>Road Name</th>
											<th>Date Added</th>
											<th>Approval Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody class = "tableBodyPwd">
									</tbody>
								</table>
							</div>
                        <!-- </div> -->
                    </div>					
                </div>
            </div>
        </div>
    </section>
</div>
	<?php $this->load->view('includes/footer');?>
</div>

<div class="modal fade" id="jv_getrefno_modal_popup" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="width: 30%;">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLongTitle">Joint visit reference number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="js_ref_form" method="POST" action="<?= base_url('pwd/process_jv_refno') ?>">
	            <div class="modal-body">
	            	<div class="alert_message"></div>
                	<input type="number" class="form-control" name="js_ref_number" placeholder="Enter reference number" autocomplete="off">
                	<input type="hidden" id="ref_no_application_id" name="ref_no_application_id">
	            </div>
	            <div class="modal-footer d-block">
	                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	                <button type="submit" id="submit_btn" class="btn btn-primary float-right">Submit</button>
	            </div>
        	</form>
        </div>
    </div>
</div>

<div class="modal fade" id="extention_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Extention request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="extention_form" action="<?= base_url('pwd/extention_process') ?>">
                <div class="modal-body">
                	<div id="alert_message"></div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name_of_road">work start date<span class="red">*</span></label>
                                <input type="datetime" readonly class="form-control"  name="ext_date" id="ext_date" placeholder="Enter Date" autocomplete="off">
                            </div>
                        </div>
						 <div class="col-6">
                            <div class="form-group">
                                <label for="name_of_road">work to date<span class="red">*</span></label>
                                <input type="datetime" readonly class="form-control"  name="ext_to_date" id="ext_to_date" placeholder="Enter Work End Date" autocomplete="off">
                            </div>
                        </div>
						<div class="col-12">
                            <div class="form-group">
                                <label>Description<span class="red">*</span></label>
								<textarea name="description" style="height: auto !important;" class="form-control" rows="5	"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <input type="hidden" value="" id="app_ai_id" name="app_ai_id">
                    <button type="submit" id="submit_btn" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>

<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/applications.js" id = "createPwd" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>

<script>


$(document).on("click",".delete_user_application",Event => { Event.preventDefault();
	let applicant_id = Event.target.parentElement.getAttribute('application_id');
	swal({
	  title: "Are you sure do you want to delete this application?",
	  text: "Once deleted, you will not be able to recover this application file!",
	  icon: "warning",
	  buttons: true,
	  dangerMode: true,
	})
	.then((willDelete) => {
	  if (willDelete) {
	  	$.ajax({
	        type: "POST",
	        url: base_url + "pwd/app_delete_by_user",
	        data:{applicant_id:applicant_id},
	        success: response => {
	        	if (response.status) {
	        		location.reload();
	        	} else {
	        		swal(response.message);
	        	}
	        },
	        error: response => {
	        	swal("Oh noes!", "Sorry, we have to face some technical issues please try again later.", "error");console.log(response.responseText);
	        }
	    });
	  } 
	});
});


	$(function(){
		$("#ext_date").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
		$("#ext_to_date").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
		let userPwd_table = $("#userPwd_table").DataTable({
			"processing": true,
			"serverSide": true,
			"searchable": true,
			"serverMethod": "post",
			"ajax": {
				url: base_url+'pwd/getUserApplicationList',
				type: "POST",
				data: function(d){
					d.fromDate = $(document).find("#fromDate").val(),
					d.toDate = $(document).find("#toDate").val()
				}
			},
			"columnDefs": [
                {
                    "targets": [6,5],
                    "orderable": false,
                },
            ],
            "order" : [[ 4,"desc" ]],
			"columns": [
				{data: 'sr_no'},
				{data: 'application_no'},
				{data: 'company_name'},
				{data: 'road_name'},
				// {data: 'amount'},
				{data: 'data_added'},
				{data: 'approval_status'},
				{data: 'action'}
			],
			initComplete:settings => {
				$('.deleted_application_class').each((index , oneFields , Event)=>{
					oneFields.parentElement.parentElement.style = "background-color: red;"
				});
			}
		});

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
				userPwd_table.draw();
				}else{
				swal("Warning!", "Please Select Correct From Date", 'warning');
				}
			}
		});
	})
</script>
