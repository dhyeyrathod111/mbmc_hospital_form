<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10"></div>
                            <div class="col-2 p-3">
                                <a href="<?php echo base_url('garden_permission/create') ?>" class="add-btn btn btn-block btn-info">ADD</a>
                            </div>
                        </div>
                        <table id="gaeden_permission_table" class="table table-bordered table-hover">
                            <thead>
                                <tr class = "text-center">
                                    <th>Sr.No</th>
                                    <th>Permisson type</th>
                                    <th>Blueprint</th>
                                    <th>Created date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class = "text-center">
                                    <th>Sr.No</th>
                                    <th>Permisson type</th>
                                    <th>Blueprint</th>
                                    <th>Created date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
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