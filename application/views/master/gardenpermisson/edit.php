<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo base_url('process_edit_permission') ?>" id="garden_permition_form_edit">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">permission Type:</label>
                                    <input type="text" value="<?php echo $gardenpermisson->permission_type ?>" name="permition_type" class="form-control" placeholder="Enter email">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Blueprint Required:</label> <br />
                                    <input type="checkbox" <?php echo $gardenpermisson->is_blueprint ? 'checked' : '' ?> name="blueprint" style="zoom: 3;">
                                </div>
                            </div>
                            <input type="hidden" id="permission_id" value="<?php echo base64_encode($gardenpermisson->garper_id) ?>" name="permission_id">
                            
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a class="btn btn-danger" href="<?php echo base_url('garden_permission') ?>">Cancel</a>
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
<script src="<?php echo base_url()?>assets/custom/js/gardenpermisson.js"></script>