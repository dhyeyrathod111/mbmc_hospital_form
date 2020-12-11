<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="alert_message"></div>
                        <form action="<?php echo base_url('ward/create_ward_process') ?>" id="create_ward_process">
                            <div class="row">

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Department:</label>
                                    <select name="department_id" id="department_select_ward" class="form-control">
                                      <option value="">---Select Department---</option>
                                      <?php foreach ($departments as $oneDepartment) : ?>
                                        <option value="<?php echo $oneDepartment->dept_id ?>"><?php echo $oneDepartment->dept_title ?></option>
                                      <?php endforeach ; ?>
                                  </select>
                                  <div id="department_select_error"></div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Role:</label>
                                    <select name="role_id" id="role_select_ward" class="form-control">
                                      <option value="">---Select Role---</option>
                                  </select>
                                  <div id="role_select_error"></div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Status:</label>
                                    <select name="status" id="status_select_ward" class="form-control">
                                        <option value="">---Select status---</option>
                                        <option value="1">Active</option>
                                        <option value="2">Not Active</option>
                                    </select>
                                    <div id="status_select_error"></div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">ward Title:</label>
                                    <input type="text" name="ward_title" class="form-control" placeholder="Enter ward title">
                                </div>

                            </div>
                            <div class="text-center">
                                <button type="submit" id="submit_btn" class="btn btn-primary">Submit</button>
                                <a class="btn btn-danger" href="<?php echo base_url('ward') ?>">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->load->view('includes/footer'); ?>
</div>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script src="<?php echo base_url()?>assets/custom/js/wardjqeury.js"></script>