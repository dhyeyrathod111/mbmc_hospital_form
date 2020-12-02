<?php $this->load->view('includes/header'); ?>

<!-- Main Sidebar Container -->
<?php $this->load->view('includes/sidenav'); ?>

<div class="content-wrapper">
  	<section class = "content">
  		<div class="row">
  			<div class="col-12">
  				<div class="card">
  					<form role="form"  class="roleStatusFormEdit" name = "roleStatusFormEdit" id="roleStatusFormEdit" method="post" enctype="multipart/form-data" >
  					<div class="card-body">
  						<div class="row justify-content-center">
  							<div class = "col-4">
  								<input type="hidden" name="status_id" value = "<?= $appData[0]['status_id']; ?>">
  								<label for="statu_title">Status Title</label>
  								<input type="text" name="status_title" value = "<?= $appData[0]['status_title']; ?>" class = "form-control" placeholder="Enter Status Title" required>
  							</div>
  						</div>
  						<br>
  						<div class = "row justify-content-center">
  							<div class = "col-4">
  								<center>
  									<input type="submit" class = "btn btn-md btn-primary" name="submit" value = "Submit">
  								</center>
  							</div>
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
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>

<script>
	$(document).ready(function(){
		$(document).on("submit", "#roleStatusFormEdit", function(e){
			e.preventDefault();

			var formData = new FormData(document.getElementById("roleStatusFormEdit"));

			$.ajax({
	          url: "<?= base_url() ?>rolestatus/submitEdit",
	          type:"post",
	          data: formData,
	          processData:false,
	          contentType:false,
	          cache:false,
	          async:false,
	          success: function(data){
	            var data = $.parseJSON(data);
	            if(data.success == '1'){
	              swal("Success", "Status Edited Successfully", "success").then((willactive) => {
	                location.href = "<?= base_url() ?>rolestatus/"
	              });
	            }else{
	              swal("Error", "Some Error Occured Please Try Again", "error");
	            }
	          }
	        });
		})
	})
</script>