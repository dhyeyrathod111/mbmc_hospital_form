  <?php $this->load->view('includes/header'); ?>

  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Application</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add Application</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid - ->
    </section> -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <!-- /.card-header -->
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <form role="form" class="pwd-form" data-page = 'create' id="pwd-form" method="post" enctype="multipart/form-data">
                    <div class="card-header">
                      
                      <div class = "row float-right">
                        <a href = "<?= base_url().'pwd/pwduserlist'?>" class = "btn btn-info btn-md application_list">Application List</a>
                      </div>

                       <div class="row">
                        <div class="col-12">
                          <h3 class="card-title">
                            <label for="email_id" class="text-info">Personal Information</label>
                          </h3>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="application_no"><span>Application No</span><span class="red">*</span></label>
                            <input type="hidden" value="<?=($app_id != null) ? $app_id :'1' ?>" name="app_id" id="app_id">
                             <input type="hidden" value="" name="id" id="id">
                            <?php
                              if($app_id != null) { 
                                  $app_val = 'MBMC-00000'.$app_id;
                                  $app_no = ++$app_val;
                              } else {
                                $app_no = 'MBMC-000001';
                              }
                           ?>
                            <input type="text" class="form-control" value="<?= $app_no; ?>" name="application_no" id="application_no" placeholder="Enter Application no" readonly>

                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Applicant Name<span class="red">*</span></label>
                            <input type="text" class="form-control" value="<?= !empty($application) ? $application['applicant_name'] : '' ; ?>" name="applicant_name" id="applicant_name" placeholder="Enter Applicant name">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="email_id">Applicant Email Id<span class="red">*</span></label>
                            <input type="text" value="<?= !empty($application) ? $application['applicant_email_id'] : '' ; ?>" class="form-control" name="applicant_email_id" id="applicant_email_id" placeholder="Enter email Id">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="mobile_no">Applicant Mobile no<span class="red">*</span></label>
                            <input type="text" value="<?= !empty($application) ? $application['applicant_mobile_no'] : '' ; ?>" class="form-control" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter mobile no">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label  for="alert_mobile_no">Alternate Mobile no<span class="grey">&nbsp;(optional)</span></label>
                            <input type="text" value="<?= !empty($application) ? $application['applicant_alternate_no'] : '' ; ?>" class="form-control" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile no">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="alert_mobile_no">Applicant Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control applicant_address_validation" name="applicant_address" id="applicant_address" placeholder="Enter applicant address"><?= !empty($application) ? $application['applicant_address'] : '' ; ?></textarea> 
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- company info -->
                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Company Information</label>
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="letter_no">Company Letter No<span class="grey">&nbsp;(optional)</span></label>
                            <input type="text" value="<?= !empty($application) ? $application['letter_no'] : '' ; ?>" class="form-control" name="letter_no" id="letter_no" placeholder="Enter letter no">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="dol">Date on Letter<span class="grey">&nbsp;(optional)</span></label>
                            <input type="text" value="<?= !empty($application) ? $application['letter_date'] : '' ; ?>" class="form-control datepicker" name="letter_date" id="letter_date" readonly="" placeholder="Enter Date on Letter">
                          </div>
                        </div>
                        
                        <div class="col-4">
                          <div class="form-group">
                            <label for="company_name">Company Name<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="company_name_select" name="company_name" data-live-search="true">
                              <option value="">---Select company name---</option>
                              <?php foreach ($company_names as $name) : ?>
                                <option <?php if(!empty($application)) echo ($name->company_id == $application['company_name']) ? 'selected' : '' ; ?> value="<?= $name->company_id ?>"><?= $name->company_name ?></option>
                              <?php endforeach ; ?> 
                            </select>
                            <div id="company_name_error"></div>
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="exampleCheck1">Company address<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="company_address" name="company_address" data-live-search="true">
                              <option value="">---Select company address---</option>
                              <?php if (!empty($application)) : ?>
                                <?php foreach ($company_address as $adress) : ?>
                                  <option  <?php if(!empty($application)) echo ($adress->address_id == $application['company_address']) ? 'selected' : '' ?> value="<?= $adress->address_id ?>"><?= $adress->company_address ?></option>
                                <?php endforeach ; ?>
                              <?php endif ; ?>
                            </select>
                          </div>
                        </div>

                        
                        <div class="col-4">
                          <div class="form-group">
                            <label for="contact_person">Name of Contact Person<span class="red">*</span></label>
                            <input type="text" value="<?= !empty($application) ? $application['contact_person'] : '' ; ?>" name="contact_person" class="form-control" id="contact_person" placeholder="Enter Name of contact person" required>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="exampleCheck1">Contact number of contact person<span class="red">*</span></label>
                            <input type="text" value="<?= !empty($application) ? $application['landline_no'] : '' ; ?>" class="form-control" name="landline_no" id="landline_no" placeholder="Enter contact number of contact person" required>
                          </div>
                        </div>
                        <div class = "col-4">
                          <div class = "form-group">
                            <label for="company_head">Name Of Company Head<span class = "red">*</span></label>
                            <input type="text" value="<?= !empty($application) ? $application['name_company_head'] : '' ; ?>" name = "name_company_head" id = "name_company_head" class = "form-control" placeholder = "Enter Name Company Head" required>
                          </div>
                        </div>
                        <div class = 'col-4'>
                              <div class = "form-group">
                                <label for="company_head_no">Contact Number Of Company Head<span class = "red">*</span></label>
                                <input value="<?= !empty($application) ? $application['company_head_number'] : '' ; ?>" type="number" min = "0" class = "form-control" name = "company_head_number" id = "company_head_number" placeholder = "Enter Number Of Company Head" required>
                              </div>
                        </div>
                        <div class = 'col-4'>
                              <div class = "form-group">
                                <label for="company_head_no">Designation Of Company Head<span class = "red">*</span></label>
                                <input type="text" value="<?= !empty($application) ? $application['company_head_designation'] : '' ; ?>" name = "company_head_designation" id = "company_head_designation" class = "form-control company_designation_validation" placeholder = "Enter Designation Of Company Head" required>
                              </div>
                        </div>
                        <div class = "col-4">
                              <div class = "form-group">
                                <label for="assistan_name">Name Of Assistant<span class = "red">*</span></label>
                                <input type="text" value="<?= !empty($application) ? $application['assistant_name'] : '' ; ?>" name = "assistant_name" id = "assistant_name" class = "form-control" placeholder = "Enter Name Of Assistant" required>
                              </div>
                        </div>
                        <div class = "col-4">
                              <div class = "form-group">
                                <label for="assistan_name">Contact Number Of Assistant<span class = "red">*</span></label>
                                <input type="text" value="<?= !empty($application) ? $application['assistant_number'] : '' ; ?>" name = "assistant_number" id = "assistant_number" class = "form-control" placeholder = "Enter Contact Number Of Assistant" required>
                              </div>
                        </div>
                        <div class = "col-4">
                              <div class = "form-group">
                                <label for="assistan_name">Designation Of Assistant<span class = "red">*</span></label>
                                <input value="<?= !empty($application) ? $application['assistant_designation'] : '' ; ?>" type="text" name = "assistant_designation" id = "assistant_designation" class = "form-control company_designation_validation" placeholder = "Enter Designation Of Assistant" required>
                              </div>
                        </div>
                      </div>
                    </div>

                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Road Information</label>
                      </h3>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        
                        <div class="col-6">
                          <div class="form-group">
                            <label for="name_of_road">Name of road(with address)<span class="red">*</span></label>
                            <textarea class="form-control" name="road_name" id="road_name" placeholder="Enter Name Of Road With Address"><?= !empty($application) ? $application['road_name'] : '' ; ?></textarea>
                          </div>
                        </div>

                        <div class="col-6">
                          <div class="form-group">
                            <label for="name_of_road">Landmark<span class="red">*</span></label>
                            <input type="text" class="form-control" value="<?= !empty($application) ? $application['landmark'] : '' ; ?>" name="landmark" id="landmark" placeholder="Enter Landmark" required></input>
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="name_of_road">expected work start date<span class="red">*</span></label>
                            <input type="text" class="form-control" readonly name="work_start_date" id="work_start_date" placeholder="Select Work Start Date">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="name_of_road">expected work end date<span class="red">*</span></label>
                            <input type="text" class="form-control datepicker" readonly name="work_end_date" id="work_end_date" placeholder="Enter Date on Letter">
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="name_of_road">Total days of work<span class="red">*</span></label>
                            <input type="text" class="form-control" readonly name="total_days_of_work" id="total_days_of_work">
                          </div>
                        </div>

                        <div class="col-12 text-right">
                          <button id="add_road_type" class="btn btn-success">Add</button>
                        </div>

                      </div>
                    </div>
                        

                        <div class="card-body">
                        <div class="row">
                        <table class="table table-responsive-sm" id = "road_info">
                          <thead>
                            <tr class="text-center">
                              <th>Type of road</th>
                              <th>Start point</th>
                              <th>End point</th>
                              <th>Total length(in meter)</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody class="tableBody">
                            <tr id="oneRoadType">
                              <td>
                                <select required class="form-control road_type_select road_info_selecter" name="road_type[]">
                                  <option value="">--- Select road type ---</option>
                                  <?php foreach ($road_type as $type) : ?>
                                    <option value="<?php echo $type->road_id ?>"><?php echo $type->road_title ?></option>
                                  <?php endforeach ; ?>
                                </select>
                              </td>
                              <td><input type="text" class="form-control start_point road_info_selecter" name="start_point[]" placeholder = "Enter Start Point" required></td>
                              <td><input type="text" class="form-control end_point road_info_selecter" name="end_point[]" placeholder = "Enter End Point" required></td>
                              <td><input type="number" class="form-control total_length road_info_selecter" name="total_length[]" placeholder = "Enter Total Length" required></td>
                              <td><span class="btn btn-info"><i class="fas fa-trash-alt delete_row"></i></span></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>

                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Permission type</label>
                      </h3>
                    </div>

                    <div class="card-body">
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="request_letter">Permission type<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <select class="selectpicker form-control" id="permission_type_select" name="permission_type" data-live-search="true">
                                  <option value="">---Select permission type---</option>
                                  <?php foreach ($permission_types as $oneType) : ?>
                                    <option <?php if(!empty($application)) echo ($oneType->pt_id == $application['permission_type']) ? 'selected' : '' ; ?> value="<?= $oneType->pt_id ?>"><?= $oneType->permission_type ?></option>
                                  <?php endforeach ; ?> 
                                </select>
                                <div id="permission_type_error"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>



                    <div class="card-header">
                      <h3 class="card-title">
                        <label for="email_id" class="text-info">Attachments</label>
                      </h3>
                    </div>

                    <div class="card-body">
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="request_letter">Request Letter<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="request_letter_name" id="request_letter" class="custom-file-input">
                                <label class="custom-file-label" for="request_letter">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6">
                          <h3 class="card-title link-margin">
                            <?php if (!empty($application)) : ?>
                              <label class="text-info" id="request_letter_name"><?= $request_letter['image_name'] ?></label>
                              <input type="hidden" value="<?= $request_letter['image_id'] ?>" name="request_letter_id">
                            <?php else : ?>
                              <label class="text-info" id="request_letter_name"> Please select a document</label>
                            <?php endif ; ?>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="geo_location_map">Geo Location map<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="geo_location_map" id="geo_location_map" class="custom-file-input">
                                <label class="custom-file-label" for="geo_location_map">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <?php if (!empty($application)) : ?>
                              <label class="text-info" id="geo_map_name"><?= $geo_location_docs['image_name'] ?></label>
                              <input type="hidden" value="<?= $geo_location_docs['image_id'] ?>" name="geo_location_id">
                            <?php else : ?>
                              <label class="text-info" id="geo_map_name"> Please select a document</label>
                            <?php endif ; ?>
                          </h3>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-12">
                            <h3 class="card-title">
                              <label for="email_id" class="text-danger">Note:</label>
                              <ul style="list-style: none;">
                                <li>
                                  <i class=" text-danger fas fa-exclamation-circle"></i>
                                  <span class="text-danger">Only PDF files are allowed.</span>
                                </li>
                                <li>
                                  <i class="text-danger fas fa-exclamation-circle"></i>
                                  <span class="text-danger" >File size should Not be more than 5 MB.</span>
                                </li>
                                <li>
                                  <i class="text-danger fas fa-exclamation-circle"></i>
                                  <span class="text-danger" >Please use google map for locating the road details using distance mesurment feature .</span>
                                </li>
                              </ul>
                            </h3>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                       <div class="row center">
                         <div class="col-12">
                            <input type="hidden" value="<?= !empty($application) ? $application['reference_no'] : 0 ?>" id="reference_no" name="reference_no">
                            <a href="<?= base_url()?>pwd" class="btn btn-lg btn-info white">Cancel</a>
                            <button type="submit" class="btn btn-lg btn-primary right">Submit</button>
                        </div>
                      </div>
                      
                    </div>
                  </form>
                </div>  
              </div>
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

<!-- DataTables -->
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/applications.js" id = "createPwd" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>
<script type="text/javascript">

  $(document).ready(() => {
    $("#work_start_date").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
    $("#work_end_date").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
    $("#pwd-form :input").each(function(){
        $(this).attr('autocomplete', 'off'); 
    });
  });



  $('#request_letter').change(function() {
    var file = $('#request_letter')[0].files[0].name;
    $('#request_letter_name').text(file);
    $('#request_letter_id').val(file);
  });

  $('#geo_location_map').change(function() {
    var file = $('#geo_location_map')[0].files[0].name;
    $('#geo_map_name').text(file);
    $('#geo_map_name_id').val(file);
  });
</script>

<!-- page script -->
</body>
</html>
