$(document).ready(function(){
    var is_user = createTrade.getAttribute("is_user");
	$('.cancel').click(function(){
		location.href = base_url+'tradefactlic/';
	});
 	
 	function CapitalizeWord(str)
	{
	  return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
	}

	$('#appName,#shootingPlace').keyup(function(){
	   var text =  $(this).val();
	   text = CapitalizeWord(text);
	   $(this).val(text);
	});

	//remarks
	$(document).on('click', '.remarks', function(){
		
		var facId = $(this).data('id');
		let trRow = "";

		$.ajax({
			url: base_url+'tradefactlic/getRemarks',
	        type:"POST",
	        dataType: "json",
	        data: {'facId': facId},
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

		$("#remarks-body").children().remove();
		$("#remarks-body").append(trRow);
		$('#modal-remarks').modal('show');
	});

	$("#licenseType").change(function(){
		var licType = $(this).val();

		if(licType == 1){
			$("#existingNo").val(Math.floor(1000 + Math.random() * 9000));
			$("#existingNo").data('id', licType);
		}else{
			$("#existingNo").val("");
			$("#existingNo").data('id', licType);
		}
	});

	//get license old data
	$(document).on("blur", "#existingNo", function(){
		var licNo = $(this).val();
		var licType = $(this).data('id');

		if(licType != '1'){
			//old lic no
			$.ajax({
				url: base_url+'tradefactlic/getLicData',
		        type:"POST",
		        dataType: "json",
		        data: {'licNo': licNo},
		        async: false,
		        success: function(res){
		        	// console.log(res[0]['shop_no']);
		        	$("#form_no").val(res[0]['form_no']);
		        	$("#appName").val(res[0]['name']);
		        	$("#shopNo").val(res[0]['shop_no']);
		        	$("#Address").val(res[0]['address']);
		        	$("#propertyNo").val(res[0]['property_no']);
		        	$("#shopName").val(res[0]['shop_name']);
		        	$("#businessType").val(res[0]['type_of_business']);
		        	$("#propertyType").val(res[0]['type_of_property']);
		        	$("#propertyDate").val(res[0]['property_date']);
		        	$("#aadharNo").val(res[0]['aadhar_no']);
		        	$("#panNo").val(res[0]['pan_no']);
		        	$("#noObjDate").val(res[0]['date_no_obj']);
		        	$("#foodLicDate").val(res[0]['date_food_lic']);
		        	$("#propTaxDate").val(res[0]['date_property_tax']);
		        	$("#establishmentDate").val(res[0]['date_establishment']);
		        	$("#assuranceDate").val(res[0]['name']);
		        }
			});
		}
	});

	$("#licenseTypeEdit").change(function(){
		var licType = $(this).val();
		
		var origLicType = $(this).parent().parent().find("#licType").val();
		var existNo = $(this).parent().parent().find("#existNo").val();
		
		if(licType == 1){
			if(origLicType == 1){
				$("#existingNo").val(existNo);	
			}else{
				$("#existingNo").val(Math.floor(1000 + Math.random() * 9000));
			}
		}else{
			if(origLicType == 2){
				$("#existingNo").val(existNo);	
			}else{
				$("#existingNo").val("");
			}
		}
	});

	//create
	//on change lic type

	jQuery.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[a-z\s]+$/i.test(value);
	}, "Letters only please");

	$("#createTFForm").validate({
		rules: {
			form_no: {
				required: true
			},
			applicationDate: {
				required: true
			},
			appName: {
				required: true,
				lettersonly: true
			},
			shop_no: {
				required: true,
				number: true
			},
			Address: {
				required: true
			},
			propertyNo: {
				required: true,
				number: true
			},
			shopName: {
				required: true,
				lettersonly: true
			},
			businessType: {
				required: true
			},
			licenseType: {
				required: true
			},
			existingNo: {
				required: true,
				number: true
			},
			propertyType: {
				required: true
			},
			propertyDate: {
				required: true
			},
			aadharNo: {
				required:true
			},
			panNo: {
				required: true
			},
			noObjDate: {
				required: true
			},
			foodLicDate: {
				required: true
			},
			propTaxDate: {
				required: true
			},
			establishmentDate: {
				required: true
			},
			assuranceDate: {
				required: true
			}
		},
		messages: {
			form_no: "Enter Form No",
			applicationDate: "Selct Application Date",
			appName: "Enter Correct Applicant Name",
			shop_no: "Enter Correct Shop No",
			Address: "Enter Correct Address",
			propertyNo: "Enter Property No",
			shopName: "Enter Correct Shop Name",
			businessType: "Enter Type Of Business",
			licenseType: "Select Type Of License",
			existingNo: "Enter Correct License No",
			propertyType: "Select Type of property",
			propertyDate: "Select Property Date",
			aadharNo: "Enter Aadhar No",
			panNo: "Enter Pan No",
			noObjDate: "Select Soc No Obj Date",
			foodLicDate: "Select Food Lic Date",
			propTaxDate: "Enter Prop Tax Date",
			establishmentDate: "Enter Establishment Date",
			assuranceDate: "Enter Assurance Date"
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

	    	var form_data = new FormData(document.getElementById("createTFForm"));

	    	var validationStatus = true;

	    	//aadhar validation 
	    	var aadharNo = $(document).find("#aadharNo").val();
	    	// console.log(aadharNo);
	    	var aadPattern = /^\d{4}-\d{4}-\d{4}-\d{4}$/;
	    	
	    	
    		if(!aadPattern.test(aadharNo)){
    			validationStatus = false;
    			$(document).find("#aadharNo").parent().append('<label id="error" class="error ui red pointing label transition">Aadhar No. Entered Is Incorrect.</label>').css({'color':'red', 'font-weight':'400 !important'});
    			setTimeout(function(){
	              $(document).find('#error').remove();
	            }, 2000);
    			return;
    		}
	    	 
	    	
	    	//pan validation
	    	var panNo = $(document).find("#panNo").val();
	    	var panPattern = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
	    	if(!panPattern.test(panNo)){
    			validationStatus = false;
    			$(document).find("#panNo").parent().append('<label id="error" class="error ui red pointing label transition">Pan No. Entered Is Incorrect.</label>').css({'color':'red', 'font-weight':'400 !important'});
    			setTimeout(function(){
	              $(document).find('#error').remove();
	            }, 2000);
    			return;
    		}

	    	if(validationStatus){

	    		swal({
		            title: 'Are You Sure Want To Submit?',
		            text: '',
		            icon: 'warning',
		            buttons: true,
		            dangerMode: true,
		        }).then((willactive) => {
		            if(willactive){
		            	$.ajax({
			             url: base_url+'tradefactlic/createFactLic',
			             type:"POST",
			             data: form_data,
			             processData:false,
			             contentType:false,
			             cache:false,
			             async:false,
			             success: function(data){
			                 // console.log(data);
			                 var data = $.parseJSON(data);
			                  // console.log(data);return;
			                if(data.success == '1'){

			                  // $(".alert-success").css({'display':'block'});
			                  // $(".alert-success").find("#alert-success").text("Complain Registered SuccessFully");
			                  sweet_alert("Good Job!",'Registered SuccessFully',"success");

			                  setTimeout(function(){
			                      if(is_user == '1'){
			                          location.reload();
			                      }else{
			                        location.href = base_url + "tradefactlic/";      
			                      }
			                    
			                  },2000);

			                }else{
			                  // $(".alert-danger").css({'display':'block'});
			                  // $(".alert-danger").find("#alert-danger").text("Some Error Occured");
			                  sweet_alert("Warning!",'Error Occured',"warning");
			                  // setTimeout(function(){
			                  //   $(".alert-danger").css({'display':'none'});
			                  // },1000);
			                } 
			             }
			         	});//Ajax
		            }
		        });    	
	    	}
	    }
	    	
	});

	//END create


	//edit
	$("#editApplicationForm").validate({
		rules: {
			form_no: {
				required: true
			},
			applicationDate: {
				required: true
			},
			appName: {
				required: true,
				lettersonly: true
			},
			shop_no: {
				required: true,
				number: true
			},
			Address: {
				required: true
			},
			propertyNo: {
				required: true,
				number: true
			},
			shopName: {
				required: true,
				lettersonly: true
			},
			businessType: {
				required: true
			},
			licenseType: {
				required: true
			},
			existingNo: {
				required: true,
				number: true
			},
			propertyType: {
				required: true
			},
			propertyDate: {
				required: true
			},
			aadharNo: {
				required:true
			},
			panNo: {
				required: true
			},
			noObjDate: {
				required: true
			},
			foodLicDate: {
				required: true
			},
			propTaxDate: {
				required: true
			},
			establishmentDate: {
				required: true
			},
			assuranceDate: {
				required: true
			}
		},
		messages: {
			form_no: "Enter Form No",
			applicationDate: "Selct Application Date",
			appName: "Enter Correct Applicant Name",
			shop_no: "Enter Correct Shop No",
			Address: "Enter Correct Address",
			propertyNo: "Enter Correct Property No",
			shopName: "Enter Correct Shop Name",
			businessType: "Enter Type Of Business",
			licenseType: "Select Type Of License",
			existingNo: "Enter Correct License No",
			propertyType: "Select Type of property",
			propertyDate: "Select Property Date",
			aadharNo: "Enter Aadhar No",
			panNo: "Enter Pan No",
			noObjDate: "Select Soc No Obj Date",
			foodLicDate: "Select Food Lic Date",
			propTaxDate: "Enter Prop Tax Date",
			establishmentDate: "Enter Establishment Date",
			assuranceDate: "Enter Assurance Date"
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

	    	var form_data = new FormData(document.getElementById("editApplicationForm"));
	    	// console.log(form_data);return;

	    	swal({
        	  title: 'Are You Sure Want To Edit?',
	          text: '',
	          icon: 'warning',
	          buttons: true,
	          dangerMode: true,
		    }).then((willactive) => {
		       if(willactive){
		       	$.ajax({
         		 url: base_url+'tradefactlic/editFactLic',
	             type:"POST",
	             data: form_data,
	             processData:false,
	             contentType:false,
	             cache:false,
	             async:false,
	             success: function(data){
	                 // console.log(data);
	                 var data = $.parseJSON(data);
	                  // console.log(data);return;
	                if(data.success == '1'){

	                  sweet_alert("Good Job!",data.messg,"success");

	                  setTimeout(function(){
	                    location.href = base_url+'tradefactlic/';
	                  },2000);

	                }else{
	                  sweet_alert("Warning!",data.messg,"warning");

	                  // setTimeout(function(){
	                  //   $(".alert-danger").css({'display':'none'});
	                  // },1000);
	                } 
	             }
		        });//Ajax
		       }
		    });   	
	    }
	    	
	});
	//end edit

	//approval
	$(document).on('click', '.approvalStatus', function(){
		
      let selectRows = "";
      let select = "";
      let appId = $(this).data('id');
      $.ajax({
        url: base_url+"tradefactlic/getAppStatus",
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
             url: base_url+'tradefactlic/approveComplain',
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
					url: base_url+'tradefactlic/delete',
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
	
})