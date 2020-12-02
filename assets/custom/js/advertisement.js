$(document).ready(function(){
    
    var is_user = createAdvertisement.getAttribute("is_user");

	function CapitalizeWord(str)
	{
	  return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	}

	$('#illName,#illNameEdit, #advNameEdit, #advName').keyup(function(){
	   var text =  $(this).val();
	   text = CapitalizeWord(text);
	   $(this).val(text);
	});

	if($(document).find('#req_type').val() == '2'){
		$(document).find(".companyDetails").css({'display':'block'});
	}else{
		$(document).find(".companyDetails").css({'display':'none'});
	}

	$(document).on("change", "#req_type", function(){
		if($(this).val() == '2'){
			$(document).find(".companyDetails").css({'display':'block'});
		}else{
			$(document).find(".companyDetails").css({'display':'none'});
		}
	});

	$(document).on("change", "#society_notice", function(){
		if($(this).val() == '2'){
			$(document).find(".uploadNotice").css({'display':'block'});
			$(document).find("#soc_not").val('');
		}else{
			$(document).find(".uploadNotice").css({'display':'none'});
		}
	});

	$(document).on("change", "#noc", function(){
		if($(this).val() == '2'){
			$(document).find(".uploadNoc").css({'display':'block'});
			$(document).find("#upload_noc").val('');
		}else{
			$(document).find(".uploadNoc").css({'display':'none'});
		}
	});

	//calculate end date
	$(document).on("change", "#start_date", function(){
		var dateSelected = new Date($(this).val());
		if($("#no_of_days").val() != ''){
			var noOfDays = parseInt($("#no_of_days").val(),10);	
			if(!isNaN(dateSelected.getTime())){
				dateSelected.setDate(dateSelected.getDate() + noOfDays);
				var expiryDate = (dateSelected.getMonth() + 1)+'/'+dateSelected.getDate()+'/'+dateSelected.getFullYear();
				$(document).find("#end_date").val(expiryDate);
			}else{
				$(document).find("#start_date").parent().append('<label id="error" class="error ui red pointing label transition">Please Enter No. of Days.</label>').css({'color':'red', 'font-weight':'400 !important'});
				setTimeout(function(){
	              $(document).find('#error').remove();
	            }, 2000);
	            return;
			}
		}else{
			$(document).find("#no_of_days").parent().append('<label id="error" class="error ui red pointing label transition">Please Enter No. of Days.</label>').css({'color':'red', 'font-weight':'400 !important'});
			setTimeout(function(){
              $(document).find('#error').remove();
            }, 2000);
            return;
		}
	});

	//on no of days change
	$(document).on("blur", "#no_of_days", function(){
		var noOfDays = parseInt($(this).val(),10);	
		
		if($("#no_of_days").val() != '' && $("#start_date").val() != ''){
			var dateSelected = new Date($("#start_date").val());	
			if(!isNaN(dateSelected.getTime())){
				dateSelected.setDate(dateSelected.getDate() + noOfDays);
				var expiryDate = (dateSelected.getMonth() + 1)+'/'+dateSelected.getDate()+'/'+dateSelected.getFullYear();
				$(document).find("#end_date").val(expiryDate);
			}else{
				$(document).find("#start_date").parent().append('<label id="error" class="error ui red pointing label transition">Please Enter No. of Days.</label>').css({'color':'red', 'font-weight':'400 !important'});
				setTimeout(function(){
	              $(document).find('#error').remove();
	            }, 2000);
	            return;
			}
		}else{
			$(document).find("#no_of_days").parent().append('<label id="error" class="error ui red pointing label transition">Please Enter No. of Days.</label>').css({'color':'red', 'font-weight':'400 !important'});
			setTimeout(function(){
              $(document).find('#error').remove();
            }, 2000);
            return;
		}
	});

	//submit
	jQuery.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[a-z\s]+$/i.test(value);
	}, "Letters only please");

	$("#advertisementForm").validate({
		rules: {
			form_no: {
				required: true,
			}, 
			name: {
				required:true,
				lettersonly: true
			},
			address: {
				required: true,
			},
			hoarding_address: {
				required: true,
			},
			adv_type: {
				required: true,
			},
			illuminate: {
				required: true,
			},
			hoarding_length: {
				required: true,
				number: true
			},
			hoarding_breadth: {
				required: true,
				number: true
			},
			road_height: {
				required: true,
				number: true
			},
			serchana: {
				required: true,
			},
			hoarding_loc: {
				required: true,
			},
			start_date: {
				required: true,
			},
			no_of_days: {
				required: true,
			},
			end_date: {
				required: true,
			},
			rate: {
				required: true,
				number: true
			},
			amount: {
				required: true,
				number:true
			},
			req_type: {
				required: true,
			},
			pancard: {
				required: true,
			},
			aadhar: {
				required: true,
			},
			society_notice:{
				required: true,
			},
			owner_hoarding_name: {
				required: true,
			},
			owner_hoarding_add: {
				required: true,
			},
			noc: {
				required: true,
			}
		},
		messages: {
			form_no: "Please Enter Form No.", 
			name: "Please Enter Name",
			address: "Please Enter Address",
			hoarding_address: "Please Enter Hoarding Address",
			adv_type: "Please Select Type Of Advertisement",
			illuminate: "Please Select illuminate",
			hoarding_length: "Please Enter Hoarding Length",
			hoarding_breadth: "Please Enter Hoarding Breadth",
			road_height: "Please Enter Road Height",
			serchana: "Please Enter Serchana",
			hoarding_loc: "Please Enter Hoarding Location",
			start_date: "Please Select Start Date",
			no_of_days: "Please Enter No Of Days",
			end_date: "Please Select End Date",
			rate: "Please Enter Rate",
			amount: "Please Enter Amount",
			req_type: "Please Select Type Of Request",
			pancard: "Please Select Pancard",
			aadhar: "Please Select Aadhar Card",
			society_notice:"Please Select Society Notice",
			owner_hoarding_name: "Please Enter Hoarding Name",
			owner_hoarding_add: "Please Enter Hoarding Address",
			noc: "Please Select Noc"
		},

		errorPlacement: function ( error, element ) {
	       console.log(error);
	       error.addClass( "ui red pointing label transition" );
	       error.insertAfter( element.after() );
	    },

	    invalidHandler: function(event, validator) {
      
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
	    	var statusDefined = true;
	    	//request type
	    	var reqType = $(document).find("#req_type").val();
	    	if(reqType == '2'){
	    		if($("#comp_add1").val() == ''){
	    			statusDefined = false;
	    			$(document).find("#comp_add1").parent().append('<label id="error" class="error ui red pointing label transition">Please Enter Company Address.</label>').css({'color':'red', 'font-weight':'400 !important'});
					setTimeout(function(){
		              $(document).find('#error').remove();
		            }, 2000);
		            return;
	    		}
	    	}
	    	//society notice
	    	var societyNotice = $(document).find("#society_notice").val();
	    	if(societyNotice == '2'){
	    		if(document.getElementById("soc_not").files.Length == 0){
	    			statusDefined = false;
	    			$(document).find("#soc_not").parent().append('<label id="error" class="error ui red pointing label transition">Please Select Society Notice.</label>').css({'color':'red', 'font-weight':'400 !important'});
					setTimeout(function(){
		              $(document).find('#error').remove();
		            }, 2000);
		            return;
	    		}
	    	}

	    	//noc
	    	var noc = $(document).find("#noc").val();
	    	if(noc == '2'){
	    		if(document.getElementById("upload_noc").files.Length == 0){
	    			statusDefined = false;
	    			$(document).find("#upload_noc").parent().append('<label id="error" class="error ui red pointing label transition">Please Select Noc.</label>').css({'color':'red', 'font-weight':'400 !important'});
					setTimeout(function(){
		              $(document).find('#error').remove();
		            }, 2000);
		            return;
	    		}
	    	}

	    	var formData = new FormData(document.getElementById("advertisementForm"));

	    	$.ajax({
             url: base_url+'advertisement/createApplications',
             type:"POST",
             data: formData,
             processData:false,
             contentType:false,
             cache:false,
             async:false,
             success: function(data){
                 console.log(data);
                 var data = $.parseJSON(data);
                  // console.log(data);return;
                if(data.success == '1'){

                  // $(".alert-success").css({'display':'block'});
                  // $(".alert-success").find("#alert-success").text("Complain Registered SuccessFully");

                  swal("Success!", data.msg, "success");

                  setTimeout(function(){
                    // $(".alert-success").css({'display':'none'});
                    if(is_user == '1'){
                        location.reload();
                    }else{
                        location.href = base_url+"advertisement/";    
                    }
                    
                  },2000);

                }else{
                  // $(".alert-danger").css({'display':'block'});
                  // $(".alert-danger").find("#alert-danger").text("Some Error Occured");
                  swal("Warning!", "Some Error Occured", "warning");
                  // setTimeout(function(){
                  //   $(".alert-danger").css({'display':'none'});
                  // },1000);
                } 
             }
         	});//Ajax
	    }
	});
	
	//approval
  $(document).on('click', '.approvalStatus', function(){
    
      let selectRows = "";
      let select = "";
      let appId = $(this).data('id');
      $.ajax({
        url: base_url+"advertisement/getAppStatus",
        type: "POST",
        dataType: "json",
        async: false,
        success: function(res){
          // console.log(res.gardenData);

          $.each(res.status, function(ind, val){
            selectRows += "<option value = '"+val.status_id+"'>"+val.status_title+"</option>";
          });

        }
      });

      select = "<select class='selectpicker form-control' name='app_status' data-live-search='true'>"+selectRows+"</select>"
      $(document).find(".app_status").children().remove();
      $(".app_status").append(select);
      $('.selectpicker').selectpicker('refresh');
      $(document).find("#complain_id_app").val(appId);

      $('#modal-approval').modal('show');
    });

  $("#approvalForm").validate({
      rules: {
        remarks:{
          required: true,
        }
      },
      messages: {
        remarks: "Please Enter Remarks",
      },
      errorPlacement: function ( error, element ) {
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
        var form_data = new FormData(document.getElementById("approvalForm"));

        $.ajax({
             url: base_url+'advertisement/approveComplain',
             type:"post",
             data: form_data,
             processData:false,
             contentType:false,
             cache:false,
             async:false,
             success: function(data){
                 // console.log(data);
                 var data = $.parseJSON(data);
                  // console.log(data.success);
                if(data.success == '1'){

                  $(document).find(".error").text(data.messg).css({'display': 'block', 'color': 'green'});

                  setTimeout(function(){
                    $(document).find(".error").text(data.messg).css({'display': 'none', 'color': 'green'});
                    location.reload();
                  },2000);  

                }else{
                  $(document).find(".error").text(data.messg).css({'display': 'block', 'color': 'green'});

                  setTimeout(function(){
                    $(document).find(".error").text(data.messg).css({'display': 'none', 'color': 'green'});
                    location.reload();
                  },2000);
                } 
             }
         });
      }
    });
  //End Approval

  //delete
  $(document).on("click", ".delete", function(){
  	var adv_id = $(this).data('id');
    var actk = $(this).data('act');

    $.ajax({
         url: base_url+'advertisement/deleteApplication',
         type:"POST",
         data: {'adv_id': adv_id, 'actk': actk},
         cache:false,
         async:false,
         success: function(data){
             // console.log(data);
             var data = $.parseJSON(data);
              // console.log(data);return;
            if(data.success == '1'){

              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("Deleted SuccessFully");

              setTimeout(function(){
                $(".alert-success").css({'display':'none'});
                location.reload(true);
              },2000);

            }else{
              $(".alert-danger").css({'display':'block'});
              $(".alert-danger").find("#alert-danger").text("Some Error Occured");

              setTimeout(function(){
                $(".alert-danger").css({'display':'none'});
              },1000);
            } 
         }
    });//Ajax
  });

	//edit
	$("#advertisementFormEdit").validate({
		rules: {
			form_no: {
				required: true,
			}, 
			name: {
				required:true,
			},
			address: {
				required: true,
			},
			hoarding_address: {
				required: true,
			},
			adv_type: {
				required: true,
			},
			illuminate: {
				required: true,
			},
			hoarding_length: {
				required: true,
			},
			hoarding_breadth: {
				required: true,
			},
			road_height: {
				required: true,
			},
			serchana: {
				required: true,
			},
			hoarding_loc: {
				required: true,
			},
			start_date: {
				required: true,
			},
			no_of_days: {
				required: true,
			},
			end_date: {
				required: true,
			},
			rate: {
				required: true,
			},
			amount: {
				required: true,
			},
			req_type: {
				required: true,
			},
			society_notice:{
				required: true,
			},
			owner_hoarding_name: {
				required: true,
			},
			owner_hoarding_add: {
				required: true,
			},
			noc: {
				required: true,
			}
		},
		messages: {
			form_no: "Please Enter Form No.", 
			name: "Please Enter Name",
			address: "Please Enter Address",
			hoarding_address: "Please Enter Hoarding Address",
			adv_type: "Please Select Type Of Advertisement",
			illuminate: "Please Select illuminate",
			hoarding_length: "Please Enter Hoarding Length",
			hoarding_breadth: "Please Enter Hoarding Breadth",
			road_height: "Please Enter Road Height",
			serchana: "Please Enter Serchana",
			hoarding_loc: "Please Enter Hoarding Location",
			start_date: "Please Select Start Date",
			no_of_days: "Please Enter No Of Days",
			end_date: "Please Select End Date",
			rate: "Please Enter Rate",
			amount: "Please Enter Amount",
			req_type: "Please Select Type Of Request",
			society_notice:"Please Select Society Notice",
			owner_hoarding_name: "Please Enter Hoarding Name",
			owner_hoarding_add: "Please Enter Hoarding Address",
			noc: "Please Select Noc"
		},

		errorPlacement: function ( error, element ) {
	       console.log(error);
	       error.addClass( "ui red pointing label transition" );
	       error.insertAfter( element.after() );
	    },

	    invalidHandler: function(event, validator) {
      
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
	    	var formData = new FormData(document.getElementById("advertisementFormEdit"));

	    	$.ajax({
             url: base_url+'advertisement/editApplications',
             type:"POST",
             data: formData,
             processData:false,
             contentType:false,
             cache:false,
             async:false,
             success: function(data){
                 console.log(data);
                 var data = $.parseJSON(data);
                  // console.log(data);return;
                if(data.success == '1'){

                  // $(".alert-success").css({'display':'block'});
                  // $(".alert-success").find("#alert-success").text("Complain Registered SuccessFully");

                  swal("Success!", data.msg, "success");

                  setTimeout(function(){
                    // $(".alert-success").css({'display':'none'});
                    location.href = base_url+"advertisement/"
                  },2000);

                }else{
                  // $(".alert-danger").css({'display':'block'});
                  // $(".alert-danger").find("#alert-danger").text("Some Error Occured");
                  swal("Warning!", "Some Error Occured", "warning");
                  // setTimeout(function(){
                  //   $(".alert-danger").css({'display':'none'});
                  // },1000);
                } 
             }
         	});//Ajax
	    }
	});

	//adv type
	$("#addAdvType").validate({
      rules: {
        'advName' : {
          required: true,
        }
      },
      messages: {
        advName: "Enter Adv Name",
      },
      errorPlacement: function ( error, element ) {
         console.log(error);
         error.addClass( "ui red pointing label transition" );
         error.insertAfter( element.after() );
      },

      invalidHandler: function(event, validator) {
      
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
        var formdata = new FormData(document.getElementById("addAdvType"));

        $.ajax({
             url: base_url+'advertisement/advSubmit',
             type:"POST",
             data: formdata,
             processData:false,
             contentType:false,
             cache:false,
             async:false,
             success: function(data){
                 // console.log(data);
                 var data = $.parseJSON(data);
                  // console.log(data);return;
                if(data.success == '1'){

                  swal("Success!", "Registered Successfully", "success").then((willactive) => {
                  	location.href = base_url+"advertisement/adv_index";
                  });

                  // setTimeout(function(){
                  //   location.href = base_url+"advertisement/adv_index";
                  // },2000);

                }else{
                  swal("Warning!", "Some Error Occured", "warning");
                } 
             }
          });//Ajax
      }
    });

    //edit adv type
    $("#editAdvType").validate({
      rules: {
        'advNameEdit' : {
          required: true,
          lettersonly: true
        }
      },
      messages: {
        advName: "Enter Correct Adv Name",
      },
      errorPlacement: function ( error, element ) {
         console.log(error);
         error.addClass( "ui red pointing label transition" );
         error.insertAfter( element.after() );
      },

      invalidHandler: function(event, validator) {
      
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
        var formdata = new FormData(document.getElementById("editAdvType"));

        $.ajax({
             url: base_url+'advertisement/advEditSubmit',
             type:"POST",
             data: formdata,
             processData:false,
             contentType:false,
             cache:false,
             async:false,
             success: function(data){
                 // console.log(data);
                 var data = $.parseJSON(data);
                  // console.log(data);return;
                if(data.success == '1'){

                  swal("Success!", "Edited Successfully", "success").then((willactive) => {
                  	location.href = base_url+"advertisement/adv_index";
                  });

                  // setTimeout(function(){
                  //   location.href = base_url+"advertisement/adv_index";
                  // },2000);

                }else{
                  swal("Warning!", "Some Error Occured", "warning");
                } 
             }
          });//Ajax
      }
    });
	//end adv type

	//illuminate
	$("#illuminate").validate({
      rules: {
        'illName' : {
          required: true,
          lettersonly: true
        }
      },
      messages: {
        advName: "Enter Correct illName Name",
      },
      errorPlacement: function ( error, element ) {
         console.log(error);
         error.addClass( "ui red pointing label transition" );
         error.insertAfter( element.after() );
      },

      invalidHandler: function(event, validator) {
      
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
        var formdata = new FormData(document.getElementById("illuminate"));

        $.ajax({
             url: base_url+'advertisement/illSubmit',
             type:"POST",
             data: formdata,
             processData:false,
             contentType:false,
             cache:false,
             async:false,
             success: function(data){
                 // console.log(data);
                 var data = $.parseJSON(data);
                  // console.log(data);return;
                if(data.success == '1'){

                  swal("Success!", "Registered Successfully", "success").then((willactive) =>{
                  	location.href = base_url+"advertisement/adv_index";
                  });

                  // setTimeout(function(){
                  //   location.href = base_url+"advertisement/adv_index";
                  // },2000);

                }else{
                  swal("Warning!", "Some Error Occured", "warning");
                } 
             }
          });//Ajax
      }
    });

    $("#editIlluminate").validate({
      rules: {
        'illNameEdit' : {
          required: true,
          lettersonly: true
        }
      },
      messages: {
        illNameEdit: "Enter Correct Illuminate Name",
      },
      errorPlacement: function ( error, element ) {
         console.log(error);
         error.addClass( "ui red pointing label transition" );
         error.insertAfter( element.after() );
      },

      invalidHandler: function(event, validator) {
      
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
        var formdata = new FormData(document.getElementById("editIlluminate"));

        $.ajax({
             url: base_url+'advertisement/illEditSubmit',
             type:"POST",
             data: formdata,
             processData:false,
             contentType:false,
             cache:false,
             async:false,
             success: function(data){
                 // console.log(data);
                 var data = $.parseJSON(data);
                  // console.log(data);return;
                if(data.success == '1'){

                  swal("Success!", "Edited Successfully", "success").then((willactive) => {
                  	location.href = base_url+"advertisement/illuminate_index";
                  });

                  // setTimeout(function(){
                  //   location.href = base_url+"advertisement/illuminate_index";
                  // },2000);

                }else{
                  swal("Warning!", "Some Error Occured", "warning");
                } 
             }
          });//Ajax
      }
    });

	//END illuminate
});