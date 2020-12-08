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
                    <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
                        <div class="card">
                            <div class="card-body">
                                <div class = "row">
                                    <div class = "col-3">
                                        <label for = "from_date">From Date</label>
                                        <input type="text" name="fromDate" autocomplete="off" id = "fromDate" class = "form-control datepicker" placeholder="Please Select Date">
                                    </div>
                                    <div class = "col-3">
                                        <label for = "to_date">To Date</label>
                                        <input type="text" name="toDate" autocomplete="off" id = "toDate" class = "form-control datepicker" placeholder="Please Select Date">
                                    </div>
                                    <div class="col-3">
                                        <label for = "from_date">File status</label>
                                        <select class="form-control" name="approval_status" id="approval_status">
                                            <option value="0">New</option>
                                            <option value="1">Approved</option>
                                            <option value="2">Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label for = "from_date">Application type</label>
                                        <select class="form-control" name="application_type" id="application_type">
                                            <option value="0">All</option>
                                            <option value="1">Registration applications</option>
                                            <option value="2">renewal applications</option>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="modal-doc">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">Documents List</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card card-primary">
                                            <div class="card-body">
                                                <table id="remarks-table" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Sr No.</th>
                                                            <th>Document Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="doc-body">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer justify-content-between"></div>
                                </div>
                            </div>
                        </div>
                        
                        <table id="clinic_table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Application No</th>
                                    <th>Applicant Name</th>
                                    <th>Applicant Email Id</th>
                                    <th>Applicant Mobile No</th>
                                    <th>clinic Name</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>inspection</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Application No</th>
                                    <th>Applicant Name</th>
                                    <th>Applicant Email Id</th>
                                    <th>Applicant Mobile No</th>
                                    <th>clinic Name</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>inspection</th>
                                    <th>Date</th>
                                    <th>Action </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="add_remark_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Remarks</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="remarks-form">
                    
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="inspection_form_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Inspection form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="inspection_form" action="<?= base_url('clinic/inspection_form_process') ?>">
                    
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="payment_request_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Payment Reqeust form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="payment_request_form" action="<?= base_url('clinic/payment_request_process') ?>">
                    
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="payment_approvel_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">payment approvel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="payment_approvel_form" action="<?= base_url('clinic/payment_approvel_process') ?>">
                    
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-remarks">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Remarks History</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-primary">
                    <div class="card-body">
                        <table id="remarks-table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Remarks Id</th>
                                    <th>Remarks</th>
                                    <th>Remarks By</th>
                                    <th>Remarks Date</th>
                                </tr>
                            </thead>
                            <tbody id="remarks-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('includes/footer');?>
</div>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/clinic.js" id = "createclinic" is_user = "<?= $this->authorised_user['user_id'] ?>"></script>
<!-- page script -->
<script>
    $(function () {
      clinic_table = $('#clinic_table').DataTable({
        // Processing indicator
        "processing": true,
        // DataTables server-side processing mode
        "serverSide": true,
        // Initial no order.
        "order": [],
        // Load data from an Ajax source
        "ajax": {
          url: "<?php echo base_url('clinic/getlist'); ?>",
          type: "POST",
          data: function(d){
            d.fromDate = $(document).find("#fromDate").val(),
            d.toDate = $(document).find("#toDate").val(),
            d.approval = $(document).find("#approval").val(),
            d.approval_status = $(document).find("#approval_status").val(),
            d.application_type = $(document).find("#application_type").val()
          }
    
        },
        //Set column definition initialisation properties
        "columnDefs": [{ 
            "targets": [0,6,7,8,10],
            "orderable": false,
            
        }]
    
      });
    
      $(document).on('change', "#fromDate", function(){
        var toDate = $(document).find('#toDate').val();
        var fromDate = $(this).val();
        if(toDate != ''){
          if(fromDate < toDate){
            clinic_table.draw();
          }else{
            swal("Warning!", "Please Select Correct To Date", 'warning');
          }
        }
      });
    
      $(document).on('change', "#toDate", function(){
        var fromDate = $(document).find("#fromDate").val();
        var toDate = $(this).val();
    
        if(fromDate != ''){
          if(fromDate < toDate){
            clinic_table.draw();
          }else{
            swal("Warning!", "Please Select Correct From Date", 'warning');
          }
        }
      });
    
      $(document).on('change', "#approval", function(){
        clinic_table.draw();
      });
      $(document).on('change', "#application_type", function(){
        clinic_table.draw();
      });
    
      $(document).on('change', "#approval_status", function(){
        clinic_table.draw();
      });
    });
</script>
</body>
</html>