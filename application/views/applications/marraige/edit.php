<?php $this->load->view('includes/header'); ?>
<!-- Main Sidebar Container -->
<?php $this->load->view('includes/sidenav'); ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" rel="stylesheet"/>
<style type="text/css">
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary">
                                <form role="form" class="marriage-form" id="marriage-form-edit" method="post" enctype="multipart/form-data">
                                    <input type="hidden" value="58" name="app_id">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-12">
                                                <h3 class="card-title">
                                                    <label for="husband_details" class="text-info">Basic Details</label>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="application_no"><span>Application No</span><span class="red">*</span></label>
                                                    <input type="text" class="form-control" value="<?= $application->application_no ; ?>" name="application_no" id="application_no" placeholder="Enter Application no" readonly>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="marriage_date">Date of Marriage<span class="red">*</span></label>
                                                    <input type="text" value="<?php echo date('Y-m-d',strtotime($application->marriage_date)) ; ?>" class="form-control datepicker" name="marriage_date" id="marriage_date" placeholder="Select date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-12">
                                                <h3 class="card-title">
                                                    <label for="husband_details" class="text-info">Husband Details</label>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">Husband Name<span class="red">*</span></label>
                                                    <input type="text" class="form-control" value="<?php echo $application->husband_name ?>" name="husband_name" id="husband_name" placeholder="Enter husband name">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_age">Age<span class="red">*</span><span class="grey"> (age as on the date of soleminization)</span> </label>
                                                    <input type="number" class="form-control" value="<?php echo $application->husband_age ?>" name="husband_age" id="husband_age" placeholder="Enter age">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_religious">Religious<span class="red">*</span></label>
                                                    <input type="text" class="form-control" value="<?php echo $application->husband_religious ?>" name="husband_religious" id="husband_religious" placeholder="Enter religious">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="mobile_no">Status<span class="grey">(optional)</span></label>
                                                    <select name="husband_marriage_status" id="husband_marriage_status"class="form-control selectpicker" data-live-search="true">
                                                        <option value="">---Select the status---</option>
                                                        <option value="Un-married">Un-married</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Divorced">Divorced</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_address">Address<span class="red">*</span></label>
                                                    <textarea type="text" class="form-control" name="husband_address" id="husband_address" placeholder="Enter Address"><?php echo $application->husband_address ?></textarea> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-12">
                                                <h3 class="card-title">
                                                    <label for="wife_details" class="text-info">Wife Details</label>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="wife_name">Wife Name<span class="red">*</span></label>
                                                    <input type="text" value="<?php echo $application->wife_name ?>" class="form-control" name="wife_name" id="wife_name" placeholder="Enter wife name">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="wife_age">Age<span class="red">*</span><span class="grey"> (age as on the date of soleminization)</span> </label>
                                                    <input type="number" value="<?php echo $application->wife_age ?>" class="form-control" name="wife_age" id="wife_age" placeholder="Enter age">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="wife_religious">Religious<span class="red">*</span></label>
                                                    <input type="text" value="<?php echo $application->wife_religious ?>" class="form-control" name="wife_religious" id="religious" placeholder="Enter religious">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="wife_marriage_status">Status<span class="grey">(optional)</span></label>
                                                    <select name="wife_marriage_status" id="wife_marriage_status"class="form-control selectpicker" data-live-search="true">
                                                        <option value="">---Select the status---</option>
                                                        <option value="Un-married">Un-married</option>
                                                        <option value="Married">Married</option>
                                                        <option value="Divorced">Divorced</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="wife_address">Address<span class="red">*</span></label>
                                                    <textarea type="text" class="form-control" name="wife_address" id="wife_address" placeholder="Enter Address"><?php echo $application->wife_address ?></textarea> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-12">
                                                <h3 class="card-title">
                                                    <label for="husband_details" class="text-info">Preist Details</label>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">Preist Name<span class="red">*</span></label>
                                                    <input type="text" value="<?php echo $application->priest_name ?>" class="form-control" name="priest_name" id="priest_name" placeholder="Enter priest name">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_age">Age<span class="red">*</span><span class="grey"> (age as on the date of soleminization)</span> </label>
                                                    <input type="number" value="<?php echo $application->priest_age ?>" class="form-control" name="priest_age" id="priest_age" placeholder="Enter age">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="priest_religious">Religious<span class="red">*</span></label>
                                                    <input type="text" value="<?php echo $application->priest_religious ?>" class="form-control" name="priest_religious" id="priest_religious" placeholder="Enter religious">
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="priest_address">Address<span class="red">*</span></label>
                                                    <textarea type="text" class="form-control" name="priest_address" id="priest_address" placeholder="Enter Address"><?php echo $application->priest_address ?></textarea> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header">
                                        <h3 class="card-title">
                                            <label for="witness_det" class="text-info">Witness Details</label>
                                        </h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Occupation</th>
                                                        <th>Relation</th>
                                                        <th>Address</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($witness as $key => $one_witness) : ?>
                                                        <tr>
                                                            <td class='text-center name'>
                                                                <input type="text" value="<?php echo $one_witness->name ?>" class="form-control" name="name[]" placeholder="Enter occupation">
                                                            </td>
                                                            <td class ='text-center occupation'>
                                                                <input type="text" value="<?php echo $one_witness->occupation ?>" class="form-control" name="occupation[]" placeholder="Enter occupation">
                                                            </td>
                                                            <td class = 'text-center relation'>
                                                                <input type="text" value="<?php echo $one_witness->relation ?>" class="form-control" name="relation[]" placeholder="Enter relation">
                                                            </td>
                                                            <td class = 'text-center address'>
                                                                <textarea type="text" class="form-control" name="applicant_address[]" placeholder="Enter Address"><?php echo $one_witness->address ?></textarea>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach ; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            </div>
                            <div class="card-header">
                                <h3 class="card-title">
                                    <label for="email_id" class="text-info">Attachments</label>
                                </h3>
                            </div>
                            <div class="card-header">
                                <div class="row">
                                    <?php foreach ($document as $oneDocument) : ?>
                                        <?php if($oneDocument->file_title == 'husband_aadhaar_card') : ?>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">Husband Aadhaar Card<span class="red">*</span></label> <br />
                                                    <input type="file" name="husband_aadhaar_card">
                                                    <a download href="<?php echo base_url('/uploads/marriage/').$oneDocument->file_name ?>"><i class="black fas fa fa-download right"></i></a>
                                                </div>
                                            </div>
                                        <?php endif ; if($oneDocument->file_title == 'wife_aadhaar_card') : ?>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">Wife Aadhaar Card<span class="red">*</span></label><br />
                                                    <input type="file" name="wife_aadhaar_card">
                                                    <a download href="<?php echo base_url('/uploads/marriage/').$oneDocument->file_name ?>"><i class="black fas fa fa-download right"></i></a>
                                                </div>
                                            </div>
                                        <?php endif ; if($oneDocument->file_title == 'first_witness_id_proof') : ?>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">First Witness Id Proo<span class="red">*</span></label><br />
                                                    <input type="file" name="first_witness_id_proof">
                                                    <a download href="<?php echo base_url('/uploads/marriage/').$oneDocument->file_name ?>"><i class="black fas fa fa-download right"></i></a>
                                                </div>
                                            </div>
                                        <?php endif ; if($oneDocument->file_title == 'second_witness_id_proof') : ?>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">Second Witness Id Proof<span class="red">*</span></label><br />
                                                    <input type="file" name="second_witness_id_proof">
                                                    <a download href="<?php echo base_url('/uploads/marriage/').$oneDocument->file_name ?>"><i class="black fas fa fa-download right"></i></a>
                                                </div>
                                            </div>
                                        <?php endif ; if($oneDocument->file_title == 'third_witness_id_proof') : ?>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">Third Witness Id Proof<span class="red">*</span></label><br />
                                                    <input type="file" name="third_witness_id_proof">
                                                    <a download href="<?php echo base_url('/uploads/marriage/').$oneDocument->file_name ?>"><i class="black fas fa fa-download right"></i></a>
                                                </div>
                                            </div>
                                        <?php endif ; if($oneDocument->file_title == 'electricity_bill') : ?>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">Electricity Bill<span class="red">*</span></label><br />
                                                    <input type="file" name="electricity_bill">
                                                    <a download href="<?php echo base_url('/uploads/marriage/').$oneDocument->file_name ?>"><i class="black fas fa fa-download right"></i></a>
                                                </div>
                                            </div>
                                        <?php endif ; if($oneDocument->file_title == 'lagan_patrika') : ?>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">Lagan Patrika<span class="red">*</span></label><br />
                                                    <input type="file" name="lagan_patrika">
                                                    <a download href="<?php echo base_url('/uploads/marriage/').$oneDocument->file_name ?>"><i class="black fas fa fa-download right"></i></a>
                                                </div>
                                            </div>
                                        <?php endif ; if($oneDocument->file_title == 'ration_card') : ?>
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="husband_name">Ration Card<span class="red">*</span></label><br />
                                                    <input type="file" name="ration_card">
                                                    <a download href="<?php echo base_url('/uploads/marriage/').$oneDocument->file_name ?>"><i class="black fas fa fa-download right"></i></a>
                                                </div>
                                            </div>
                                        <?php endif ; ?>
                                    <?php endforeach ; ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                    <div class="row center">
                                        <div class="col-12">
                                            <input type="hidden" value="<?php echo base64_encode($application->id) ?>" name="marriedge_app_id">
                                            <a href="<?= base_url('/marriage') ?>" class="btn btn-lg btn-info white">Cancel</a>
                                            <button type="submit" id="submit_btn" class="btn btn-lg btn-primary right">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php $this->load->view('includes/footer');?>
<script type="text/javascript">
    function previewFile(thisimage){
       var file = $(thisimage).get(0).files[0];
       if(file){
            var reader = new FileReader();
            reader.onload = function(){
                $(thisimage).next().attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    }
    $( document ).ready(function() {
        $('#husband_marriage_status').val('<?php echo $application->husband_marriage_status ?>');
        $('#wife_marriage_status').val('<?php echo $application->wife_marriage_status ?>');
        $("#marriage-form :input").each(function(){
            $(this).attr('autocomplete', 'off'); 
        });
        $('.selectpicker').selectpicker('refresh');
    });
</script>
</div>
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/marriage.js"></script>
<script type="text/javascript"></script>
</body>
</html>