$(document).ready(function(){
    
    var is_user = createFilm.getAttribute("is_user");

	//name capitaliztion
	function CapitalizeWord(str)
	{
	  return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	}

	$('#cntctPersonName,#shootingPlace').keyup(function(){
	   var text =  $(this).val();
	   text = CapitalizeWord(text);
	   $(this).val(text);
	});

	//Submit create

	jQuery.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[a-z\s]+$/i.test(value);
	}, "Letters only please");

	$("#createApplicationForm").validate({
		rules: {
			form_no: {
				required: true,
			},
			cntctPersonName: {
				required: true,
				lettersonly: true
			}, 
			reasonForLicense: {
				required: true,
			},
			shootingPlace: {
				required: true,
				lettersonly: true
			},
			policeNoc: {
				required: true,
			},
			aadhar: {
				required: true,
			},
			pan: {
				required: true,
			},
			periodFrom: {
				required: true,
			},
			periodTo: {
				required: true,
			}
		},

		messages: {
			form_no: "Enter Form No.",
			cntctPersonName: "Contact Person Name Required",
			reasonForLicense: "Please Enter Reason For License",
			shootingPlace: "Enter Place Of Shooting",
			policeNoc: "Upload Police Noc",
			aadhar: "Upload Aadhar Copy",
			pan: "Upload Pan Copy",
			periodFrom: "Please Enter Date",
			periodTo: "Please Enter Date"
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

	    submitHandler: function(form, e){
	    	e.preventDefault();

	    	var subStatus = true;

	    	var fromDate = $(document).find("#periodFrom").val();

	    	var toDate = $(document).find("#periodTo").val();;

	    	if(fromDate > toDate){
	    		subStatus = false;
	    		$(document).find("#periodFrom").parent().append('<label id="error" class="error ui red pointing label transition">From Cannot Be Greater Than To.</label>').css({'color':'red', 'font-weight':'400 !important'});
    			setTimeout(function(){
	              $(document).find('#error').remove();
	            }, 2000);
	    		return;
	    	}
	    	
	    	var formData = new FormData(document.getElementById('createApplicationForm'));

	    	
	    	if(subStatus){

	    		swal({
	    			title: 'Are You Sure Want To Submit Application?',
			        text: '',
			        icon: 'warning',
			        buttons: true,
			        dangerMode: true,
	    		}).then((willactive) => {
	    			if(willactive){
	    				$.ajax({
			             url: base_url+'film/createFilmLic',
			             type:"POST",
			             data: formData,
			             processData:false,
			             contentType:false,
			             cache:false,
			             async:false,
			             success: function(data){
			                 // console.log(data);
			                 var data = $.parseJSON(data);
			                  // console.log(data);return;
			                if(data.success == '1'){

			                  sweet_alert("Good Job!",'Registered SuccessFully',"success");

			                  setTimeout(function(){
			                      if(is_user == '1'){
			                          location.reload();
			                      }else{
			                        location.href = base_url + "film/";  
			                      }
			                    
			                  },2000);

			                }else{
			                  
			                  sweet_alert("Warning!",'Error Occured',"warning");
			                  
			                } 
			             }
			         	});//Ajax
	    			}else {
			          sweet_alert("Warning!",'Oops! something went wrong.',"warning");
			        }
	    		});
	    	}
	    }
	});

	//remarks modal
	$(document).on("click", ".remarks", function(){
		var id = $(this).data('id');
		
		let trRow = "";

		$.ajax({
			url: base_url+'film/getRemarks',
	        type:"POST",
	        dataType: "json",
	        data: {'filmId': id},
	        async: false,
	        success: function(res){
	        	// console.log(res);
        	  var srNo = 1;
	          $.each(res.result, function(ind,val){
	            var username = val.user_name;
	            var str = username.toLowerCase().replace(/\b[a-z]/g, function(letter) {
	                return letter.toUpperCase();
	            });
	            trRow += "<tr class = 'text-center'><td>"+srNo+"</td><td>"+val.remarks+"</td><td>"+str+"</td><td>"+val.created_at+"</td></tr>";
	            
	            srNo++;
	          });
	        }
		});
		// console.log(trRow);

		$("#remarks-body").children().remove();
		$("#remarks-body").append(trRow);
		$('#modal-remarks').modal('show');
	});

	//Approval Modal
	//approval  
	$(document).on('click', '.approvalStatus', function(){
		
      let selectRows = "";
      let select = "";
      let appId = $(this).data('id');
      $.ajax({
        url: base_url+"film/getAppStatus",
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

    $(document).on('submit', '#approvalForm', function(e){
    	e.preventDefault();
    	var appStatus = $("select[name=app_status]").val();
    	// alert(appStatus);
    	var remarks = $("#remarks").val();
    	if(remarks != '' && (appStatus != '' || appStatus != null)){
    		var form_data = new FormData(document.getElementById("approvalForm"));
    		$.ajax({
             url: base_url+'film/approveComplain',
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
    	}else{
	      $(document).find(".error").text(data.messg).css({'display': 'block', 'color': 'green'});

          setTimeout(function(){
            $(document).find(".error").text(data.messg).css({'display': 'none', 'color': 'green'});
            location.reload();
          },2000);
    	}
    });

    // $("#approvalForm").validate({
    //   rules: {
    //     remarks:{
    //       required: true,
    //     }
    //   },
    //   messages: {
    //     remarks: "Please Enter Remarks",
    //   },
    //   errorPlacement: function ( error, element ) {
    //     var errors = validator.numberOfInvalids();
    //     console.log(errors);
    //     if(errors) {
    //         var message = errors == 1
    //         ? 'You missed 1 field. It has been highlighted'
    //         : 'You missed ' + errors + ' fields. They have been highlighted';
    //         $("div.error span").html(message);
    //         $("div.error").show();
    //     } else {
    //         $("div.error").hide();
    //     }
    //   },
    //   submitHandler: function(form,e) {
    //     e.preventDefault();
    //     var form_data = new FormData(document.getElementById("approvalForm"));

        // $.ajax({
        //      url: base_url+'film/approveComplain',
        //      type:"post",
        //      data: form_data,
        //      processData:false,
        //      contentType:false,
        //      cache:false,
        //      async:false,
        //      success: function(data){
        //          // console.log(data);
        //          var data = $.parseJSON(data);
        //           // console.log(data.success);
        //         if(data.success == '1'){

        //           $(document).find(".error").text(data.messg).css({'display': 'block', 'color': 'green'});

        //           setTimeout(function(){
        //             $(document).find(".error").text(data.messg).css({'display': 'none', 'color': 'green'});
        //             location.reload();
        //           },2000);  

        //         }else{
        //           $(document).find(".error").text(data.messg).css({'display': 'block', 'color': 'green'});

        //           setTimeout(function(){
        //             $(document).find(".error").text(data.messg).css({'display': 'none', 'color': 'green'});
        //             location.reload();
        //           },2000);
        //         } 
        //      }
        //  });
    //   }
    // });
	//End Approval

	//edit
	//Submit create
	$("#createApplicationFormEdit").validate({
		rules: {
			form_no: {
				required: true,
			},
			cntctPersonName: {
				required: true,
				lettersonly: true
			}, 
			reasonForLicense: {
				required: true,
			},
			shootingPlace: {
				required: true,
				lettersonly: true
			},
			periodFrom: {
				required: true,
			},
			periodTo: {
				required: true,
			}
		},

		messages: {
			form_no: "Enter Form No.",
			cntctPersonName: "Contact Person Name Required",
			reasonForLicense: "Please Enter Reason For License",
			shootingPlace: "Enter Place Of Shooting",
			periodFrom: "Please Select Date",
			periodTo: "Please Select Date"
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

	    submitHandler: function(form, e){
	    	e.preventDefault();

	    	var subStatusEdit = true;

	    	var fromDate = $(document).find("#periodFrom").val();

	    	var toDate = $(document).find("#periodTo").val();;

	    	if(fromDate > toDate){
	    		subStatus = false;
	    		$(document).find("#periodFrom").parent().append('<label id="error" class="error ui red pointing label transition">From Cannot Be Greater Than To.</label>').css({'color':'red', 'font-weight':'400 !important'});
    			setTimeout(function(){
	              $(document).find('#error').remove();
	            }, 2000);
	    		return;
	    	}

	    	var formData = new FormData(document.getElementById('createApplicationFormEdit'));

	    	if(subStatusEdit){

	    		swal({
			        title: 'Do You Want To Submit',
			        text: '',
			        icon: 'warning',
			        buttons: true,
			        dangerMode: true,
			      })
			      .then((willactive) => {
			        if (willactive) {
			          $.ajax({
			             url: base_url+'film/editFilmLic',
			             type:"POST",
			             data: formData,
			             processData:false,
			             contentType:false,
			             cache:false,
			             async:false,
			             success: function(data){
			                 // console.log(data);
			                 var data = $.parseJSON(data);
			                  // console.log(data);return;
			                if(data.success == '1'){
			                	
			                  sweet_alert("Good Job!",'Edited SuccessFully',"success");

			                  setTimeout(function(){
			                    location.href = base_url + "film/";
			                  },2000);

			                }else{
			                  sweet_alert("Warning!",'Error Occured',"warning");
			                } 
			             }
			         	});//Ajax
			        } else {
			          sweet_alert("Warning!",'Oops! something went wrong.',"warning");
			        }
			      });
	    	}
	    	
	    }
	});

	//cancel
	$(document).on("click", ".cancel", function(){
		location.href = base_url+'film';
	});

	//delete
	$(document).on("click", ".delete", function(){
		let act = $(this).data('act');
		var id = $(this).data("id");

		if(act == '1'){
			title = "Do You Want To Inactive?";
		}else{
			title = "Do You Want To Activate?";
		}

		swal({
	        title: title,
	        text: '',
	        icon: 'warning',
	        buttons: true,
	        dangerMode: true,
	    }).then((willactive) => {
	    	if(willactive){
	    		$.ajax({
					url: base_url+'film/delete',
		            type:"POST",
		            data: {'act': act, 'id': id},
		            async: true,
		            success: function(res){
		            	// console.log(res);

		        		var res = JSON.parse(res);

		            	if(res.success == '1'){
		            		if(act == '1'){
		            			sweet_alert("Success!", "Deactivated Successfully", "success");	
		            		}else{
		            			sweet_alert("Success!", "Activated Successfully", "success");
		            		}
		            		
		            		setTimeout(function(){
		            			location.href = base_url+'film';
		            		}, 2000);
		            	}else{
		            		sweet_alert("Warning!", "Deactivated Failed Please Try Again", "warning");
		            	}
		            }
				})
	    	}else {
	          sweet_alert("Warning!",'Oops! something went wrong.',"warning");
	        }
	    });
	});
});