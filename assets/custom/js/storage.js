$(document).ready(function(){

	$(document).on("change", "#renewalLic", function(){
		var id = $(this).val();

		if(id == '1'){
			$(document).find(".oldLicSec").css({'display':'block'});
			$(document).find(".LicSec").css({'display':'none'});
		}else{
			$(document).find(".oldLicSec").css({'display':'none'});
			$(document).find(".LicSec").css({'display':'block'});
		}
	});

	$(document).on('click', '.cancel', function(){
		location.href = base_url + "godown";
	});

	//on enter old lic no 
	$(document).on("blur", "#old_lic_no", function(){
		var oldLicNo = $(this).val();

		$.ajax({
			url: base_url+'godown/getDataByLic',
             type:"post",
             data: {'licNo': oldLicNo},
             async:false,
             success: function(data){
             	var data = $.parseJSON(data);
             	$("#appid").val(data[0]['godown_id']);
             	$("#form_no").val(data[0]['form_no']);
             	$("#name").val(data[0]['name']);
             	$("#address_1").val(data[0]['address_1']);
             	$('#address_2').val(data[0]['address_2']);
             	$('#telephone').val(data[0]['telephone']);
             	$('#mobile').val(data[0]['mobileNo']);
             	$('#godaddress_1').val(data[0]['god_address1']);
             	$('#godaddress_2').val(data[0]['god_address2']);
             	$('#productName').val(data[0]['product_name']);
             	$('#godownArea').val(data[0]['godown_area']);
             	$('#godownType').val(data[0]['type_of_godown']);
             	$('#startDate').val(data[0]['start_date']);
             	$('#otherMulLic').val(data[0]['other_muncipal_lic']);
             	$('#explosive').val(data[0]['explosive']);
             	$('#pendingDues').val(data[0]['pending_dues']);
             	$('#disapproveEarlier').val(data[0]['disapproveEarlier']);
             }
		})
	});

	//on submit
	$("#createStorage").validate({
		rules: {
			form_no: {
				required: true,
			},
			renewalLic: {
				required: true,
			},
			name: {
				required: true,
			},
			address_1: {
				required: true,
			},
			mobile: {
				required: true,
			},
			godaddress_1: {
				required: true,
			},
			productName: {
				required:true,
			},
			godownArea: {
				required: true,
			},
			godownType: {
				required: true,
			},
			startDate: {
				required: true
			},
			otherMunLic: {
				required: true,
			},
			explosive: {
				required: true,
			},
			pendingDues: {
				required: true,
			},
			disapproveEarlier: {
				required: true,
			}
		},
		messages: {
			form_no: "Enter Form No",
			renewalLic: "Select License Type",
			name: "Enter Name",
			address_1: "Enter Address",
			mobile: "Enter Mobile No",
			godaddress_1: "Enter Godown Address",
			productName: "Enter Product Name",
			godownArea: "Enter Godown Area",
			godownType: "Enter Type Of Godown",
			startDate: "Select Start Date",
			otherMunLic: "Enter Other License",
			explosive: "Select Explosive",
			pendingDues: "Enter PendingDues",
			disapproveEarlier: "Select Approval"
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

	    	var form_data = new FormData(document.getElementById("createStorage"));

	    	var dis = $("#disapproveEarlier").val();
	    	var explosive = $("#explosive").val();
	    	// alert(dis+explosive);
	    	if(dis == '-1'){
	    		$(document).find("#disapproveEarlier").parent().append('<label id="error" class="error ui red pointing label transition">Please Select Disapprove status.</label>').css({'color':'red', 'font-weight':'400 !important'});
	    		setTimeout(function(){
	              $(document).find('#error').remove();
	            }, 2000);
	    		return;
	    	}else if(explosive == '0'){
	    		$(document).find("#explosive").parent().append('<label id="error" class="error ui red pointing label transition">Please Select Explosive.</label>').css({'color':'red', 'font-weight':'400 !important'});
	    		setTimeout(function(){
	              $(document).find('#error').remove();
	            }, 2000);
	    		return;
	    	}

	    	swal({
		        title: 'Do You Want To Register?',
		        text: '',
		        icon: 'warning',
		        buttons: true,
		        dangerMode: true,
		      })
		      .then((willactive) => {
		        if (willactive) {
		         $.ajax({
		             url: base_url+'godown/addStorage',
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
		                  sweet_alert("Good Job!",'Complain Registered SuccessFully',"success");
		                  setTimeout(function(){
		                    if(is_user == '1'){
		                        location.reload();
		                    }else{
		                        location.href = base_url+'godown';
		                    }
		                  },2000);

		                }else{
		                  sweet_alert("Warning!",'Some Error Occured',"warning");
		                } 
		             }
		         }); 
		        } 
		      });
	    }
	});


	//edit
	$("#createStorageEdit").validate({
		rules: {
			form_no: {
				required: true,
			},
			renewalLic: {
				required: true,
			},
			name: {
				required: true,
			},
			address_1: {
				required: true,
			},
			mobile: {
				required: true,
			},
			godaddress_1: {
				required: true,
			},
			productName: {
				required:true,
			},
			godownArea: {
				required: true,
			},
			godownType: {
				required: true,
			},
			startDate: {
				required: true
			},
			otherMunLic: {
				required: true,
			},
			explosive: {
				required: true,
			},
			pendingDues: {
				required: true,
			},
			disapproveEarlier: {
				required: true,
			}
		},
		messages: {
			form_no: "Enter Form No",
			renewalLic: "Select License Type",
			name: "Enter Name",
			address_1: "Enter Address",
			mobile: "Enter Mobile No",
			godaddress_1: "Enter Godown Address",
			productName: "Enter Product Name",
			godownArea: "Enter Godown Area",
			godownType: "Enter Type Of Godown",
			startDate: "Select Start Date",
			otherMunLic: "Enter Other License",
			explosive: "Select Explosive",
			pendingDues: "Enter PendingDues",
			disapproveEarlier: "Select Approval"
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

	    	var form_data = new FormData(document.getElementById("createStorageEdit"));

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
		             url: base_url+'godown/editStorage',
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
		                  sweet_alert("Good Job!",'Complain Edited SuccessFully',"success");
		                  setTimeout(function(){
		                    location.href = base_url+'godown';
		                  },2000);

		                }else{
		                  sweet_alert("Warning!",'Some Error Occured',"warning");
		                } 
		             }
		         });
		        }
		     });   
	    }
	});

	$(document).on('click', '.delete', function(){
		var appId = $(this).data('id');
		var ids = $(this).data('ids');
		if(ids == '1'){
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
	      })
	      .then((willactive) => {
	        if (willactive) {
	          $.ajax({
	             url: base_url+'godown/delStorage',
	             type:"post",
	             data: {'appid':appId, 'ids': ids},
	             async:false,
	             success: function(data){
	                 // console.log(data);
	                 var data = $.parseJSON(data);
	                  // console.log(data.success);
	                if(data.success == '1'){
	                  sweet_alert("Good Job!",'Complain Inactive SuccessFully',"success");
	                  setTimeout(function(){
	                    location.href = base_url+'godown';
	                  },2000);

	                }else{
	                  sweet_alert("Warning!",'Activated SuccessFully',"warning");
	                } 
	             }
	         });
	        }
	      });  
	});

	//approval Modal
    $(document).on('click', '.approvalStatus', function(){
		
      let selectRows = "";
      let select = "";
      let appId = $(this).data('id');
      
      $.ajax({
        url: base_url+"godown/getAppStatus",
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

	//get remarks
    $(document).on("click",".remarks_check", function(){
      // alert("data");
      var appId = $(this).data("appid");
      let trRow = "";
      //remarksGet
      $.ajax({
        url: base_url+"godown/remarksGet",
        type: "POST",
        dataType: "json",
        data: {'godownId': appId},
        async: false,
        success: function(res){
          // console.log(res.result);
          // return;
          var srNo = 1;
          $.each(res.result, function(ind,val){
            var username = val.user_name;
            var str = username.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });
            trRow += "<tr class = 'text-center'><td>"+srNo+"</td><td>"+val.remarks+"</td><td>"+str+"</td><td>"+val.created_at+"</td></tr>";
             // $("#docs-body").append(tr);    
             // console.log(trRow);
            srNo++;
          });

        }
      });

      $("#remarks-body").children().remove();
      $("#remarks-body").append(trRow);
      // $(document).find("#complain_id_app").val(complainId);
      // $("#docs-table > thead, #docs-body, tr").css({'display': 'table', 'width': "100%", 'table-layout': "fixed"});
      $('#modal-remarks').modal('show');

    });
});