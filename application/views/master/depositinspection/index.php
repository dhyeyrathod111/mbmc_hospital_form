<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="row pb-3">
                            <div class="col-md-5">
                                <select name="department_id" id="department_id" class="form-control selectpicker">
                                    <option value="">---Select Department---</option>
                                    <?php foreach ($departments as $oneDepartment) : ?>
                                        <option value="<?php echo $oneDepartment->dept_id ?>"><?php echo $oneDepartment->dept_title ?></option>
                                    <?php endforeach ; ?>
                                </select>
                            </div>

                            <div class="col-md-5">
                                <select name="version" id="version" class="form-control selectpicker">
                                    <option selected value="1">Active</option>
                                    <option value="2">Old version</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <a href="<?php echo base_url('depositinspection/create') ?>" style="margin-top: 0px" class="add-btn btn btn-block btn-info">ADD</a>
                            </div>
                        </div>

                        <table id="depositinspection_table" class="table table-bordered table-hover">
                            <thead>
                                <tr class = "text-center">
                                    <th>Sr.No</th>
                                    <th>Department</th>
                                    <th>User</th>
                                    <th>Inspection fee</th>
                                    <th>Deposit</th>
                                    <th>From date</th>
                                    <th>To date</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class = "text-center">
                                    <th>Sr.No</th>
                                    <th>Department</th>
                                    <th>User</th>
                                    <th>Inspection fee</th>
                                    <th>Deposit</th>
                                    <th>From date</th>
                                    <th>To date</th>
                                    <th>Edit</th>
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
<?php $this->load->view('includes/footer'); ?>
</div>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script src="<?php echo base_url()?>assets/custom/js/depositinspection.js"></script>