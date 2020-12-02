<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="alert_message"></div>
                        <form action="<?php echo base_url('ward/update_ward_process') ?>" id="update_ward_process">
                            <div class="row">

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Department:</label>
                                    <select name="department_id" id="department_select_ward" class="form-control">
                                        <option value="">---Select Department---</option>
                                        <?php foreach ($departments as $oneDepartment) : ?>
                                            <option <?= ($oneDepartment->dept_id == $ward->dept_id) ? "selected" : "" ?> value="<?php echo $oneDepartment->dept_id ?>"><?php echo $oneDepartment->dept_title ?></option>
                                        <?php endforeach ; ?>
                                    </select>
                                    <div id="department_select_error"></div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Role:</label>
                                    <select name="role_id" id="role_select_ward" class="form-control">
                                        <option value="">---Select Role---</option>
                                        <?php foreach ($roles as $key => $oneRole) : ?>
                                            <option <?= ($oneRole->role_id == $ward->role_id) ? "selected" : "" ?> value="<?php echo $oneRole->role_id ?>"><?php echo $oneRole->role_title ?></option>
                                        <?php endforeach ; ?>
                                  </select>
                                  <div id="role_select_error"></div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Status:</label>
                                    <select name="status" class="form-control">
                                        <option value="">---Select status---</option>
                                        <option <?= ($ward->status == 1) ? "selected" : '' ?> value="1">Active</option>
                                        <option <?= ($ward->status == 2) ? "selected" : '' ?> value="2">Not Active</option>
                                    </select>
                                    <div id="status_select_error"></div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="exampleInputEmail1">ward Title:</label>
                                    <input type="text" name="ward_title" value="<?= $ward->ward_title ?>" class="form-control" placeholder="Enter ward title">
                                </div>

                            </div>
                            <div class="text-center">
                                <input type="hidden" value="<?php echo base64_encode($ward->ward_id) ?>" name="ward_id">
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