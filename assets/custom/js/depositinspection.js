	$( document ).ready(function() {
		$("#depositinspection_form_add :input").each(function(){
	        $(this).attr('autocomplete', 'off'); 
	    },()=>{console.log('Hello')});
        depositinspection_table();$('.selectpicker').selectpicker('refresh');
	});

	var form_add_validate = $("#depositinspection_form_add").validate({
        rules: {
            from_date:{
            	required:true,
            	date: true,
            },
            to_date:{
            	required:true,
            	date: true,	
            },
            department_id:{
            	required:true,
            	number: true,
            },
            inspection_fees:{
            	required:true,
            	number: true,
            },
            deposit_fees:{
            	required:true,
            	number : true,	
            }
        },
        errorPlacement: function(error, element) {
		    if (element.attr("name") == "department_id") {
		      	error.insertAfter(".bs-placeholder");
		    } else {
		      	error.insertAfter(element);
		    }
		},
        submitHandler: function(form,Event) { Event.preventDefault()
            var form_data = JSON.stringify($(form).serializeArray());
            $.ajax({
                type: "POST",
                url: $(form).attr('action'),
                data: JSON.parse(form_data),
                success: response => {
                	if (response.status == true) {
                		swal(response.message, {icon: "success"});
                		setTimeout(function(){ window.location.href = base_url + 'depositinspection'; }, 1000);
                	} else {
                		swal(response.message, {icon: "error"});
                	}
                },
                error: response => {
                	swal('Sorry, we have to face some technical issues please try again later.', {icon: "error"});console.log(response);
                }
            });
        },
    }); 

    $(document).on('change', '#department_id', function() { depositinspection_table(); });
    $(document).on('change', '#version', function() { depositinspection_table(); });

    function depositinspection_table() {

        let filter_department_id = $('#department_id').val(); 
        let filter_version = $('#version').val();

        var dataTable = $('#depositinspection_table').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth" : false,
            "order": [],
            "columnDefs": [
                {
                    "targets": [0,7],
                    "orderable": false,
                },
            ],
            "ajax": {
                url: base_url + 'depositinspection_datatable',
                type: "POST",
                data:{filter_department_id:filter_department_id,filter_version:filter_version}
            },
            "bDestroy": true,
        });
    }