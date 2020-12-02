  <?php $this->load->view('includes/header'); ?>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>/assets/on_off/css/on-off-switch.css">
  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>

  

  <div class="content-wrapper">
  	<section class="content">
      <div class="row">
        <div class="col-12">
          <form id="permission-form" class="form" role="form">	
           <div class = "card" style = "height: 100%;">
            <div class = "card-body">	
	        	<div class = "card" style = "margin-top: 20px;">
	        		<div class = "card-header">
	        			<h3>User Details</h3>
	        		</div>
	        		<div class = "card-body">
	        		  <div class = "row">	
	        			<div class = "col-4">
	        				<label for="user_department">User Department</label>
	        				<select name="userDepartment" id = "userDepartment" class = "form-control" required="">
	        					<option value="0">Select Department</option>
	        					<?php
	        						foreach($usersDepartment as $keyDept => $valDept){
	        							echo "<option value = '".$valDept['dept_id']."'>".$valDept['dept_title']."</option>";
	        						}
	        					?>
	        				</select>
	        			</div>
	        			<div class = "col-4">
	        				<label for="user_department">User Role</label>
	        				<select name="userRole" id = "userRole" class = "form-control selectpicker">
	        					<option value="0">Select Role</option>
	        				</select>
	        			</div>
	        		  </div>	
	        		</div>
	        	</div>
	        	<br>
	        	<!-- <div class = "card">
	        		<div class = "card-body"> -->
	        			<table class = "table table-striped" style = "width: 400px; margin: 0 auto;margin-top: 25px;margin-bottom: 25px;">
	        				<tbody>
		    					<!-- <tr class = "text-center">
		    						<td><h3>Index</h3></td>
		    						<td><input type="hidden" name = "index" id="on-off-switch-notext" value="0"></td>
		    					</tr> -->
		    					<tr class = "text-center">
		    						<td><h3>Create</h3></td>
		    						<td><input type="hidden" name = "create" id="on-off-switch-notext1" value="0"></td>
		    					</tr>
		    					<tr class = "text-center">
		    						<td><h3>Edit</h3></td>
		    						<td><input type="hidden" name = "edit" id="on-off-switch-notext2" value="0"></td>
		    					</tr>
		    					<!-- <tr class = "text-center">
		    						<td><h3>Delete</h3></td>
		    						<td><input type="hidden" name = "delete" id="on-off-switch-notext3" value="0"></td>
		    					</tr> -->
	        				</tbody>
	        			</table>
	        		<!-- </div>
	        	</div> -->
             </div>
             <div class = "card-footer">
             	<center>
             		<a href = "<?= base_url() ; ?>permissions" class = "btn btn-lg btn-danger">Cancel</a>
             		<input type = "submit" id = "submit" class = "btn btn-lg btn-info" value = "Submit">
             	</center>
             </div>
            </div>
          </form>	
        </div>
      </div>
    </section>    	
  </div>

 <?php $this->load->view('includes/footer');?>

  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>  
<script src="<?php echo base_url()?>/assets/on_off/js/on-off-switch.js"></script>
<script src="<?php echo base_url()?>/assets/on_off/js/on-off-switch-onload.js"></script>

<script>
	// new DG.OnOffSwitch({
	// 	el:'#on-off-switch-notext',

	// });
	new DG.OnOffSwitch({
		el:'#on-off-switch-notext1'
	});
	new DG.OnOffSwitch({
		el:'#on-off-switch-notext2'
	});
	// new DG.OnOffSwitch({
	// 	el:'#on-off-switch-notext3'
	// });

	// $(document).on("change", "#username", function(){
	// 	$.ajax({
	// 		url: '<?= base_url(); ?>permissions/getUserData',
	//         type:"POST",
	//         dataType: "json",
	//         data: {'user_id': $(this).val()},
	//         async: false,
	//         success: function(res){
	//         	// console.log(res);
	//         	$.each(res, function(i, val){
	//         		$(document).find("#role_id").val(val.role_id);
	//         		$(document).find("#user_role").val(val.role);
	//         		$(document).find("#dept_id").val(val.dept_id);
	//         		$(document).find("#user_department").val(val.dept);
	//         		$(document).find("#on-off-switch-notext").val(val.index_status);
	//         		if(val.index_status == '1'){
	//         			$('#on-off-switch-notext').parent().find(".on-off-switch-track").children().css('left', '0px');
	//         			$('#on-off-switch-notext').parent().find(".on-off-switch-thumb").css('left', '30px');
	//         		}
	//         		$(document).find("#on-off-switch-notext1").val(val.create_status);
	//         		if(val.create_status == '1'){
	//         			$('#on-off-switch-notext1').parent().find(".on-off-switch-track").children().css('left', '0px');
	//         			$('#on-off-switch-notext1').parent().find(".on-off-switch-thumb").css('left', '30px');
	//         		}
	//         		$(document).find("#on-off-switch-notext2").val(val.edit_status);
	//         		if(val.edit_status == '1'){
	//         			$('#on-off-switch-notext2').parent().find(".on-off-switch-track").children().css('left', '0px');
	//         			$('#on-off-switch-notext2').parent().find(".on-off-switch-thumb").css('left', '30px');
	//         		}
	//         		$(document).find("#on-off-switch-notext3").val(val.delete_status);
	//         		if(val.delete_status == '1'){
	//         			$('#on-off-switch-notext3').parent().find(".on-off-switch-track").children().css('left', '0px');
	//         			$('#on-off-switch-notext3').parent().find(".on-off-switch-thumb").css('left', '30px');
	//         		}
	//         	});
	//         }
	// 	})
	// });

	//onchange userRole
	$(document).on("change", "#userRole", function(){
		var status = true;
		if($("#userDepartment").val() == '0'){
			status = false;
		}

		if(status){

			$.ajax({
				url: '<?= base_url(); ?>permissions/getUserData',
		        type:"POST",
		        dataType: "json",
		        data: {'role_id': $(this).val(), "dept_id": $("#userDepartment").val()},
		        async: false,
		        success: function(res){
		        	
		        	if(res != false)
		        	{	
			          $.each(res, function(i, val){
			        	$(document).find("#on-off-switch-notext1").val(val.route_status);
		        		if(val.category_id == '2' && val.route_status == '1'){
		        			$('#on-off-switch-notext1').parent().find(".on-off-switch-track").children().css('left', '0px');
		        			$('#on-off-switch-notext1').parent().find(".on-off-switch-thumb").css('left', '30px');
		        		}
		        		$(document).find("#on-off-switch-notext2").val(val.route_status);
		        		if(val.category_id == '3' && val.route_status == '1'){
		        			$('#on-off-switch-notext2').parent().find(".on-off-switch-track").children().css('left', '0px');
		        			$('#on-off-switch-notext2').parent().find(".on-off-switch-thumb").css('left', '30px');
		        		}
		        	  });	

		        	}else{
		        		console.log("test");
		        		$('#on-off-switch-notext1').parent().find(".on-off-switch-track").children().css('left', '-30px');
		        		$('#on-off-switch-notext1').parent().find(".on-off-switch-thumb").css('left', '0px');
		        		$('#on-off-switch-notext2').parent().find(".on-off-switch-track").children().css('left', '-30px');
		        		$('#on-off-switch-notext2').parent().find(".on-off-switch-thumb").css('left', '0px');
		        	}
		        }
			});

		}else{
			swal("Warning!", "Please Select User Department", "warning");
		}
	});

	//onchange deptartment
	$(document).on("change", "#userDepartment", function(){
		var status = false;
		if($("#userRole").val() != '0'){
			status = true;
		}

		let option = "<option value = '0'>Select Role</option>";

		if(status){

			$.ajax({
				url: '<?= base_url(); ?>permissions/getUserData',
		        type:"POST",
		        dataType: "json",
		        data: {'role_id': $("#userDepartment").val(), "dept_id": $(this).val()},
		        async: false,
		        success: function(res){
					// console.log(res);
		         if(res['res'] != false)
		         {	
		          $.each(res['res'], function(i, val){	
		        	$(document).find("#on-off-switch-notext1").val(val.create_status);
	        		if(val.category_id == '2' && val.route_status == '1'){
	        			$('#on-off-switch-notext1').parent().find(".on-off-switch-track").children().css('left', '0px');
	        			$('#on-off-switch-notext1').parent().find(".on-off-switch-thumb").css('left', '30px');
	        		}

	        		$(document).find("#on-off-switch-notext2").val(val.edit_status);
	        		if(val.category_id == '3' && val.route_status == '1'){
	        			$('#on-off-switch-notext2').parent().find(".on-off-switch-track").children().css('left', '0px');
	        			$('#on-off-switch-notext2').parent().find(".on-off-switch-thumb").css('left', '30px');
	        		}
	        	  });
	        	  } else {
	        	  	$('#on-off-switch-notext1').parent().find(".on-off-switch-track").children().css('left', '-30px');
	        		$('#on-off-switch-notext1').parent().find(".on-off-switch-thumb").css('left', '0px');
	        		$('#on-off-switch-notext2').parent().find(".on-off-switch-track").children().css('left', '-30px');
	        		$('#on-off-switch-notext2').parent().find(".on-off-switch-thumb").css('left', '0px');
				  }
				  
				  if(res['success'] == 1){
					  $.each(res['resData'], function(ind,val){
						option += "<option value = '"+val['role_id']+"'>"+val['title']+"</option>"
					  });
					  
					  $(document).find("#userRole").append(option);
					  $('.selectpicker').selectpicker('refresh');
				  }else{
					  $(document).find("#userRole").append(option);
					  $('.selectpicker').selectpicker('refresh');
				  }
		        }
			});

		}
	});

	$(document).on("submit", "#permission-form", function(e){
		e.preventDefault();
		// console.log($(this).serialize());
		var status = true;
		if($("#userDepartment").val() == '0'){
			status = false;
		}

		if($("#userRole").val() == '0'){
			status = false;
		}

		if(status){
			var formData = new FormData(document.getElementById("permission-form"));

			$.ajax({
				url: '<?= base_url(); ?>permissions/addPermission',
		        type:"POST",
		        dataType: "json",
		        data: formData,
		        processData:false,
		        contentType:false,
		        cache:false,
		        async:false,
		        success: function(res){
		        	// console.log(res);
		        	if(res.success == '1'){
		        		swal("Success", "Permission Granted", "success").then((willactive) => {
		        			location.reload();
		        		});
		        	}
		        }
			});
		}else{
			swal("Warning!", "Please Select All Fields", "warning");
		}	
	});

</script>
