
let close_application_validation = $("#close_application_form").validate({
    rules: {
        refundable_amount:{
            required:true,
        },
        payment_status:{
            required:true,
        },
        payment_type:{
            required:true,  
        },
        remark_note:{
            required:true,
            noSpace:true,
        }
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);
        error.addClass("ui red pointing label transition");
        error.insertAfter(element.after());
    },
    submitHandler: function(form) {
        $('#submit_btn').text('Processing...').prop('disabled', true);
        var form_data = JSON.stringify($(form).serializeArray());
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: JSON.parse(form_data),
            success: response => {
                if (response.status) {
                    notify_success(response.message);
                } else {
                    notify_error(response.message);
                }
                setTimeout(()=>window.location.reload(),3000);
            },
            error: response => {
                console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
            }
        });
    },
});



$(document).on('click','.file_close_modal_selecter', Event => { Event.preventDefault();
    let application_id = Event.target.getAttribute('application_id');
    $.ajax({
        type: "POST",
        url: base_url + 'pwd/close_application_form',
        data:{application_id:application_id},
        success: response => {
            if (response.status) {
                $('#close_application_form').html(response.html_str);
                $('#file_close_modal').modal('show');
            } else {
                swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
            }
        },
        error: response => {
            console.log(response);
            swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
        }
    });
});

$(document).on('click','.file_close_selecter', Event => { Event.preventDefault();
    $('.file_close_selecter').text('Processing...').prop('disabled', true);
    let application_id = Event.target.getAttribute('application_id');
    $.ajax({
        type: "POST",
        async:true,
        url: base_url + 'pwd/file_close_process',
        data:{application_id:application_id},
        success: response => {
            $('.file_close_selecter').text('Close application').prop('disabled', false);
            if (response.status) $('.success_close_app_text').text(response.message).css("color", "green");
            setTimeout(()=>window.location.reload(),3000);
        },
        error: response => {
            console.log(response);
            swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
        }
    });
});


$(document).on('click','.submit_btn_joint_ref', Event => { Event.preventDefault();
    $('#refrence_order_popup').modal('hide');

// 	$('#is_close').val(Event.target.getAttribute('is_close'));
// 	swal({
// 	  	title: "Are you sure?",
// 	  	text: (Event.target.getAttribute('is_close') == 1) ? "Do you want to close this joint visit?" : "Do you want to create and send reference number?",
// 	  	icon: "warning",
// 	  	buttons: true,
// 	  	dangerMode: true,
// 	}).then((willDelete) => {
// 	  	if (willDelete) {
// 	    	$( "#refrence_order_form" ).submit();
// 	  	} else {
// 	    	swal("None action has been gicen by you.");
// 	  	}
// 	});
});



let refrence_order_form = $("#refrence_order_form").validate({
    rules: {
        refrence_number:{
        	required: true,
		},
		joint_visit_remark:{
			required: true,
			noSpace:true,
		}
	},
	errorPlacement: function (error, element) {
		error.insertAfter(element);
		error.addClass("ui red pointing label transition");
		error.insertAfter(element.after());
	},
    submitHandler: function(form) {
    	$('.submit_btn_joint_ref').prop('disabled', true);
        var form_data = JSON.stringify($(form).serializeArray());
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: JSON.parse(form_data),
            success: response => {
            	$('.submit_btn_joint_ref').prop('disabled', false);
                if (response.status) {
                    swal("Success..!!!",response.message, "success");
                } else {
                    swal("Opps..!!!",response.message, "error");
                }
                setTimeout(()=>{
                    $('#refrence_order_popup').modal('hide');window.location.reload();
                },3000);
            },
            error: response => {
                console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
                $('#refrence_order_popup').modal('hide');
            }
        });
    },
});

$(document).on('click','#refrence_order', Event => { Event.preventDefault();
	let app_id = Event.target.getAttribute('app_id');
	$.ajax({
        type: "POST",
        url: base_url + 'pwd/refrence_order_by_appid',
        data:{app_id:app_id},
        success: response => {
        	if (response.status) {
        		$('#refrence_order_form').html(response.html_str);
        		$('#refrence_order_popup').modal('show');
        	} else {
        		console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
        	}
        },
        error: response => {
        	console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
        }
    });
});



$(document).on('click','.jv_ref_selecter', Event => { Event.preventDefault();
	reference_validation.resetForm();$('#js_ref_form').trigger("reset");
	let app_ai_id = Event.target.getAttribute('application_id');
	$('#ref_no_application_id').val(app_ai_id);
	$('#jv_getrefno_modal_popup').modal('show');
});


$(document).on('change','.road_info_selecter',Event => {
  	if (!/^[a-zA-Z ]*$/.test(event.target.value) || event.target.value.slice(0,1) == " ") {
        if (($.isNumeric(event.target.value) && event.target.name == "start_point[]") || ($.isNumeric(event.target.value) && event.target.name == "end_point[]")) {  
            swal("Warning!", "Please enter valid road information.", "warning");event.target.focus();event.target.value = '';
        } 
  	}
  	$('.road_info_selecter').each((index,oneInputNode) =>{
  		if (oneInputNode.value == '') {
  			oneInputNode.style = "border: solid red 1px";
  		} else {
  			oneInputNode.style = 'border: ""';
  		}
  	});
});



let reference_validation = $("#js_ref_form").validate({
    rules: {
        js_ref_number:{
        	required: true,
        	remote: {
		        url: base_url + 'pwd/validate_jv_refno',
		        type: "post",
		        data: {
		        	app_id:()=>{ return $('#ref_no_application_id').val(); }
		        }
		    }
		},
	},
	messages: {
		js_ref_number:{
        	required: 'Enter joint extention reference number.',
        	remote: 'Please enter valid reference number.'
		},
	},
	errorPlacement: function (error, element) {
		error.insertAfter(element);
		error.addClass("ui red pointing label transition");
		error.insertAfter(element.after());
	},
    submitHandler: function(form) {
    	$('#submit_btn').text('Processing...').prop('disabled', true);
        var form_data = JSON.stringify($(form).serializeArray());
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: JSON.parse(form_data),
            success: response => {
				if (response.status) {
					window.location.replace(response.redirect_url);
				} else {
					notify_error(response.message);
				}
            },
            error: response => {
        		notify_error();console.log(response);
            }
        });
    },
});


function notify_success_pv(message) {
      let html_str = '<div class="alert alert-info text-center"><strong>'+ message +'</strong></div>';
      $('#alert_message_pv').fadeIn();
      $('#alert_message_pv').html(html_str).fadeOut(4000);
  }
  function notify_error_pv(message = '') {
      if (message === '') {
          message = "Sorry, we have to face some technical issues please try again later."
      } 
      let html_str = '<div class="alert alert-warning text-center"><strong>'+ message +'</strong></div>';
      $('#alert_message_pv').fadeIn();
      $('#alert_message_pv').html(html_str).fadeOut(4000);
  }

$(document).on("click",".pv_action",Event => { Event.preventDefault();
	let is_approve = Event.target.getAttribute('is_approve');
	let app_id = $('#pwd_app_ai_id').val();let pay_id = $('#pay_id').val();
	$('.pv_action').text('Processing...').prop('disabled', true);
	$.ajax({
        type: "POST",
        url: base_url+'pwd/pv_process',
        data:{is_approve:is_approve,app_id:app_id,pay_id:pay_id},
        success: response => {
        	if (response.status == true) {
        		notify_success_pv(response.message);setTimeout(()=>location.reload(),3000);
        	} else {
        		notify_error_pv(response.message);
        	}
        	setTimeout(()=>location.reload(),3000);
        },
        error: response => {
        	notify_error_pv();console.log(response);
        	setTimeout(()=>location.reload(),2000);
        }
    });
});



$(document).on("click",".payment_verification_selecter",Event => { Event.preventDefault();
	let app_ai_id = Event.target.getAttribute('application_id');
	$.ajax({
        type: "POST",
        url: base_url + 'pwd/payment_verification',
        data:{app_ai_id:app_ai_id},
        success: response => {
        	if (response.status == true) {
        		$('#paymnet_verification_body').html(response.html_str);
        		$('#payment_verification_modal').modal('show');
        	} else {
        		notify_error(response.message);
        	}
        },
        error: response => {
        	notify_error();console.log(response);
        }
    });

});

$(document).on("click","#extention_approve_btn",Event => { Event.preventDefault();
	let extention_id = $('#extention_id').val();
	let pwd_app_id = $('#pwd_app_id').val();
	$('#extention_reject_btn').hide();
	$('#extention_approve_btn').text('Processing...').prop('disabled', true);
	$.ajax({
        type: "POST",
        url: base_url + 'pwd/extention_approvel_process',
        data:{extention_id:extention_id,pwd_app_id:pwd_app_id},
        success: response => {
        	if (response.status == true) {
        		notify_success(response.message);
        	} else {
        		notify_error(response.message);
        	}
        	setTimeout(()=>location.reload(),2000);
        },
        error: response => {
        	notify_error();console.log(response);
        }
    });
});

$(document).on("click","#extention_reject_btn",Event => { Event.preventDefault();
	let extention_id = $('#extention_id').val();
	let pwd_app_id = $('#pwd_app_id').val();
	$('#extention_approve_btn').hide()
	$('#extention_reject_btn').text('Processing...').prop('disabled', true);
	$.ajax({
        type: "POST",
        url: base_url + 'pwd/extention_approvel_process',
        data:{extention_id:extention_id,pwd_app_id:pwd_app_id,is_rejected:true},
        success: response => {
        	if (response.status == true) {
        		notify_success(response.message);setTimeout(()=>location.reload(),2000);
        	} else {
        		notify_error(response.message);
        	}
        },
        error: response => {
        	notify_error();console.log(response)
        }
    });
});



$(document).on("click",".extention_approval_selecter",Event => {
	let app_id = Event.target.getAttribute('app_id');
	$.ajax({
        type: "POST",
        url: base_url + 'pwd/extention_approvel_datafetch',
        data:{app_id:app_id},
        success: response => {
        	if (response.status == true) {
                $('.ext_approvel_body').html(response.html_str);
                $('#extention_approvel_modal').modal('show');
        	} else {
        		swal("Warning!",response.message,"warning");
        	}
        },
        error: response => {
        	swal("Warning!", "Sorry, we have to face some technical issues please try again later.", "warning");
        	console.log(response);
        }
    });
});


$(document).on("click",".extention_selecter",Event => { Event.preventDefault();
	let app_id = Event.target.getAttribute('application_id');
    $.ajax({
        type: "POST",
        url: base_url + 'pwd/get_user_extention_form',
        data:{app_id:app_id}, 
        success: response => {
            if (response.status) {
                $('#extention_form').html(response.html_str);
                $("#ext_date").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
                $("#ext_to_date").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
                $('#extention_modal').modal('show');
            } else {
                swal("Error..!!!",response.message, "error");
            }
        },
        error: response => {
            swal("Warning!", "Sorry, we have to face some technical issues please try again later.", "warning");
            console.log(response);
        }
    });
});

const checkJointVisitDone = app_id => {
	$.ajax({
        type: "POST",
        url: base_url + 'pwd/get_old_joint_visit',
        data:{app_id,app_id},
        success: response => {
        	if (response.status) {
        		let warning_str = 'Joint visit has been already scheduled on the selected date '+response.joint_visit.date+' for the length of '+ response.joint_visit.length +' meter.';
        		$('#joint_visit_remark_alert').text(warning_str);
        		$('#joint_visit_submit_button').hide();
        	} else {
        		$('#joint_visit_remark_alert').text('');$('#joint_visit_submit_button').show();
        	}
        },
        error: response => {
        	console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
        }
    });
}

$(document).on("click","#joint_visit_action",Event => { Event.preventDefault();
	let app_id = Event.target.getAttribute('app_id');
	checkJointVisitDone(app_id);
	$('#jv_model_app_id').val(app_id)
	$('#joint_visit_modal').modal('show');
});


const extention_date_validation = (start_date,end_date,application_id) => {
    var response_status = "";
    $.ajax({
        type: "POST",
        async:false,
        url: base_url + 'pwd/validation_extention',
        data:{start_date:start_date,end_date:end_date,application_id:application_id},
        success: response => {
            response_status = response;
        },
        error: response => {
            debugger ;
            response_status = "error"
        }
    });
    return response_status;
}


$("#extention_form").validate({
    rules: {
        ext_date:{
        	required: true,
		},
		ext_to_date:{
			required: true,	
		},
		description:{
			required: true,
		}
	},
	errorPlacement: function (error, element) {
		error.insertAfter(element);
		error.addClass("ui red pointing label transition");
		error.insertAfter(element.after());
	},
    submitHandler: function(form) {
        var application_id = $('#app_id').val();
    	var From_date = new Date($('#ext_date').val());
		var To_date = new Date($('#ext_to_date').val());
        let validate_status = extention_date_validation($('#ext_date').val(),$('#ext_to_date').val(),application_id);
        if (validate_status.status == false) {
            notify_error(validate_status.message); return ;
        } else if (To_date <= From_date) {
            notify_error("Your start date is greater then your end date. its invalid formate."); return ;
        }
    	$('#submit_btn_ext').text('Processing...').prop('disabled', true);
        var form_data = JSON.stringify($(form).serializeArray());
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: JSON.parse(form_data),
            success: response => {
				if (response.status == true) {
	        		notify_success(response.message);setTimeout(()=>location.reload(),2000);
	        	} else {
	        		notify_error(response.message);
	        	}
            },
            error: response => {
        		notify_error();console.log(response);
            }
        });
    },
});


$("#joint_visit_form").validate({
    rules: {
        jv_length:{
        	required: true,
        },
        jv_date:{
        	required: true,	
        }
    },
    submitHandler: function(form) {
        var form_data = JSON.stringify($(form).serializeArray());
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: JSON.parse(form_data),
            success: response => {
            	if (response.status) {
            		swal("Success..!!!", response.message, "success");
            		setTimeout(()=>{ location.reload() }, 3000);
            	} else {
            		swal("Opps..!!!", response.message, "error");
            	}
            },
            error: response => {
            	console.log(response);swal("Error..!!!","Sorry, we have to face some technical issues please try again later.", "error");
            }
        });
    },
});


$(document).on("click","#add_road_type",Event => { Event.preventDefault();
	let main_node = $("#oneRoadType").clone();
	main_node.find("input").each((index , node) => node.value = '');
	$(".tableBody").append(main_node);
});

$(document).on("click",".delete_row",Event => { Event.preventDefault();
	if ($('.table-responsive-sm tr').length > 2) {
		Event.target.parentElement.parentElement.parentElement.remove();
	}
});


$(document).on("change","#work_end_date",Event => { Event.preventDefault();
	var From_date = new Date($('#work_start_date').val());
	var To_date = new Date($('#work_end_date').val());
	if (To_date >= From_date) {
		var days = daysdifference(From_date, To_date);
		$('#total_days_of_work').val(days+1);
	} else {
		$('#work_end_date').val('').datepicker("hide");$('#total_days_of_work').val(0);
		swal("Please select end date greater than start date.");
	}
});

function daysdifference(firstDate, secondDate){
    var startDay = new Date(firstDate);
    var endDay = new Date(secondDate);
    var millisBetween = startDay.getTime() - endDay.getTime();
    var days = millisBetween / (1000 * 3600 * 24);
    return Math.round(Math.abs(days));
}



$(document).on("change","#company_name_select",Event => { 
	Event.preventDefault();
    let comapany_id = event.target.value ;
    $.ajax({
        type: "POST",
        url: base_url + 'pwd/get_company_address',
        data: {comapany_id:comapany_id},
        success: response => {
        	let html_str = '<option value="">---Select company address---</option>';
        	if (response.status == true) {
        		$(response.company_add).each( index  => {
        			let one_address_data = response.company_add[index];
        			html_str += '<option value="'+ one_address_data.address_id +'">'+one_address_data.company_address+'</option>'
        		});
        	} 
        	$("#company_address").html(html_str).selectpicker('refresh');
        },
        error: response => {
        	notify_error();console.log(response);
        }
    });
});


$(document).ready(function () {

    if ($('#is_authority_edit').val()) {
        document.getElementById("request_letter").disabled = true;
        $('#company_address').selectpicker('destroy').attr("readonly",true);
        $('#company_name_select').selectpicker('destroy').attr("readonly",true);
    } else {
        $("#work_start_date").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd' });
        $("#work_end_date").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
    }

	$('.road_type_select').selectpicker('destroy');
	var is_user = createPwd.getAttribute("is_user");
	$('#request_letter').change(function () {
		var file = $('#request_letter')[0].files[0].name;
		$('#request_letter_name').text(file);
		$('#request_letter_name_id').val(file);
	});

	$('#geo_location_map').change(function () {
		var file = $('#geo_location_map')[0].files[0].name;
		$('#geo_map_name').text(file);
		$('#geo_map_name_id').val(file);
	});


	const Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 3000
	});

	function CapitalizeWord(str) {
		return str.replace(/\w\S*/g, function (txt) {
			return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
		});
	}	

	// $('#applicant_name, #applicant_address, #company_name, #contact_person, #name_company_head, #assistant_name, #road_name, #assistant_designation, #company_head_designation, #landmark').keyup(function () {
	// 	var text = $(this).val();
	// 	text = CapitalizeWord(text);
	// 	$(this).val(text);
	// });


    $(document).on('change','#applicant_mobile_no,#applicant_alternate_no', Event => {
        if ($('#applicant_mobile_no').val() == $('#applicant_alternate_no').val()) {
            swal("Warning!", "Mobile number and Alternate mobile number cannot be same !", "warning");
            Event.target.value = '';
        }  
    });

    $(document).on('change','#company_head_designation,#assistant_designation', Event => {
        if ($('#company_head_designation').val() == $('#assistant_designation').val() && Event.target.value != '') {
            swal("Warning!", "Company Head and Assistant cannot have the same Designation.", "warning");
            Event.target.value = '';
        }
    });


	//check for alternate and user_mobile
	// $(document).on("change", "#applicant_mobile_no", function () {
	// 	var usermobile = $(document).find("#applicant_mobile_no").val();
	// 	if (usermobile == $(this).val()) {
	// 		$(document).find('#error').remove();
	// 		$(this).parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({ 'color': 'red', 'font-weight': '400 !important' });
	// 		$(this).val("");
 //            setTimeout(()=>{
 //                $('#error').hide();
 //            },4000);
 //            return;
	// 	} 
	// });
	//End check for alternate and user_mobile

	jQuery.validator.addMethod("lettersonly", function (value, element) {
		return this.optional(element) || /^[a-z\s]+$/i.test(value);
	}, "Letters only please");

	jQuery.validator.addMethod("noSpecialCharacter", function (value, element) {
		return this.optional(element) || /^[a-zA-Z0-9,. ]+$/i.test(value);
	}, "You can not enter special characters.");

	// jQuery.validator.addMethod("address_validation", function (value, element) {
	// 	return this.optional(element) || /^[a-z0-9\s]+$/i.test(value);
	// }, "Please enter valid address.");

	$(document).on('change', '.applicant_address_validation', event => {
		if ($.isNumeric(event.target.value) || !/^[a-zA-Z0-9- ]*$/.test(event.target.value)) {
			event.target.value = '';swal("Warning!", "Please enter valid address.", "warning");
		}
	});



	jQuery.validator.addMethod("noSpace", function(value, element) { 
	  	return value != "" && value.slice(0,1) != " "; 
	}, "No space please and don't leave it empty");


	





	// new submit function
	$("#pwd-form").validate({
		rules: {
			application_no: {
				required: true,
				noSpace:true,
			},
			applicant_name: {
				required: true,
				noSpace:true,
			},
			applicant_email_id: {
				required: true,
				email: true,
				noSpace:true,
			},
			applicant_mobile_no: {
				required: true,
				minlength:10,
				maxlength: 10,
				number: true,
				noSpace:true,
			},
			applicant_address: {
				required: true,
				noSpace:true,
			},
			company_name: {
				required: true,
				noSpace:true,
			},
			company_address: {
				required: true,
				noSpace:true,
			},
			landline_no: {
				required: true,
				minlength: 8,
				maxlength: 12,
				number: true,
				noSpace:true,
			},
			contact_person: {
				required: true,
				lettersonly: true,
				noSpace:true,
			},
			name_company_head: {
				required: true,
				lettersonly: true,
				noSpace:true,
			},
			company_head_number: {
				required: true,
				number: true,
				minlength: 10,
				maxlength: 10,
				noSpace:true,
			},
			company_head_designation: {
				required: true,
				lettersonly: true,
				noSpace:true,
			},
			assistant_name: {
				required: true,
				lettersonly: true,
				noSpace:true,
			},
			assistant_number: {
				required: true,
				number: true,
				minlength: 10,
				maxlength: 10,
				noSpace:true,
			},
            applicant_alternate_no:{
                minlength:10,
                maxlength: 10,
                number: true,
            },
			assistant_designation: {
				required: true,
				lettersonly: true,
				noSpace:true,
			},
			road_name: {
				required: true,
				noSpace:true,
                maxlength: 230,
				noSpecialCharacter:true,
			},
			landmark: {
				required: true,
				lettersonly: true,
				noSpace:true,
			},
			work_start_date: {
				required: true,
				noSpace:true,
			},
			work_end_date: {
				required: true,
				noSpace:true,
			},
			total_days_of_work: {
				required: true,
				noSpace:true,
			},
			permission_type:{
				required: true,
				noSpace:true,
			},
		},
		messages: {
			application_no: "Application Number Not Generated Please Refresh",
			applicant_name: "Please Enter Name",
			applicant_email_id: "Please Enter Email",
			applicant_mobile_no: "Please Enter Mobile Number",
			applicant_address: "Please Enter Address",
			company_name: "Please Select Name Of Company",
			company_address: "Please Select Address Of Company",
			landline_no: "Please Enter Number Of Company Head",
			contact_person: "Please Enter Name Of Contact Person",
			name_company_head: "Please Enter Name Of Company Head",
			company_head_number: "Please Enter Number of Company Head",
			company_head_designation: "Please Enter Designation Of Company Head",
			assistant_name: "Please Enter Name Of Assistant",
			assistant_number: "Please Enter Number Of Assistant",
			assistant_designation: "Please Enter Designation Of Assistant",
			road_name: "Please Enter Name Of Road (max 230 characters allowed)",
			landmark: "Please Enter landmark",
			work_start_date: "Please Select Work Start Date",
			work_end_date: "Please Select Work End Date",
			total_days_of_work: "Some Error Occured Please Try Again",
			permission_type: "Please Select Permission Type"
		},
		errorPlacement: (error, element) => {
				error.addClass( "ui red pointing label transition" );
				// error.insertAfter( element.after() );
			// console.log(element.parent().prop("className"));
			if (element.parent().prop("className") == 'dropdown bootstrap-select form-control') {
				if (element.parent().parent().prop("className") == 'custom-file') {
					element.parent().parent().parent().append(error);
				} else {
					element.parent().parent().append(error);
				}
			} else {
				element.parent().append(error);
			}
		},
		invalidHandler: (event, validator) => {
			var errors = validator.numberOfInvalids()
			// console.log(errors);
				if(errors) {
						var message = errors == 1
						? 'You missed 1 field. It has been highlighted'
						: 'You missed ' + errors + ' fields. They have been highlighted';
						$("div.error span").html(message);
						$("div.error").show();
				} else {
						$("div.error").hide();
				}
		},
		submitHandler: function (form, e) { 
			e.preventDefault(); 
			var form_data = new FormData(document.getElementById("pwd-form"));
			var statusInput = false;
			
			if ($(".company_designation_validation")[0].value == $(".company_designation_validation")[1].value) {
				$(".company_designation_validation")[0].value = '';$(".company_designation_validation")[1].value = '';
				swal("Opps..!!!", "designation of the Comopany Head and Designation Of Assistant is not same", "error");
				return ;
			} 


			if(is_user == '1') {
				var pageType = $('form').data('page');
				//true
				if(pageType != 'edit' && $('#reference_no').val() == 0){
					if($("#geo_location_map").val() == ''){
						$("#geo_location_map").parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({ 'color': 'red', 'font-weight': '400 !important' });

						setTimeout(function(){
							$(document).find('#error').remove();
						}, 4000);
						return;
					}

					if($("#request_letter").val() == ''){
						$("#request_letter").parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({ 'color': 'red', 'font-weight': '400 !important' });

						setTimeout(function(){
							$(document).find('#error').remove();
						}, 4000);
						return;
					}
				}
			} 

			//check letter no
			var letter_no = $("#letter_no").val();
			var pattern = /^[A-Za-z0-9 '-_./]+$/;
			if(letter_no != ''){
				if(!pattern.test(letter_no)){
					$("#letter_no").parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({ 'color': 'red', 'font-weight': '400 !important' });
					
					setTimeout(function(){
						$(document).find('#error').remove();
					}, 4000);
					return;
				}
			}
			//end check letter no

			//type of road validation
			var totalCntSelct = 0;
			var totalRoadSelct = 0;
			$(".road_type_select").each(function () {
				totalCntSelct++;
				if ($(this).val() == '') {
					$(this).parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({ 'color': 'red', 'font-weight': '400 !important' });
					
					setTimeout(function(){
						$(document).find('#error').remove();
					}, 4000);
					return;
				} else {
					totalRoadSelct++;
				}
			});

			if (totalCntSelct == totalRoadSelct) {
				statusInput = true;
			} else {
				return ;
			}



			//End type of road validation

			//start point validation
			var totalStrtCnt = 0;
			var totalStrtPntCnt = 0;
			$(".start_point").each(function () { 
				totalStrtCnt++;
				if ($(this).val() == '' || $(this).val().slice(0,1) == " ") {
					$(this).parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({ 'color': 'red', 'font-weight': '400 !important' });
					
					setTimeout(function(){
						$(document).find('#error').remove();
					}, 4000);
					return;
				} else {
					totalStrtPntCnt++;
				}
			});
			
			if (totalStrtCnt == totalStrtPntCnt) {
				statusInput = true;
			} else {
				return ;
			}
			//End start point validation

			//end point validation
			var totalEndCnt = 0;
			var totalEndPntCnt = 0;
			$(".end_point").each(function () {
				totalEndCnt++;
				if ($(this).val() == '' || $(this).val().slice(0,1) == " ") {
					$(this).parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({ 'color': 'red', 'font-weight': '400 !important' });
					
					setTimeout(function(){
						$(document).find('#error').remove();
					}, 4000);
					return;
				} else {
					totalEndPntCnt++;
				}
			});

			if (totalEndCnt == totalEndPntCnt) {
				statusInput = true;
			} else {
				return ;
			}
			//End end point validation

			//total length validaiton
			var totallenCnt = 0;
			var totallengthCnt = 0;
			$(".total_length").each(function () {
				totallenCnt++;
				if ($(this).val() == '' || $(this).val().slice(0,1) == " ") {
					$(this).parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({ 'color': 'red', 'font-weight': '400 !important' });
					
					setTimeout(function(){
						$(document).find('#error').remove();
					}, 4000);
					return;
				} else {
					totallengthCnt++;
				}
			});

			if (totallenCnt == totallengthCnt) {
				statusInput = true;
			} else {
				return;
			}
			//End total length validation

			let roadStatus = true;let lastNode = ''; 
			$('.road_info_selecter').each((index,oneInputNode) =>{
				if (oneInputNode.value == '') {
					oneInputNode.style = "border: solid red 1px";
					lastNode = oneInputNode;
					roadStatus = false;return;
				}
			});
			if (roadStatus == false) {
				swal("Warning!","Please enter all road information field.", "warning");
				lastNode.focus();return ;
			}

			//submit form
			if (statusInput) {
				swal({
					title: 'Are You Sure Want To Submit?',
					text: '',
					icon: 'warning',
					buttons: true,
					dangerMode: true,
				}).then((willactive) => {
					if(willactive){
						$.ajax({
							type: 'POST',
							url: base_url + 'pwd/save',
							dataType: "Json",
							data: form_data,
							processData: false,
							contentType: false,
							cache: false,
							async: false,
							success: function (res) {
								if (res.status == '1') {
									swal("Good Job!", res.messg, "success")
										.then((value) => {
											if (is_user == '1') {
												window.location = base_url + 'pwd/pwduserlist';
											} else {
												window.location = base_url + 'pwd';
											}
	
										});
								} else if (res.status == '2') {
									swal("Warning!", res.messg, "warning");
								}
							},
						});
					}
				});
			} else {
				swal("Warning!", "Error Occured Please Try Again", "error");
			}
			
			//End Submit Form
		}
	});
	// End new Submit function

	//old submit function
	$("#pwd-form_old").validate({
		rules: {
			application_no: {
				required: true,
			},
			applicant_name: {
				required: true,
				lettersonly: true
			},
			applicant_email_id: {
				required: true,
				email: true,
			},
			applicant_mobile_no: {
				required: true,
				maxlength: 10,
				number: true
			},
			landmark:{
				required: true,
			},
			applicant_address: {
				required: true,
			},
			company_name: {
				required: true,
			},
			company_address: {
				required: true,
			},
			landline_no: {
				required: true,
				maxlength: 10,
				number: true
			},
			contact_person: {
				required: true,
				lettersonly: true
			},
			permission_type:{
				required: true,
			},

			road_name:{
				required: true,
			},
			work_start_date:{
				required: true,
			},
			work_end_date:{
				required: true,
			},

			name_company_head: {
				required: true,
				lettersonly: true
			},
			company_head_designation: {
				required: true,
				lettersonly: true
			},
			company_head_number: {
				required: true,
				maxlength: 10,
				number: true
			},
			assistant_number: {
				required: true,
				maxlength: 10,
				number: true
			},
			assistant_name: {
				required: true,
			},
			assistant_designation: {
				required: true,
			},

		},
		

		errorPlacement: function (error, element) {
			if (element.attr("name") == 'company_name') {
		        error.insertAfter('#company_name_error');
		    } else if(element.attr("name") == 'permission_type') {
		    	error.insertAfter('#permission_type_error');
		    } else {
        		error.insertAfter(element);
      		}
			error.addClass("ui red pointing label transition");
			error.insertAfter(element.after());
		},

		invalidHandler: function (event, validator) {
			// 'this' refers to the form
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1 ?
					'You missed 1 field. It has been highlighted' :
					'You missed ' + errors + ' fields. They have been highlighted';
				$("div.error span").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},

		submitHandler: function (form, e) {
			e.preventDefault();
			var form_data = new FormData(document.getElementById("pwd-form"));
			$.ajax({
				type: 'POST',
				url: base_url + 'pwd/save',
				dataType: "Json",
				data: form_data,
				processData: false,
				contentType: false,
				cache: false,
				async: false,
				success: function (res) {
					if (res.status == '1') {
						swal("Good Job!", res.messg, "success")
							.then((value) => {
								if (is_user == '1') {
									window.location = base_url + 'pwd/pwduserlist';
								} else {
									window.location = base_url + 'pwd';
								}

							});
					} else if (res.status == '2') {
						swal("Warning!", res.messg, "warning");
					}
				},
			});
			return false;
		}
	});
	//End old submit function

	const checkDeficitLIB = app_id => {
		let response_status = "";
		$.ajax({
	        type: "POST",
	        async : false,
	        url: base_url + 'pwd/check_defect_laiblity',
	        data: {app_id:app_id},
	        success: response => {
	        	if (response.laiblity_pending == 0) {
	        		response_status = response.laiblity_pending;
	        	} else {
	        		response_status = response.laiblity_pending;
	        	}
	        },
	        error: response => {
	        	console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
	        }
	    });
	    return response_status;
	}

	const is_approved_access = app_id => {
		let response_status = "";
		$.ajax({
	        type: "POST",
	        async : false,
	        url: base_url + 'pwd/check_approve_access',
	        data: {app_id:app_id},
	        success: response => {
	        	if (response.access_status) {
	        		response_status = response.access_status
	        	} else {
	        		response_status = response.access_status;
	        	}
	        },
	        error: response => {
	        	console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
	        }
	    });
	    return response_status;	
	}

	const getWardData = () => {
		let response_status = "";
		$.ajax({
	        type: "POST",
	        async : false,
	        url: base_url + 'pwd/getward',
	        success: response => {
	        	if (response.status) {
	        		response_status = response.wardData
	        	} 
	        },
	        error: response => {
	        	console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
	        }
	    });
	    return response_status;
	}

    const check_application_is_rejected = app_id => {
        let response_status = '';
        $.ajax({
            type: "POST",
            async : false,
            url: base_url + 'pwd/rejected_application_test',data: {app_id:app_id},
            success: response => {
                if (response.status) {
                    response_status = response; 
                } 
            },
            error: response => {
                console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
            }
        });
        return response_status;
    }

    const file_is_closed = app_id => {
        let response_status = '';
        $.ajax({
            type: "POST",
            async : false,
            url: base_url + "pwd/check_file_close",
            data: {app_id:app_id},
            success: response => {
                response_status = response;
            },
            error: response => {
                console.log(response);swal("Opps..!!!","Sorry, we have to face some technical issues please try again later.", "error");
            }
        });
        return response_status; 
    }




	$(document).on('click', '.status_button', function () {
		var pwd_id = $(this).attr('data-pwd');
		var dept_id = $(this).attr('data-dept');
		var app_id = $(this).attr('data-app');
		var status = $(this).attr('data-status');
		var role_id = $(this).attr('data-role');
		var user_id = $(this).attr('data-user');
		var ward_id = $(this).attr('ward_id');
		$('#status').val(status);
		$('#status').selectpicker('refresh');

		// alert(status);
		if (status != '') {
			$('#pwd_id').val(pwd_id);	
			$('#dept_id').val(dept_id);
			$('#app_id').val(app_id);
			$('#ward_id').val(ward_id);
			$('#status').selectpicker('refresh');
			$('#status').html('');

			let approvel_access_status = is_approved_access(app_id);
            let is_rejected_app = check_application_is_rejected(app_id);

            let file_closed = file_is_closed(app_id);

			if (checkDeficitLIB(pwd_id) == 0 && approvel_access_status && !is_rejected_app.status && !file_closed.status) {

				$('.remark_save_btn').show();$('#remark_alert').text();
				$.ajax({
					type: 'POST',
					async : false,
					url: base_url + 'status/getStatusByDeptRole',
					dataType: "Json",
					data: {
						'dept_id': dept_id,
						'status': status,
						'user_id': user_id,
						'role_id': role_id
					},
					success: function (res) {
						var status = res.status
						var result = res.result;
						var option = '<option value="">---Select Status---</option>';
						if (res.status == '1') {
							$.each(result, function (index, val) {
								option += "<option value='" + val.status_id + "'>" + val.status_title + "</option>";
							});
							let role_id = res.result[0].role_id;let this_ward_id = $('#ward_id').val();
							let allActiveWard = getWardData();
							let html_str = '<option value="">---Select Ward---</option>';
							allActiveWard.forEach(oneWard => {
								let select_mark = (oneWard.ward_id == $('#ward_id').val()) ? 'selected' : '' ;
								html_str += '"<option '+ select_mark +' value="'+ oneWard.ward_id +'">'+ oneWard.ward_title +'</option>"'
							});
							$('#ward_selectre').html(html_str).selectpicker('destroy');
							if (role_id != 3) $('#ward_selectre').prop('disabled', true);
						} else if (res.status == '2') {
							option = "";
						}
						$('#status').html(option);
						$('#status').selectpicker('refresh');
					},
				});
			} else {
				$('.remark_save_btn').hide();
				if (approvel_access_status == false) {
					$('#remark_alert').text('You can not approved this document please contact with higher authority');
				} else if (is_rejected_app.status) {
                    $('#remark_alert').text(is_rejected_app.message);
                } else if(file_closed.status){
                    $('#remark_alert').text(file_closed.message);
                } else {
					$('#remark_alert').text('First you have to fill all defect laiblity.');
				}
			}

		}
	});

	$(document).on('click', '.remarks_button', function () {
		var pwd_id = $(this).attr('data-pwd');
		var app_id = $(this).attr('data-app');

		// alert(app_id);
		if (app_id != '') {

			$('#status').html('');
			$.ajax({
				type: 'POST',
				url: base_url + 'remarks/remarksbyid',
				dataType: "Json",
				data: {
					'app_id': app_id
				},
				success: function (res) {

					var status = res.status
					var result = res.remarks;
					var tbody = '';
					if (res.status == '1') {
						// console.log(res);
						$.each(result, function (index, val) {
							// console.log(index);
							tbody += '<tr><td>' + val.id + '</td><td>' + val.remarks + '</td><td>' + val.user_name + '</td><td>' + val.created_at + '</td></tr>';
						});

					} else if (res.status == '2') {
						tbody = "";
					}
					console.log(tbody);
					$('#remarks-body').html(tbody);
				},
			});
		}
	});

	$("#remarks-form").validate({
		rules: {
			remarks: {
				required: true,
			},
			ward_select: {
				required: true,
			},
		},
		messages: {
			remarks: "Please provide your remarks.",
		},

		errorPlacement: function (error, element) {
			console.log(element);
			error.addClass("ui red pointing label transition");
			error.insertAfter(element.after());
		},

		invalidHandler: function (event, validator) {
			// 'this' refers to the form
			var errors = validator.numberOfInvalids();
			console.log(errors);
			if (errors) {
				var message = errors == 1 ?
					'You missed 1 field. It has been highlighted' :
					'You missed ' + errors + ' fields. They have been highlighted';
				$("div.error span").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},
		submitHandler: function (form, e) {
            $('.remark_save_btn').text('Processing...').prop('disabled', true);
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: base_url + 'pwd/addremarks',
				data: $('#remarks-form').serialize(),
				success: function (res) {
                    $('.remark_save_btn').text('save').prop('disabled', false);
					let response = JSON.parse(res);
					if (response.status == '1') {
						swal("Good Job!", response.messg, "success")
							.then((value) => {
								location.reload()
							});
					} else if (response.status == '2') {
						swal("Warning!", response.messg, "warning");
					}
				},
				error: response => {
					console.log(response);
				}
			});
		}
	});
});
