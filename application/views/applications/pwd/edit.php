  <?php 
    $this->load->view('includes/header'); 
    $readonly = "readonly";
    if($this->authorised_user['is_user'] == '1'){
      $readonly = "";
    }
  ?>

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
            <h1>Edit Application</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Edit Application</li>
            </ol>
          </div>
        </div>
      </div> --><!-- /.container-fluid - ->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <!-- form start -->
                  <form role="form" class="pwd-form" data-page = 'edit' id="pwd-form" method="post" enctype="multipart/form-data">
                    <div class="card-header">
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

                            <input type="hidden" value="<?=($users['app_id'] != null) ? $users['app_id'] :'1' ?>" name="app_id" id="app_id">
                            <input type="hidden" value="<?=($users['id'] != null) ? $users['id'] :'1' ?>" name="id" id="id">
                            <?php
                              if($users['app_id'] != null) {
                                  $app_val = 'MBMC-00000'.$users['app_id'];
                                  $app_no = $app_val++;
                              } else {
                                $app_no = 'MBMC-000001';
                              }
                           ?>
                            <input type="text" class="form-control readonly_fields" value="<?= $app_no ; ?>" name="application_no" id="application_no" placeholder="Enter Application no" readonly>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="applicant_name">Applicant Name<span class="red">*</span></label>
                            <input type="text" class="form-control readonly_fields" value="<?=($users['applicant_name'] != null) ? $users['applicant_name'] :'' ?>" name="applicant_name" id="applicant_name" placeholder="Enter full name" <?= $readonly; ?>>
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="email_id">Applicant Email Id<span class="red">*</span></label>
                            <input type="text" class="form-control readonly_fields" value="<?=($users['applicant_email_id'] != null) ? $users['applicant_email_id'] :'' ?>" name="applicant_email_id" id="applicant_email_id" placeholder="Enter email Id" <?= $readonly; ?>>
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="mobile_no">Applicant Mobile no<span class="red">*</span></label>
                            <input type="text" class="form-control readonly_fields" value="<?=($users['applicant_mobile_no'] != null) ? $users['applicant_mobile_no'] :'' ?>" name="applicant_mobile_no" id="applicant_mobile_no" placeholder="Enter mobile no" <?= $readonly; ?>>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label  for="alert_mobile_no">Alternate Mobile no<span class="grey"> (optional)</span></label>
                            <input type="text" class="form-control readonly_fields" value="<?=($users['applicant_alternate_no'] != null) ? $users['applicant_alternate_no'] :'' ?>" name="applicant_alternate_no" id="applicant_alternate_no" placeholder="Enter alternate mobile no" <?= $readonly; ?>>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="alert_mobile_no">Applicant Address<span class="red">*</span></label>
                            <textarea type="text" class="form-control readonly_fields applicant_address_validation" name="applicant_address" id="applicant_address" value="" placeholder="Enter applicant address" <?= $readonly; ?>><?=($users['applicant_address'] != null) ? $users['applicant_address'] :'' ?></textarea> 
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
                            <input type="text" class="form-control" value="<?php echo $users['letter_no'] ?>" name="letter_no" id="letter_no" placeholder="Enter letter no" <?= $readonly; ?>>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="dol">Date on Letter<span class="grey">&nbsp;(optional)</span></label>
                            <input type="text" class="form-control datepicker" value="<?php echo $users['letter_date'] ?>" name="letter_date" id="letter_date" placeholder="Enter Date on Letter" <?= $readonly; ?>>
                          </div>
                        </div>
                        
                        <div class="col-4">
                          <div class="form-group">
                            <label for="company_name">Company Name<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="company_name_select" name="company_name" data-live-search="true" <?= $readonly; ?>>
                              <option value="">---Select company name---</option>
                              <?php foreach ($company_names as $name) : ?>
                                <option <?= ($name->company_id == $users['company_name']) ? 'selected' : '' ; ?> value="<?= $name->company_id ?>"><?= $name->company_name ?></option>
                              <?php endforeach ; ?> 
                            </select>
                            <div id="company_name_error"></div>
                          </div>
                        </div>

                        <div class="col-4">
                          <div class="form-group">
                            <label for="exampleCheck1">Company address<span class="red">*</span></label>
                            <select class="selectpicker form-control" id="company_address" name="company_address" data-live-search="true" <?= $readonly; ?>>
                              <option value="">---Select company address---</option>
                              <?php foreach ($company_address as $key => $oneAddress) : ?>
                                <option <?= ($oneAddress->address_id == $users['company_address']) ? 'selected' : '' ; ?> value="<?= $oneAddress->address_id ?>"><?= $oneAddress->company_address ?></option>
                              <?php endforeach ; ?>
                            </select>
                          </div>
                        </div>

                        
                        <div class="col-4">
                          <div class="form-group">
                            <label for="contact_person">Name of Contact Person<span class="red">*</span></label>
                            <input type="text" value="<?= $users['contact_person'] ?>" name="contact_person" class="form-control" id="contact_person" placeholder="Enter Name of contact person" required <?= $readonly; ?>>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="exampleCheck1">Contact number of contact person<span class="red">*</span></label>
                            <input type="text" value="<?= $users['landline_no'] ?>" class="form-control" name="landline_no" id="landline_no" placeholder="Enter contact number of contact person" required <?= $readonly; ?>>
                          </div>
                        </div>
                        <div class = "col-4">
                          <div class = "form-group">
                            <label for="company_head">Name Of Company Head<span class = "red">*</span></label>
                            <input type="text" value="<?= $users['name_company_head'] ?>" name="name_company_head" id = "name_company_head" class = "form-control" placeholder = "Enter Name Company Head" required <?= $readonly; ?>>
                          </div>
                        </div>
                        <div class = 'col-4'>
                          <div class = "form-group">
                            <label for="company_head_no">Contact Number Of Company Head<span class = "red">*</span></label>
                            <input type="number" value="<?= $users['company_head_number'] ?>" min = "0" class = "form-control" name = "company_head_number" id = "company_head_number" placeholder = "Enter Company Head Number" required <?= $readonly; ?>>
                          </div>
                        </div>
                        <div class = 'col-4'>
                              <div class = "form-group">
                                <label for="company_head_no">Designation Of Company Head<span class = "red">*</span></label>
                                <input type="text" value="<?= $users['company_head_designation'] ?>" name = "company_head_designation" id = "company_head_designation" class = "form-control company_designation_validation" placeholder = "Enter Designation Of Company Head" required <?= $readonly; ?>>
                              </div>
                        </div>
                        <div class = "col-4">
                              <div class = "form-group">
                                <label for="assistan_name">Name Of Assistant<span class = "red">*</span></label>
                                <input type="text" value="<?= $users['assistant_name'] ?>" name = "assistant_name" id = "assistant_name" class = "form-control" placeholder = "Enter Name Of Assistant" required <?= $readonly; ?>>
                              </div>
                        </div>
                        <div class = "col-4">
                              <div class = "form-group">
                                <label for="assistan_name">Contact Number Of Assistant<span class = "red">*</span></label>
                                <input type="text" value="<?= $users['assistant_number'] ?>" name = "assistant_number" id = "assistant_number" class = "form-control" placeholder = "Enter Number Of Assistant" required <?= $readonly; ?>>
                              </div>
                        </div>
                        <div class = "col-4">
                            <div class = "form-group">
                              <label for="assistan_name">Designation Of Assistant<span class = "red">*</span></label>
                              <input type="text" value="<?= $users['assistant_designation'] ?>" name = "assistant_designation" id = "assistant_designation" class = "form-control company_designation_validation" placeholder = "Enter Designation Of Assistant" required <?= $readonly; ?>>
                            </div>
                        </div>
                      </div>
                    </div>
                    <!-- road info -->
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
                                <textarea class="form-control" name="road_name" <?= $readonly ?> id="road_name" placeholder="Enter name of road"><?= $users['road_name'] ?></textarea>
                              </div>
                            </div>

                            <div class="col-6">
                              <div class="form-group">
                                <label for="Landmark">Landmark<span class="red">*</span></label>
                                <input type="text" <?= $readonly ?> class="form-control" value="<?= $users['landmark'] ?>" name="landmark" id="landmark" placeholder="Enter Landmark"></input>
                              </div>
                            </div>

                            <div class="col-4">
                              <div class="form-group">
                                <label for="name_of_road">work start date<span class="red">*</span></label>
                                <input type="text" class="form-control" value="<?= $users['work_start_date'] ?>" readonly name="work_start_date" id="work_start_date" placeholder="Enter name of road">
                              </div>
                            </div>

                            <div class="col-4">
                              <div class="form-group">
                                <label for="name_of_road">Work end date<span class="red">*</span></label>
                                <input type="text" class="form-control datepicker" value="<?= $users['work_end_date'] ?>"  readonly name="work_end_date" id="work_end_date" placeholder="Enter Date on Letter">
                              </div>
                            </div>

                            <div class="col-4">
                              <div class="form-group">
                                <label for="name_of_road">Total days of work<span class="red">*</span></label>
                                <input type="text" class="form-control" value="<?= $users['days_of_work'] ?>" readonly name="total_days_of_work" id="total_days_of_work">
                              </div>
                            </div>

                            <div class="col-12 text-right">
                              <button id="add_road_type" class="btn btn-success">Add</button>
                            </div>

                          </div>
                        </div>
                        <div class="card-body">
                        <div class="row">
                        <table class="table table-responsive-sm">
                          <thead>
                            <tr class="text-center">
                              <th>Type of road</th>
                              <th>Start point</th>
                              <th>End point</th>
                              <th>Total length(in meter)</th>
                              <?= ($this->authorised_user['is_user'] == 0) ? '<th>Defect laiblity</th>' : '' ?>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody class="tableBody">
                            <?php if (count($application_roadtype)) : ?>
                            <?php foreach ($application_roadtype as $roadtype) : ?>
                              <tr id="oneRoadType">
                                <td>
                                  <select required class="form-control road_type_select road_info_selecter" name="road_type[]">
                                    <option value="">--- Select road type ---</option>
                                    <?php foreach ($road_type as $type) : ?>
                                      <option <?= ($type->road_id == $roadtype->road_type_id) ? 'selected' : '' ?> value="<?php echo $type->road_id ?>"><?php echo $type->road_title ?></option>
                                    <?php endforeach ; ?>
                                  </select>
                                </td>
                                <td><input type="text" value="<?= $roadtype->start_point ?>" class="form-control road_info_selecter" name="start_point[]" required></td>
                                <td><input type="text" value="<?= $roadtype->end_point ?>" class="form-control road_info_selecter" name="end_point[]" required></td>
                                <td><input type="text" value="<?= $roadtype->total_length ?>" class="form-control road_info_selecter" name="total_length[]" required></td>
                                <?php if ($this->authorised_user['is_user'] == 0) : ?>
                                <td>
                                  <select required class="form-control road_type_select road_info_selecter" name="defect_laiblity[]">
                                    <option value="">--- Select road type ---</option>
                                    <?php foreach ($defect_laiblities as $oneLaiblity) : ?>
                                      <option <?= ($oneLaiblity->laib_id == $roadtype->defectlaib_id) ? 'selected' : '' ?> value="<?php echo $oneLaiblity->laib_id ?>"><?php echo $oneLaiblity->laib_name ?></option>
                                    <?php endforeach ; ?>
                                  </select>
                                </td>
                                <?php endif ; ?>
                                <td><span class="btn btn-info"><i class="fas fa-trash-alt delete_row"></i></span></td>
                              </tr>
                            <?php endforeach ?>
                            <?php else : ?>
                              <tr id="oneRoadType">
                                <td>
                                  <select required class="form-control road_type_select road_info_selecter" name="road_type[]">
                                    <option value="">--- Select road type ---</option>
                                    <?php foreach ($road_type as $type) : ?>
                                      <option value="<?php echo $type->road_id ?>"><?php echo $type->road_title ?></option>
                                    <?php endforeach ; ?>
                                  </select>
                                </td>
                                <td><input type="text" class="form-control road_info_selecter" name="start_point[]" required></td>
                                <td><input type="text" class="form-control road_info_selecter" name="end_point[]" required></td>
                                <td><input type="text" class="form-control road_info_selecter" name="total_length[]" required></td>
                                <td>
                                  <select required class="form-control road_type_select road_info_selecter" name="road_type[]">
                                    <option value="">--- Select Defect Laiblity ---</option>
                                    <?php foreach ($defect_laiblity as $oneLaiblity) : ?>
                                      <option value="<?php echo $oneLaiblity->laib_id ?>"><?= $oneLaiblity->laib_name ?></option>
                                    <?php endforeach ; ?>
                                  </select>
                                </td>
                                <td><span class="btn btn-info"><i class="fas fa-trash-alt delete_row"></i></span></td>
                              </tr>
                            <?php endif ; ?>
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
                                <select class="selectpicker form-control" id="permission_type_select" name="permission_type" data-live-search="true" <?= $readonly; ?>>
                                  <option value="">---Select permission type---</option>
                                  <?php foreach ($permission_types as $oneType) : ?>
                                    <option <?= ($oneType->pt_id == $users['permission_type']) ? 'selected' : '' ; ?> value="<?= $oneType->pt_id ?>"><?= $oneType->permission_type ?></option>
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

                                 <input type="file" name="request_letter" id="request_letter" value="<?=($users['request_letter'] != null) ? $users['request_letter'] :'' ?>"  class="custom-file-input readonly_fields" <?= $readonly; ?>>

                                  <input type="hidden" name="request_letter_name" id="request_letter_name_id" value="<?= $users['request_letter_name']?>" class="custom-file-input">
                                  <label class="custom-file-label" for="request_letter">Choose file</label>
                                </div>
                              </div>
                            </div>
                        </div> 
                        <div class="col-6">
                          <h3 class="card-title link-margin">
                            <label for="" id="request_letter_name" class="text-info"> <a href="<?=($users['request_letter'] != null) ? $users['request_letter'] :'' ?>"><?= $users['request_letter_name'] ?> </a>
                            </label>
                          </h3>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="geo_location_map">Geo Location map<span class="red">*</span></label>
                            <div class="form-group">
                              <div class="custom-file">
                                <input type="file" name="geo_location_map" id="geo_location_map" value="" class="custom-file-input readonly_fields">
                                 <input type="hidden" name="geo_map_name" id="geo_map_name_id" value="<?= $users['geo_name']?>" class="custom-file-input">
                                <label class="custom-file-label" for="geo_location_map">Choose file</label>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-6" style="">
                          <h3 class="card-title link-margin">
                            <label for="" id="geo_map_name"  class="text-info"><a href="<?=($users['geo_location_map'] != null) ? $users['geo_location_map'] :'' ?>"><?= $users['geo_name']?></a>
                            </label>
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
                                  <span class="text-danger">Only PDF are allowed.</span>
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
														<?php
															if($this->authorised_user['is_user'] != 0){
																echo '<a href="'.base_url().'pwd/pwduserlist" class="btn btn-lg btn-info white">Cancel</a>';
															}else{
																echo '<a href="'.base_url().'pwd" class="btn btn-lg btn-info white">Cancel</a>';
															}
														?>
                            <?php if ( ($users['last_approved_role_id'] <= $this->authorised_user['role_id']) && $users['file_closure_status'] == 0 ) : ?>
                              <button type="submit" class="btn btn-lg btn-primary right">Submit</button>
                            <?php endif ; ?>
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
  <input type="hidden" value="<?= $readonly ? TRUE : FALSE ; ?>" id="is_authority_edit">  
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
<script type="text/javascript">
  $(document).ready(() => {
    $("#pwd-form :input").each(function(){
        $(this).attr('autocomplete', 'off'); 
    });
  });
</script>  
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/applications.js" id = "createPwd" is_user = "<?= $this->authorised_user['is_user']; ?>"></script>

<!-- page script -->
</body>
</html>
