<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="<?php echo base_url('depositinspection_form_process_create') ?>" id="depositinspection_form_add">
                            <div class="row">

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Department:</label>
                                    <select name="department_id" class="form-control selectpicker">
									    <option value="">---Select Department---</option>
									    <?php foreach ($departments as $oneDepartment) : ?>
									    	<option value="<?php echo $oneDepartment->dept_id ?>"><?php echo $oneDepartment->dept_title ?></option>
									    <?php endforeach ; ?>
									</select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Inspection Fees:</label>
                                    <input type="number" name="inspection_fees" class="form-control" placeholder="Enter inspection fee">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="exampleInputEmail1">Deposit Fees:</label>
                                    <input type="number" name="deposit_fees" class="form-control" placeholder="Enter inspection fee">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">From date:</label>
                                    <input type="text" name="from_date" id="fromDate" class="form-control" placeholder="Enter inspection fee">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">To date:</label>
                                    <input type="text" name="to_date" id="toDate" class="form-control" placeholder="Enter inspection fee">
                                </div>

                            </div>
                            <div class="text-center">
                                <input type="hidden" value="<?php echo (!empty($is_edit)) ? TRUE : FALSE ; ?>" name="is_edit">
                        		<button type="submit" class="btn btn-primary">Submit</button>
                            	<a class="btn btn-danger" href="<?php echo base_url('depositinspection') ?>">Cancel</a>
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
<script src="<?php echo base_url()?>assets/custom/js/depositinspection.js"></script>