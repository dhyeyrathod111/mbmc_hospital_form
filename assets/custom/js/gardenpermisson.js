	$(document).on("click",".change_permission_stats",function() {
		let permission_id = $(this).attr('data_id');
		$.ajax({
	        type: "POST",
	        url: base_url + 'update_permission_status',
	        data: {permission_id:permission_id},
	        success: response => {
	        	if (response.status == true) {
	        		gaeden_permission_table();
	        	} else {
	        		swal(response.message, {icon: "error"});console.log(response);
	        	}
	        },
	        error: response => {
	        	swal('Sorry, we have to face some technical issues please try again later.', {icon: "error"});console.log(response);
	        }
	    });
	});
	function gaeden_permission_table() {
        var dataTable = $('#gaeden_permission_table').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth" : false,
            "order": [],
            "columnDefs": [
                {
                    "targets": [0,5],
                    "orderable": false,
                },
            ],
            "ajax": {
                url: base_url + 'get_garden_permission_list',
                type: "POST",
            },
            "bDestroy": true,
        });
    }
	$( document ).ready(function() {
	    $("#garden_permition_form_add :input").each(function(){
	        $(this).attr('autocomplete', 'off'); 
	    });
	    $.validator.addMethod("alphaNumericSpace", function(value, element) {
		    return this.optional(element) || value == value.match(/^[a-z A-Z0-9]+$/);
		},'Enter proper garden permisson type');
		gaeden_permission_table();
	});

	var form_edit_validate = $("#garden_permition_form_edit").validate({
        rules: {
            permition_type: {
                required: true,
                alphaNumericSpace:true,
                minlength: 5,
                maxlength: 30,
                remote: {
          			url: base_url + 'gardenpermission_edit_validation',
          			type: "post",
          			data: {permisson_id:()=>{ return $('#permission_id').val(); }}
        		}
            },
        },
        messages: {
        	permition_type:{
        		remote: 'this garden permisson is already is use.'
        	}
        },
        submitHandler: function(form,Event) { Event.preventDefault()
            var form_data = JSON.stringify($(form).serializeArray());
            $.ajax({
                type: "POST",
                url: $(form).attr('action'),
                data: JSON.parse(form_data),
                success: response => {
                	if (response.status === true) {
                		swal(response.message, {icon: "success"});
                		setTimeout(function(){ window.location.href = base_url + 'garden_permission'; }, 1000);
                	} else {
                		swal(response.message, {icon: "error"});console.log(response);
                	}
                },
                error: response => {
                	swal('Sorry, we have to face some technical issues please try again later.', {icon: "error"});console.log(response);
                }
            });
        },
    }); 

    var form_add_validate = $("#garden_permition_form_add").validate({
        rules: {
            permition_type: {
                required: true,
                alphaNumericSpace:true,
                minlength: 5,
                maxlength: 30,
                remote: {
          			url: base_url + 'garden_permission_add_validation',
          			type: "post",
        		}
            },
        },
        messages: {
        	permition_type:{
        		remote: 'this garden permisson is already is use.'
        	}
        },
        submitHandler: function(form,Event) { Event.preventDefault()
            var form_data = JSON.stringify($(form).serializeArray());
            $.ajax({
                type: "POST",
                url: $(form).attr('action'),
                data: JSON.parse(form_data),
                success: response => {
                	if (response.status === true) {
                		swal(response.message, {icon: "success"});
                		setTimeout(function(){ window.location.href = base_url + 'garden_permission'; }, 1000);
                	} else {
                		swal(response.message, {icon: "error"});console.log(response);
                	}
                },
                error: response => {
                	swal('Sorry, we have to face some technical issues please try again later.', {icon: "error"});console.log(response);
                }
            });
        },
    }); 