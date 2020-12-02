<?php $this->load->view('includes/header'); ?>
<!-- Main Sidebar Container -->
<?php $this->load->view('includes/sidenav'); ?>
<style type="text/css">
    .modal-dialog {
    max-width: 1000px !important;
    }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class = "row mb-3">
                            <div class = "col-3">
                                <label for = "from_date">From Date</label>
                                <input type="text" name="fromDate" autocomplete="off" readonly id="fromDate" class = "form-control datepicker" placeholder="Please Select Date">
                            </div>
                            <div class = "col-3">
                                <label for = "to_date">To Date</label>
                                <input type="text" name="toDate" autocomplete="off" readonly id = "toDate" class = "form-control datepicker" placeholder="Please Select Date">
                            </div>
                        </div>
                        <table id="lab_userapps_table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Application No</th>
                                    <th>Applicant Name</th>
                                    <th>Applicant Email Id</th>
                                    <th>Applicant Mobile No</th>
                                    <th>lab Name</th>
                                    <th>Status</th>
                                    <th>Action </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>




<?php $this->load->view('includes/footer');?>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/lab.js" id = "createlab" is_user = "<?= $this->authorised_user['user_id'] ?>"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        load_user_apps_datatable();
    });
</script>
</body>
</html>