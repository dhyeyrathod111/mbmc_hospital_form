<?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<section class = "content">
  		<div class="row">
  			<div class="col-12">
  				<div class="card">
  					<div class="card-body">
  						<form role="form"  class="roleStatusForm" name = "roleStatusForm" id="roleStatusForm" method="post" enctype="multipart/form-data" >
  						<div class="row justify-content-center">
  							<div class = "col-4">
  								<!-- department -->
  								<select class = "form-control" id = "department" name = "department">
  									<option value="0">Select Department</option>
  									<?php
  										foreach ($department as $key => $valDept) {
  											echo "<option value = '".$valDept['dept_id']."'>".$valDept['dept_title']."</option>";
  										}
  									?>
  								</select>
  							</div>
  							<div class = "col-4">
  								<!-- roles -->
  								<select class = "form-control" id = "roles" name = "roles">
  									<option value="0">Select Roles</option>
  									<?php
  										foreach ($roles as $key => $valRoles) {
  											echo "<option value = '".$valRoles['role_id']."''>".$valRoles['role_title']."</option>";
  										}
  									?>
  								</select>
  							</div>
  						</div>
  						<br>
  						<div class = "row justify-content-end">
  							<span class = "btn btn-md btn-primary add">Add</span>
  						</div>
  						<br>
  						<div class = "row justify-content-center">
  							<table class = 'table table-bordered table-hover' id = "roleStatusTable">
  								<thead>
  									<tr class = 'text-center header'>
  										<th>Sr No</th>
  										<th>Status Title</th>
                      <th>is rejected</th>
  										<th>Action</th>
  									</tr>
  								</thead>
  								<tbody class = "tableBody">
                    <tr class = "text-center">
                      <td>1</td>
                      <td><input type = 'text' name = 'status_title[]' id = 'status_title_1' class = 'form-control' placeholder = 'Enter Status Title (tip: Please Enter word Approved and Reject in Title)' required></td>
                      <td><input type="checkbox" class="form-control is_rejected_ckeckbox" value=""></td>
                      <td><span style = 'font-size: 25px; cursor:pointer' class = 'delete'><i class='fas fa-trash'></i></span></td>
                    </tr>
  								</tbody>
  							</table>
  						</div>
  						<br>
  						<div class = "row justify-content-center">
  							<button type="submit" class = "btn btn-md btn-primary">submit</button>
  						</div>	
  					</div>
  					</form>
  				</div>	
  			</div>
  		</div>	
  	</section>
  </div>
  
  <?php $this->load->view('includes/footer');?>
</div>

<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>

<script>
	$(document).ready(function(){
		var srNo = 2;
		$(".add").click(function(){
			var row = "<tr class = 'text-center'><td>"+srNo+"</td><td><input type = 'text' name = 'status_title[]' id = 'status_title_"+srNo+"' class = 'form-control' placeholder = 'Enter Status Title (Tip: Please Enter Approved And Reject Word in Title)' required></td> <td><input type='checkbox' class='form-control is_rejected_ckeckbox' value=''></td> <td><span style = 'font-size: 25px; cursor:pointer' class = 'delete'><i class='fas fa-trash'></i></span></td></tr>";
			srNo++;
			$(".tableBody").append(row);
		});

		$(document).on("click", ".delete", function(){
			$(this).parent().parent().remove();
		});

		$(document).on("submit", "#roleStatusForm", function(e){
			e.preventDefault();

			var status = true;

      $("input[name='status_title[]']").each(function(){
        if($(this).val() == ''){
          status = false;
        }
      });

      if(status){
        var formData = new FormData(document.getElementById("roleStatusForm")); 

        let checked_array = [];
        $('.is_rejected_ckeckbox').each(function () { 
          if (this.checked) {
            checked_array.push(true)
          } else {
            checked_array.push(false)
          }
        });

        formData.append('is_rejected',checked_array);

        $.ajax({
          url: "<?= base_url() ?>rolestatus/submit",
          type:"post",
          data: formData,
          processData:false,
          contentType:false,
          cache:false,
          async:false,
          success: function(data){
            var data = $.parseJSON(data);
            if(data.success == '1'){
              swal("Success", "Status Added Successfully", "success").then((willactive) => {
                location.reload();
              });
            }else{
              swal("Error", "Some Error Occured Please Try Again", "error");
            }
          }
        });

      }else{
        sweet_alert("Warning!",'Please Enter All Fields',"warning");
      }
		})
	})
</script>