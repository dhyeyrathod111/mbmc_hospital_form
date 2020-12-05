<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <form role="form" action="<?= base_url('hospital/application_type_process') ?>" method="post">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="card-title">
                                        <label for="email_id" class="text-info">Application type</label>
                                    </h3>
                                    <a class="btn btn-success float-right" href="<?= base_url('hospital/user_apps_list') ?>">Application List</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="application_type" value="1" checked>
                                        <label class="form-check-label" for="exampleRadios1">
                                            New application
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="application_type" value="2">
                                        <label class="form-check-label" for="exampleRadios2">
                                            Renewal application
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row center">
                                <div class="col-12">
                                    <a href="<?= base_url('hospital/create') ?>" class="btn btn-lg btn-info white">Cancel</a>
                                    <button type="submit" class="btn btn-lg btn-primary right">Submit</button>
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
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/hospital.js" id="createHospital" is_user="<?= $this->authorised_user['is_user']; ?>"></script>
</body>
</html>