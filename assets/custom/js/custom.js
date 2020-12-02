

const ward_visibility = (role_id , dept_id) => {
    if (role_id == 8) {
      $("#word_select").prop('disabled', false);$('.word_container').show();
    } else {
      $("#word_select").prop('disabled', true);
      $('.word_container').hide();
      $('#alert_message').remove();
      return;
    }
}

$(document).on('change', '#role_id', Event => { Event.preventDefault();
    let role_id = Event.target.value ;
    let dept_id = $('#dept_id').val();

    ward_visibility(role_id,dept_id);

    if ($.isNumeric(role_id) && $.isNumeric(dept_id)) {
        $.ajax({
            type: "POST",
            url: base_url + 'userdata/get_user_ward',
            data: {role_id:role_id,dept_id:dept_id},
            success: response => {
                let html_str = '<option value="">---Select word---</option>';
                if (response.status == true) {
                    $(response.role_data).each( index  => {
                        html_str += '<option value="'+ response.role_data[index].ward_id +'">'+ response.role_data[index].ward_title +'</option>';
                    });
                } else {
                    notify_error(response.message);
                }
                $("#word_select").html(html_str).selectpicker('refresh');
            },
            error: response => {
                notify_error();console.log(response);
            }
        });
    } else {
        notify_error();
    }

});



$(document).ready(function(){


    let role_id = $('#role_id').val();
    let dept_id = $('#dept_id').val();


    ward_visibility(role_id , dept_id);

	// alert(base_url);
  // console.log(getCookie('token'));
  // validate jwt to verify access
  // var token = getCookie('token');
  // // alert(token); 
  // // return false;
  // if(token !== false) {
  //   console.log('token: ' +token);
  //   $.ajax({
  //     type: 'POST',
  //     url: base_url +'admin/validate_token',
  //     dataType: "Json",
  //     data:{'token':token},
  //     success: function(res) {
  //         console.log(res.messg);
  //         if(res.status =='2') {
  //           window.location = base_url + 'login';
  //         } else if(res.status =='1'){
  //             Toast.fire({
  //               type: 'error',
  //               title: res.messg
  //             })
  //         } 
  //       },
  //   });

  // } else {
  //   // window.location = base_url + 'login';
  // }
 
  // show login page on error will be here

  // function setCookie(cname, cvalue, exdays) {
  //     var d = new Date();
      
  //     d.setTime(d.getTime() + (1*24*60*60*1000));
  //     var expires = "expires="+ d.toUTCString();
  //     // console.log(getCookie('token'));
  //     console.log(cname + "=" + cvalue + ";" + expires + ";path=/");
  //     return false;
  //     // return false;
  //     document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  // }
  // var is_type = createUsers.getAttribute("is_type");
	$(document).find(".errorReg").hide();
	// if(is_type == 'add'){
	// 	$(document).find(".is_visitor_section").hide();
	// }else{
	// 	$(document).find(".is_visitor_section").hide();
	// }
	
	var is_type = usersPage.getAttribute("is_type");

  function setCookie(name,value,expires){
     document.cookie = name + "=" + value + ((expires==null) ? "" : ";expires=" + expires.toGMTString());
     // console.log(getCookie('token'));
     // return false;
  }

  function getCookie(name) {
     var cookieName = name + "="
     var docCookie = document.cookie
     var cookieStart
     var end
     // alert(document.cookie);
     if (docCookie.length>0) {
        cookieStart = docCookie.indexOf(cookieName)
        if (cookieStart != -1) {
           cookieStart = cookieStart + cookieName.length
           end = docCookie.indexOf(";",cookieStart)
           if (end == -1) {
              end = docCookie.length
           }
           return unescape(docCookie.substring(cookieStart,end))
        }
     }
     return false
  }

  // const Toast = Swal.mixin({
  //   toast: true,
  //   position: 'top-end',
  //   showConfirmButton: false,
  //   timer: 3000
  // });
    
  $( "#login-form" ).validate({
    rules: {
      
      email_id: {
          required: true,
          email: true,

      },

      password: "required",

    },
    messages: {
      email_id: "Please Provide email Id",
      password: "Please Provide password",
    },

    errorPlacement: function ( error, element ) {
      console.log(error);
      error.addClass( "ui red pointing label transition" );
      error.insertAfter( element.after() );
    },

    invalidHandler: function(event, validator) {
      // 'this' refers to the form
      var errors = validator.numberOfInvalids();
      console.log(errors);
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
    submitHandler: function(form,e) {
      e.preventDefault();
      console.log('Form submitted');
      $.ajax({
          type: 'POST',
          url: base_url +'login_check',
          dataType: "Json",
          data:$('#login-form').serialize(),
          success: function(res) {

            if(res.status =='1') {
            //   swal("Good Job!",res.messg,"success")
            //   .then((value) => {
                // window.location = base_url;
            //   });
                
                $(document).find(".error_msg").text(res.messg).css({'display':'block','color':'green','font-weight':'bold','margin-left':'20%'});
                setTimeout(function(){
                    $(document).find(".error_msg").css({'display':'none'});
                    window.location = base_url;
                },2000);
            } else if(res.status =='2'){
            //   swal("Warning!",res.messg,"warning");
                $(document).find(".error_msg").text(res.messg).css({'display':'block','color':'red','font-weight':'bold','margin-left':'20%'});
                setTimeout(function(){
                    $(document).find(".error_msg").css({'display':'none'});
                },2000);
            } else if(res.status =='3'){
               $(document).find(".error_msg").text(res.messg).css({'display':'block','color':'red','font-weight':'bold','margin-left':'20%'});
               setTimeout(function(){
                $(document).find(".error_msg").css({'display':'none'});
               },2000);
            } else if(res.status =='4'){
            //   swal("Warning!",res.messg,"warning");
                $(document).find(".error_msg").text(res.messg).css({'display':'block','color':'red','font-weight':'bold','margin-left':'20%'});
                setTimeout(function(){
                    $(document).find(".error_msg").css({'display':'none'});
                    location.reload();
                },2000);
            }
          }   

              // if(res.status =='1') {
              //   swal("Good Job!",res.messg,"success")
              //   .then((value) => {

              //     window.location = base_url;
              //   });
              // } else if(res.status =='2'){
              //   swal("Warning!",res.messg,"warning");
              // } else if(res.status =='3'){
              //    swal("Warning!",res.messg,"warning");
              // } else if(res.status =='4'){
              //    swal("Warning!",res.messg,"warning");

              // }
          // },

      });
      // return false;
    }
  });

  jQuery.validator.addMethod('phoneUS', function(phone_number, element) {
      phone_number = phone_number.replace(/\s+/g, ''); 
      return this.optional(element) || phone_number.length > 9 &&
          phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
  }, 'Please enter a valid phone number.');

  $('.selectpicker').on('change', function() {
      $('#role_id').selectpicker('refresh');
  });

  $( "#register-form" ).validate({
    rules: {
      user_name:{
        required: true
      },
      email_id: {
        required: true,
        email: true,
        remote: {
          url: base_url + 'user_email_validate_edit',
          type: "post",
          data:{user_id:()=>{ return $('#user_id').val(); }}
        }
      },
      user_mobile: {
        minlength:10,
        maxlength:10,
        required: true,
        remote: {
          url: base_url + 'edit_validate_contact',
          type: "post",
          data:{user_id:()=>{ return $('#user_id').val(); }}
        }
      },
      password: "required",
      terms: "required",
      word_id:{
        required:true,
        number: true,
      }
    },
    messages: {
      email_id: {
        required:"Please Provide email Id",
        remote:"This email id is already in use"
      },
      user_mobile: {
        required: "Please Provide Mobile No.",
        maxlength: "Your Mobile No. must be of 10 digits",
        remote: "This mobile number is already in use."
      },
      user_name: "Please Provide user name",
      password: "Please Provide password",
      terms: "Please Agree Terms and Conditions"
    },

    errorPlacement: function ( error, element ) {
      if (element.attr("name") === 'role_id') {
        error.insertAfter('#role_error');
      } else if(element.attr("name") === 'dept_id'){
        error.insertAfter('#dept_error');
      }
      else if(element.attr("name") === 'word_id'){
        error.insertAfter('#word_error');
      } 

      else {
        error.insertAfter(element);
      }
      error.addClass( "ui red pointing label transition" );
    },
    invalidHandler: function(event, validator) {
      var errors = validator.numberOfInvalids();
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

    submitHandler: function(form,e) {
      e.preventDefault();
    //   console.log('Form submitted');
			
      $.ajax({
          type: 'POST',
          url: base_url +'users/register_save',
          dataType: "Json",
          data:$('#register-form').serialize(),
          success: function(res) {
              // console.log(res);
              if(res.success =='1') {
								if(res.status == '1'){
									
									swal("Success!", "User Created Successfully", "success").then((willActive) => {
										window.location = base_url;
									});

								}else{
									$(document).find(".errorReg").text("User Registered Successfully").css({'font-weight':'bold','font-size':'20px','color':'green','margin-left': '12px', 'margin-right': '12px'}).show();

									setTimeout(function(){
										$(document).find(".errorReg").text("").hide();
										window.location = base_url+'login';
										
                	}, 2000);
								}

              } else if(res.status =='2'){
                $(document).find(".errorReg").text("Some Error Occured Please Try Again").css({'font-weight':'bold','font-size':'20px','color':'red','text-align': 'center'}).show();
                
                setTimeout(function(){
                    $(document).find(".errorReg").text("").hide();
                }, 2000);
              } 
          },
      });
      return false;
    }
  });

	//get role on department change
	$(document).on("change", "#dept_id", function(){
		let option = "<option value=''>---Select Role---</option>";
		var dept_id = $(this).val();

		$.ajax({
			type: 'POST',
			url: base_url +'users/getRoleByDept',
			dataType: "Json",
			data: {"dept_id" : dept_id},
			async: false,
			success: function(res){
				console.log(res);
				if(res.success == 1){
					$.each(res.roles,function(index,value){
						option += "<option value = '"+value.role_id+"'>"+value.roleTitle+"</option>";
					});
				}
			}
		});
		
		$(document).find("#role_id").children().remove();
		$(document).find("#role_id").append(option).selectpicker("refresh");
	})
	//End get role

	//is_visitor section
	$(document).on("change", "#role_id", function(){
		//check for garden suprintendent
		if($(this).val() == 15){
			$(".is_visitor_section").show();
		}else{
			$(".is_visitor_section").hide();
		}
	});
	//End is_visitor section
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