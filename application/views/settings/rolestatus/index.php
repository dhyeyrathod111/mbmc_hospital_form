<?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <!-- Content Wrapper. Contains page content -->
  <style>
    #roleStatusTable_wrapper {
      width: 100%;
    }
  </style>
  <div class="content-wrapper">
  	<section class = "content">
  		<div class="row">
  			<div class="col-12">
  				<div class="card">
  					<div class="card-body">
  						<div class="row justify-content-center">
  							<div class = "col-md-4">
  								<select class = "form-control" id = "department" name = "department">
  									<option value="0">Select Department</option>
  									<?php
  										foreach ($department as $key => $valDept) {
  											echo "<option value = '".$valDept['dept_id']."'>".$valDept['dept_title']."</option>";
  										}
  									?>
  								</select>
  							</div>
  							<div class = "col-md-4">
  								<select class = "form-control" id = "roles" name = "roles">
  									<option value="0">Select Roles</option>
  									<?php
  										foreach ($roles as $key => $valRoles) {
  											echo "<option value = '".$valRoles['role_id']."''>".$valRoles['role_title']."</option>";
  										}
  									?>
  								</select>
  							</div>
                <div class="col-md-4 text-center">
                  <a href="<?php echo base_url('rolestatus/create') ?>" class="btn btn-info">
                    Add
                  </a>
                </div>
  						</div>
  						<br>
  						<div class = "row justify-content-center">
  							<table class = 'table table-bordered table-hover' id = "roleStatusTable">
  								<thead>
  									<tr class = 'text-center header'>
  										<th>Sr No</th>
                      <th>Department</th>
                      <th>Role</th>
  										<th>Status Title</th>
  										<th>Action</th>
  									</tr>
  								</thead>
  								<tbody>
  								</tbody>
  							</table>
  						</div>
  					</div>
  				</div>	
  			</div>
  		</div>	
  	</section>
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

<script>
	$(document).ready(function(){
    $(function() {
      var dataTable = $("#roleStatusTable").DataTable({
        "processing": true,
              responsive: {
        breakpoints: [
            { name: 'desktop', width: Infinity },
            { name: 'tablet',  width: 1024 },
            { name: 'fablet',  width: 768 },
            { name: 'phone',   width: 480 }
        ]
    },
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
          url: base_url + 'rolestatus/getData',
          type: "POST",
          data: function(d){
            d.department = $(document).find("#department").val(),
            d.roles = $(document).find("#roles").val()
          }
        },
        "columns": [
          {data: 'sr_no'},
          {data: 'department'},
          {data: 'role'},
          {data: 'title'},
          {data: 'action'}
        ]
      });//End Ajax

      $(document).on('change', "#department", function(){
        var department = $(document).find('#department').val();
        
        if(department != '0'){
          
            dataTable.draw();
        }else{
          swal("Warning!", "Please Select Correct Department", 'warning');
        }
        
      });

      $(document).on('change', "#roles", function(){
        var roles = $(document).find('#roles').val();
        
        if(roles != '0'){
          
            dataTable.draw();
        }else{
          swal("Warning!", "Please Select Correct Roles", 'warning');
        }
        
      });

      $(document).on("click", ".delete", function(){

        if (confirm("Are you sure do you want to delete ?")) {
          var id = $(this).data("id");
          $.ajax({
            url: "<?= base_url() ?>rolestatus/delete",
            type:"POST",
            data: {'status_id': id},
            cache:false,
            async:false,
            success: function(data){
                 // console.log(data);
                 var data = $.parseJSON(data);
                  // console.log(data);return;
                if(data.success == '1'){

                  swal("Success", "Deleted Successfully", "success").then((willactive) => {
                    location.reload();
                  })

                }else{
                  swal("Error", "Failed", "error");
                } 
             }
          })
        }
      })
    })
	})
</script>