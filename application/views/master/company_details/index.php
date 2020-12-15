  <?php $this->load->view('includes/header'); ?>
  <?php $this->load->view('includes/sidenav'); ?>

  <style>
    .odd {
      text-align: center;
    }

    .even{
      text-align: center;
    }  
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
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
                    <a href="<?=base_url()?>company_details/create" class="add-btn btn btn-block btn-info mb-2">ADD</a>
                </div>
              </div>
              <table id="comapany_details_table" class="table table-bordered table-hover">
                <thead>
                <tr class = "text-center">
                  <th>Sr.No</th>
                  <th>Company Name</th>
									<th>Date Added</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                  <tr class = "text-center">
                  <th>Sr.No</th>
                  <th>Company Name</th>
                  <th>Date Added</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </tfoot>
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

		<!-- Address list Modal -->
		<div class="modal" id="addressListmodal">
  		<div class="modal-dialog">
    		<div class="modal-content">

      		<!-- Modal Header -->
					<div class="modal-header">
						<h4 class="modal-title">Address List</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					<!-- Modal body -->
					<div class="modal-body">
						
								<table id="docs-table" class="table table-bordered table-hover">
									<thead>
										<tr class = 'text-center'>
											<th>SrNo</th>
											<th>Company Addresses</th>
										</tr>
									</thead>
									<tbody id="address-body"></tbody>
								</table>
								
					</div>

					<!-- Modal footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>

				</div>
			</div>
		</div>
		<!-- End -->

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
<!-- page script -->
<script>var base_url = "<?= base_url(); ?>";</script>
<script src="<?php echo base_url()?>assets/custom/js/company_details.js"></script>
<script>
  
  $(function () {
    var company_detials_table = $('#comapany_details_table').DataTable({
      // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
            "url": "<?php echo base_url('company_details/getData'); ?>",
            "type": "POST"
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0],
            "orderable": false,
            
        }]
    }).draw();

    
  });

  $(document).on('click','.advStatus',function(){
    var id = $(this).data('id');
    var status = $(this).data('status');
    changeStatus(id, status);
  });

  function changeStatus(adv_type_Id = null ,status = null) {
    // status_response(title,url);
    if(status == '1') {
      title = 'Are you Sure to deactived the Adv type ?';
    } else {
      title = 'Are you Sure to actived Adv type ?';
    }

    url = base_url +'advertisement/deactivateAdv',
    data = {'adv_type_Id':adv_type_Id,'actualStatus':status};
    ele = adv_type_table;
    status_response(title,url,data,ele);
  }
</script>
</body>
</html>
