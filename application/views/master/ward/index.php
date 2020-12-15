<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row pb-3 float-right">
                            <div class="col-md-12">
                                <a href="<?php echo base_url('ward/create') ?>" style="margin-top: 0px" class="add-btn btn btn-block btn-info">Create new ward</a>
                            </div>
                        </div>

                        <table id="ward_table" class="table table-bordered table-hover">
                            <thead>
                                <tr class = "text-center">
                                    <th>Sr.No</th>
                                    <th>Ward id</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class = "text-center">
                                    <th>Sr.No</th>
                                    <th>Ward id</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                            </tbody>
                             
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<input type="hidden" value="" id="is_deleted_id">
<?php $this->load->view('includes/footer'); ?>
</div>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script src="<?php echo base_url()?>assets/custom/js/wardjqeury.js"></script>