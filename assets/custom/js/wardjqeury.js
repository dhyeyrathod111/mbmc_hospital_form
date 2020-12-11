$( document ).ready(() => {
    ward_datatable();
    $('#department_select_ward,#role_select_ward,#status_select_ward').selectpicker('destroy');
});

$("#update_ward_process").validate({
    rules: {
        department_id:{
            required:true,
            number: true,
        },
        role_id:{
            // required:true,
            number: true,
        },
        status:{
            required:true,
            number: true,
        },
        ward_title:{
            required:true,
        },
    },
    errorPlacement: function ( error, element ) {
        if (element.attr("name") === 'department_id') {
            error.insertAfter('#department_select_error');
        } else if(element.attr("name") === 'role_id'){
            error.insertAfter('#role_select_error');
        } else if(element.attr("name") === 'status'){
            error.insertAfter('#status_select_error');
        } else {
            error.insertAfter(element);
        }
        error.addClass( "ui red pointing label transition" );
    },
    submitHandler: form => {
        var form_data = JSON.stringify($(form).serializeArray());
        $('#submit_btn').text('Sending...').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: JSON.parse(form_data),
            success: response => {
                if (response.status == true) {
                    notify_success(response.message);setTimeout(()=>{ window.location.href = base_url + 'ward'; },3000);
                } else {
                    notify_error(response.message);$('#submit_btn').text('Submit').prop('disabled', false);
                }
            },
            error: response => {
                console.log(response);notify_error();$('#submit_btn').text('Submit').prop('disabled', false);
            }
        });
    },
});

const ward_datatable = () => {
     var dataTable = $('#ward_table').DataTable({
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
            url: base_url + 'ward/ward_datatable',
            type: "POST",
        },
        "bDestroy": true,
    });
}

$(document).on("change","#department_select_ward",Event => { 
	Event.preventDefault();
    let department_id = event.target.value ;
    $.ajax({
        type: "POST",
        url: base_url + 'ward/get_roles',
        data: {department_id:department_id},
        success: response => {
        	let html_str = '<option value="">---Select Role---</option>';
        	if (response.status == true) {
        		$(response.roles_list).each( index  => {
        			let one_role_data = response.roles_list[index];
        			html_str += '<option value="'+ one_role_data.role_id +'">'+one_role_data.role_title+'</option>'
        		});
        	} else {
        		notify_error('Role is not present in this department. Please create role for this deparment.')
        	}
        	$("#role_select_ward").html(html_str);
        },
        error: response => {
        	notify_error();console.log(response);
        }
    });
});



$("#create_ward_process").validate({
    rules: {
        department_id:{
        	required:true,
        	number: true,
        },
        role_id:{
        	// required:true,
        	number: true,
        },
        status:{
        	required:true,
            number: true,
        },
        ward_title:{
        	required:true,
        },
    },
    errorPlacement: function ( error, element ) {
        if (element.attr("name") === 'department_id') {
            error.insertAfter('#department_select_error');
        } else if(element.attr("name") === 'role_id'){
            error.insertAfter('#role_select_error');
        } else if(element.attr("name") === 'status'){
            error.insertAfter('#status_select_error');
        } else {
            error.insertAfter(element);
        }
        error.addClass( "ui red pointing label transition" );
    },
    submitHandler: form => {
        var form_data = JSON.stringify($(form).serializeArray());
        $('#submit_btn').text('Sending...').prop('disabled', true);
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: JSON.parse(form_data),
            success: response => {
                if (response.status == true) {
                	notify_success(response.message);setTimeout(()=>{ window.location.href = base_url + 'ward'; },3000);
                } else {
                	notify_error(response.message);$('#submit_btn').text('Submit').prop('disabled', false);
                }
            },
            error: response => {
                console.log(response);notify_error();$('#submit_btn').text('Submit').prop('disabled', false);
            }
        });
    },
});

$(document).on("click",".delete_word",Event => { Event.preventDefault();
    $('#is_deleted_id').val(Event.target.getAttribute('word_id'));
    swal({
        title: "Are you sure do you want to delete this ward?",
        text: "Once deleted, you will not be able to recover this ward!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then(willDelete => {
        if (willDelete) {
            let ward_id = $('#is_deleted_id').val();
            $.ajax({
                type: "POST",
                url: base_url + 'ward/delete',
                data:{ward_id:ward_id},
                success: response => {
                    if (response.status == true) {
                        swal(response.message, { icon: "success" });ward_datatable();
                    } else {
                        swal(response.message, { icon: "error" });
                    }   
                },
                error: response => {
                    console.log(response);notify_error();$('#submit_btn').text('Submit').prop('disabled', false);
                }
            });

            swal("Success! Your ward has been deleted!", {
                icon: "success",
            });
        } else {
            swal("Your ward is safe!");
        }
    });
});

function notify_success(message) {
    let html_str = '<div class="alert alert-info text-center"><strong>'+ message +'</strong></div>';
    $('#alert_message').fadeIn();
    $('#alert_message').html(html_str).fadeOut(4000);
}
function notify_error(message = '') {
    if (message === '') {
        message = "Sorry, we have to face some technical issues please try again later."
    } 
    let html_str = '<div class="alert alert-warning text-center"><strong>'+ message +'</strong></div>';
    $('#alert_message').fadeIn();
    $('#alert_message').html(html_str).fadeOut(4000);
}

