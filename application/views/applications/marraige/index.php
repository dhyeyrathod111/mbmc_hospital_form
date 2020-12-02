<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('includes/sidenav'); ?>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-wrapper-scroll-y my-custom-scrollbar">
                        
                        <div class="card">
                            <div class="card-body">
                                <div class = "row">
                                    <div class = "col-md-2">
                                        <label for = "from_date">From Date</label>
                                        <input type="text" name="fromDate" id = "fromDate" class = "form-control datepicker" placeholder="Please Select Date">
                                    </div>
                                    <div class = "col-md-2">
                                        <label for = "to_date">To Date</label>
                                        <input type="text" name="toDate" id="toDate" class="form-control datepicker" placeholder="Please Select Date">
                                    </div>
                                    <div class="col-md-2">
                                        <label for = "to_date">Remark</label>
                                        <select class="form-control selectpicker" id="filter_remark_data">
                                            <option value="">---Select remark---</option>
                                            <?php foreach ($appStatus as $oneStatus) : ?>
                                                <option value="<?php echo $oneStatus['status_id'] ?>"><?php echo $oneStatus['status_title'] ?></option>
                                            <?php endforeach ; ?>
                                        </select>
                                    </div>
                                    <div class = "col-md-2">
                                        <label for = "to_date">Status</label>
                                        <select class="form-control selectpicker" id="filter_activate_status">
                                            <option value="">---Select Status---</option>
                                            <option value="1">Active</option>
                                            <option value="2">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <a id="filter_reset" class="add-btn btn btn-block btn-info"> Reset Filter</a>
                                    </div>
                                    <div class="col-md-2 right">
                                        <a type="button" href="<?php echo base_url('marriage/create') ?>" class="add-btn btn btn-block btn-info">ADD</a>
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
                        <table id="marriage_table" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Application No</th>
                                    <th>Husband Name</th>
                                    <th>Wife Name</th>
                                    <th>Priest name</th>
                                    <th>Marriage Date</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Application No</th>
                                    <th>Husband Name</th>
                                    <th>Wife Name</th>
                                    <th>Mobile No</th>
                                    <th>Marriage Date</th>
                                    <th>Remark</th>
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
            <div class="modal-footer justify-content-between">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_status_remark">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Remarks</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="remarks-form" id="status_remark_form" role="form" method="POST">
                <div class="modal-body">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="road_type">Status<span class="red">*</span></label>
                                <select required class="form-control selectpicker" id="remark_status_select" name="status">
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <input type="hidden" id="app_id" name="app_id">
                                <textarea required class="form-control" id="remarks" name ="remarks" placeholder="Enter the Remarks Title"></textarea> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<input type="hidden" value="" id="pre_delete_id">
<?php $this->load->view('includes/footer');?>
</div>
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/custom/js/marriage.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        load_marriage_table();
        $(":input").each(function(){
            $(this).attr('autocomplete', 'off'); 
        });
    });

    $(document).on('change','#filter_activate_status', function(event) {
        $(this).selectpicker('refresh');
        let active_status = $(this).val();
        const dateRange = {
            fromDate: $('#fromDate').val(),
            toDate: $('#toDate').val()
        }
        let remark_id = $('#filter_remark_data').val();
        load_marriage_table(dateRange,remark_id,active_status);
    });

    $(document).on('change','#filter_remark_data', function(event) {
        $(this).selectpicker('refresh');
        let remark_id = $(this).val();
        const dateRange = {
            fromDate: $('#fromDate').val(),
            toDate: $('#toDate').val(),
        }
        load_marriage_table(dateRange,remark_id);
    });


    var validator = $( "#status_remark_form" ).validate({
        submitHandler: form => {
            var form_data = JSON.stringify($(form).serializeArray());
            $.ajax({
                type: "POST",
                url: base_url + 'update_status_remark',
                data: JSON.parse(form_data),
                success: response => {
                    if(response.status == true){
                        swal(response.message, {icon: "success"});
                        $('#modal_status_remark').modal('hide');
                    } else {
                        swal(response.message, {icon: "error"});
                    }
                    load_marriage_table();
                    $('#status_remark_form')[0].reset();
                },
                error: response => {
                    swal("Sorry, we have to face some technical issues please try again later.", {icon: "error"});
                    $('#status_remark_form')[0].reset();
                }
            });
        }
    });

    $(document).on('click', '.add_remark_modal', function(event) {
        event.preventDefault();
        let app_id = $(this).attr('application_id');
        $.ajax({
            type: "POST",
            url: base_url + 'add_remark_modal',
            data: {app_id:app_id},
            success: response => {
                if (response.status == true) {
                    $('#remark_status_select').html(response.data_stack.html_str);
                    $('#app_id').val(response.data_stack.application_id);
                    $('.selectpicker').selectpicker('refresh');
                    $('#modal_status_remark').modal('show');
                } else {
                    swal(response.message, {icon: "error"});
                }
            },
            error: response => {
                swal("Sorry, we have to face some technical issues please try again later.", {icon: "error"});
            }
        });
    });

    $(document).on('click', '.remarks_button_marriage', function(event) {
        event.preventDefault();
        let app_id = $(this).attr('application_id');
        $.ajax({
            type: "POST",
            url: base_url + 'get_marriage_application_remark',
            data: {app_id:app_id},
            success: response => {
                if (response.status == true) {
                    $('#remarks-body').html(response.html_str);$('#modal-remarks').modal('show');
                } else {
                    swal(response.message, {icon: "error"});
                }
            },
            error: response => {
                swal("Sorry, we have to face some technical issues please try again later.", {icon: "error"});
            }
        });
    });

    $(document).on('click', '#update_status', function(event) {
        $('#pre_delete_id').val($(this).attr('data_id'));
        swal({
            title: "Are you sure?",
            text: "Do you want to delete this Application.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                let app_id = $('#pre_delete_id').val();
                $.ajax({
                    type: "POST",
                    url:  base_url + 'update_marriage_application_status',
                    data: {app_id:app_id},
                    success: response => {
                        if (response.status == true) {
                            swal(response.message, {icon: "success"});
                        } else {
                            swal(response.message, {icon: "error"});
                        }
                        load_marriage_table();
                    }, 
                    error: response => {
                        swal("Sorry, we have to face some technical issues please try again later.", {icon: "error"});
                    }
                });
            } else {
                swal("Your Application is safe!");
            }
        });
    });    
    $(document).on('change', '#toDate', function(event) {
        event.preventDefault();
        const dateRange = {
            fromDate: $('#fromDate').val(),
            toDate: $('#toDate').val()
        }
        let remark_id = $('#filter_remark_data').val();
        load_marriage_table(dateRange,remark_id);
    });
    $(document).on('click', '#filter_reset', function(event) {
        $('#fromDate').val('');$('#toDate').val('');$('#filter_remark_data').val('');$('#filter_activate_status').val('');
        $('.selectpicker').selectpicker('refresh');
        load_marriage_table();
    });
    function load_marriage_table(dateRange = [],remark_id,active_status) {
        var dataTable = $('#marriage_table').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth" : false,
            "order": [],
            "columnDefs": [
                {
                    "targets": [6],
                    "orderable": false,
                },
            ],
            "ajax": {
                url: base_url + '/marriage_datatable',
                type: "POST",
                data: {dateRange:dateRange,remark_id:remark_id,active_status:active_status}
            },
            "bDestroy": true,
        });
    }
</script>

</body>
</html>