$(document).ready(function(){
    var is_user = createTemp.getAttribute("is_user");
  function CapitalizeWord(str)
  {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
  }

  $('#appName, #licName_edit, #licName').keyup(function(){
     var text =  $(this).val();
     text = CapitalizeWord(text);
     $(this).val(text);
  });

	//license type Start
	$("#addLic").click(function(){
      var licName = $("#licName").val();
      var pattern = /^[a-zA-Z\s]+$/;
      var status = true;

      if(!pattern.test(licName) && licName == ''){
          status = false;
          return false;
      }

      if(status && licName != ''){
        
        $.ajax({
          url: base_url+"templic/licSubmit",
          type: "POST",
          dataType: "json",
          data: {'licName': licName},
          async: true,
          success: function(res){
            // console.log(res);
            if(res.success == '1'){
              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("License Type Added SuccessFully");

              setTimeout(function(){
                $(".alert-success").css({'display':'none'});
                location.reload(true);
              },2000);
              
            }else{
              $(".alert-danger").css({'display':'block'});
              $(".alert-danger").find("#alert-danger").text("Some Error Occured Please Try Again");

              setTimeout(function(){
                $(".alert-danger").css({'display':'none'});
              },1000);
            }
          },
        });
      }else{
        $(".alert-danger").css({'display':'block'});
        $(".alert-danger").find("#alert-danger").text("Please Enter Proper Value");

        setTimeout(function(){
          $(".alert-danger").css({'display':'none'});
        },1000);
      }
    });//End Add License

    $(".deleteLic").click(function(){
      var lic_type_id = $(this).parent().parent().find("#lic_type_id").val();

      if(lic_type_id != ''){
        
        $.ajax({
          url: base_url+"templic/deleteLic",
          type: "POST",
          dataType: "json",
          data: {'lic_type_id': lic_type_id},
          // async: true,
          success: function(res){
            if(res.success == '1'){
              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("Lic Type Deleted SuccessFully");

              setTimeout(function(){
                $(".alert-success").css({'display':'none'});
                location.reload(true);
              },2000);
            }else{
              $(".alert-danger").css({'display':'block'});
              $(".alert-danger").find("#alert-danger").text("Lic Type Deletion Failed");

              setTimeout(function(){
                $(".alert-danger").css({'display':'none'});
              },1000);
            }
          }
        });

      }else{
        $(".alert-danger").css({'display':'block'});
        $(".alert-danger").find("#alert-danger").text("Some Error Occured");

        setTimeout(function(){
          $(".alert-danger").css({'display':'none'});
        },1000);
      }
    });//End Delete Lic

    $(".licStatus").click(function(){
      var lic_type_Id = $(this).parent().parent().find('#lic_type_id').val();
      var actualStatus = $(this).parent().parent().find('#actualStatus').val();
      
      if(lic_type_Id != ''){
        
        $.ajax({
          url: base_url+"templic/deactivateLic",
          type: "POST",
          dataType: "json",
          data: {'lic_type_Id': lic_type_Id, 'actualStatus': actualStatus},
          // async: true,
          success: function(res){
            if(res.success == '1'){
              $(".alert-success").css({'display':'block'});
              $(".alert-success").find("#alert-success").text("Lic type deactivated SuccessFully");

              setTimeout(function(){
                $(".alert-success").css({'display':'none'});
                location.reload(true);
              },2000);
            }else{
              $(".alert-danger").css({'display':'block'});
              $(".alert-danger").find("#alert-danger").text("Lic type deactivation Failed");

              setTimeout(function(){
                $(".alert-danger").css({'display':'none'});
              },1000);
            }
          }
        });

      }else{
        $(".alert-danger").css({'display':'block'});
        $(".alert-danger").find("#alert-danger").text("Some Error Occured");

        setTimeout(function(){
          $(".alert-danger").css({'display':'none'});
        },1000);
      }
    });//Change Lic Status

    $(".editLic").click(function(){
      var lic_type_Id = $(this).parent().parent().find('#lic_type_id').val();

      $(document).find("#licId_edit").val(lic_type_Id);

      $('#modal-status').modal('show');
    });//open edit model

    $(document).on("click",".save", function(){
      var licNameEdit = $("#licName_edit").val();
      var licIdEdit = $(document).find("#licId_edit").val();
      var pattern = /^[a-zA-Z\s]+$/;
      var status = true;

      if(!pattern.test(licNameEdit) && licNameEdit == ''){
          status = false;
          return false;
      }

      if(status){

        $.ajax({
          url: base_url+"templic/licEdit",
          type: "POST",
          dataType: "json",
          data: {'licName': licNameEdit, 'licId': licIdEdit},
          async: true,
          success: function(res){
            // console.log(res);
            if(res.success == '1'){
              // $(".alert-success-edit").css({'display':'block'});
              // $(".alert-success-edit").find("#alert-success").text("License Type Edited SuccessFully");
              sweet_alert("Good Job!",res.messg,"success");

              setTimeout(function(){
                $(".alert-success-edit").css({'display':'none'});
                location.href = base_url+"templic/addLicType";
              },2000);
              
            }else{
              sweet_alert("Warning!",res.messg,"warning");
            }
          },
        });

      }else{
        sweet_alert("Warning!","Please Enter Proper Value","warning");
      }

    });


    $(".cancelEdit").click(function() {
      // $(this).closest('form').find("input[type=text], textarea").val("");
      window.location.href = base_url+ 'templic/addLicType';
    });

    $(".cancel").click(function() {
	    // $(this).closest('form').find("input[type=text], textarea").val("");
	    window.location.href = base_url+ 'templic';
	  });

    $("#addLicType").validate({
      rules: {
        'licName' : {
          required: true,
          lettersonly: true
        }
      },
      messages: {
        lic_name: "Enter Correct License Type Name",
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
        var formdata = new FormData(document.getElementById("addLicType"));

        $.ajax({
             url: base_url+'templic/licSubmit',
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

                  // $(".alert-success").css({  'display':'block'});
                  // $(".alert-success").find("#alert-success").text("Complain Registered SuccessFully");

                  swal("Success!", "Registered Successfully", "success");

                  setTimeout(function(){
                    // $(".alert-success").css({'display':'none'});
                    location.href = base_url+"templic/addLicType"
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

    //License Type end

    //license creation

    $("#renewalDate").change(function(){
    	// alert($(this).val());
    	var ren_date = $(this).val();
    	var splitDate = ren_date.split("/");//04/17/2020
    	var new_date = new Date(parseInt(splitDate[2]) + 1, splitDate[0], splitDate[1]);
    	var expiryDate = new_date.getMonth()+'/'+new_date.getDate()+'/'+new_date.getFullYear();

    	$(document).find("#expiryDate").val(expiryDate);
    });

    jQuery.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "Letters only please");

    $("#createApplicationForm").validate({
    	rules: {
    		form_no: {
    			required: true
    		},
    		licenseNo: {
    			required: true,
          number: true
    		},
    		// applicationDate: {
    		// 	required: true
    		// },
    		licenseType: {
    			required: true
    		},
    		appName: {
    			required: true,
          lettersonly: true
    		},
    		stallAddress: {
    			required: true
    		},
    		renewalDate: {
    			required: true
    		},
    		expiryDate: {
    			required: true
    		},
    		licenseCopy: {
    			required: true
    		},
    		aadhar: {
    			required: true
    		},
    		pan: {
    			required: true
    		},
    	},

    	messages: {
			form_no: "Enter Form No",
			licenseNo: "Enter Correct License No",
			// applicationDate: "Select Application Date",
			licenseType: "Selct License Type",
			appName: "Enter Correct App Name",
			stallAddress: "Enter Stall Address",
			renewalDate: "Select Renewal Date",
			expiryDate: "Select Expiry Date",
			licenseCopy: "Enter License Copy",
			aadhar: "Enter aadhar Copy",
			pan: "Enter Pan Copy"
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

	    	var form_data = new FormData(document.getElementById("createApplicationForm"));

	    	// console.log(form_data);return;

        swal({
          title: 'Are You Sure Want To Submit Application?',
            text: '',
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then((willactive) => {
          if(willactive){
            $.ajax({
               url: base_url+'templic/createApplication',
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

                    swal("Success!", "Registered Successfully", "success");

                    setTimeout(function(){
                      // $(".alert-success").css({'display':'none'});
                      if(is_user == '1'){
                          location.reload();
                      }else{
                        location.href = base_url+"templic/";    
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
	    }
    });

    //edit license Data
    $("#renewApplicationForm").validate({
    	rules: {
    		form_no: {
    			required: true
    		},
    		licenseNo: {
    			required: true,
          number: true
    		},
    		// applicationDate: {
    		// 	required: true
    		// },
    		licenseType: {
    			required: true
    		},
    		appName: {
    			required: true,
          lettersonly: true
    		},
    		stallAddress: {
    			required: true
    		},
    		renewalDate: {
    			required: true
    		},
    		expiryDate: {
    			required: true
    		}
    	},

    	messages: {
			form_no: "Enter Form No",
			licenseNo: "Enter Correct License No",
			// applicationDate: "Select Application Date",
			licenseType: "Selct License Type",
			appName: "Enter Correct App Name",
			stallAddress: "Enter Stall Address",
			renewalDate: "Select Renewal Date",
			expiryDate: "Select Expiry Date"
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

	    	var form_data = new FormData(document.getElementById("renewApplicationForm"));

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
                 url: base_url+'templic/editApplicationRenew',
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

                      $(".alert-success").css({'display':'block'});
                      $(".alert-success").find("#alert-success").text("License Edited SuccessFully");

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
            }
        });      
	    }
    });
    //End Edit license Data

    //apply for renewal 

    $(".renew").click(function(){
    	var licId = $(this).data('id');

    	$(document).find("#lic_id").val(licId);

    	$("#modal-renewal").modal();
    });

    $("#renewal_dateEdit").change(function(){

    	var ren_date = $(this).val();
    	var splitDate = ren_date.split("/");//04/17/2020
    	var new_date = new Date(parseInt(splitDate[2]) + 1, splitDate[0], splitDate[1]);
    	var expiryDate = new_date.getMonth()+'/'+new_date.getDate()+'/'+new_date.getFullYear();

    	$(document).find("#expiry_dateEdit").val(expiryDate);
    });

    $("#renewalForm").submit(function(e){
    	e.preventDefault();

	    	// console.log(form_data);return;

    	$.ajax({
         url: base_url+'templic/renewApplication',
         type:"POST",
         data: new FormData(this),
         processData:false,
         contentType:false,
         cache:false,
         async:false,
         success: function(data){
             // console.log(data);
            if(data.success == '1'){

              $(".alert-success-edit").css({'display':'block'});
              $(".alert-success-edit").find("#alert-success").text("Complain Registered SuccessFully");

              setTimeout(function(){
                $(".alert-success-edit").css({'display':'none'});
                location.reload(true);
              },2000);

            }else{
              $(".alert-danger-edit").css({'display':'block'});
              $(".alert-danger-edit").find("#alert-danger").text("Some Error Occured");

              setTimeout(function(){
                $(".alert-danger-edit").css({'display':'none'});
              },1000);
            } 
         }
     	});//Ajax

    });

    //END apply for renewal

    //edit 
    //End edit

    //delete
    $(".delete").click(function(){
    	var licId = $(this).data('id');
    	var actk = $(this).data('actk');
    	// alert(licId+"-"+actk);return;
    	$.ajax({
         url: base_url+'templic/deleteApplication',
         type:"POST",
         data: {'lic_id': licId, 'actk': actk},
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
    //End Delete

    //approval
  $(document).on('click', '.approvalStatus', function(){
    
      let selectRows = "";
      let select = "";
      let appId = $(this).data('appid');
      $.ajax({
        url: base_url+"templic/getAppStatus",
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
             url: base_url+'templic/approveComplain',
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

  //remarks modal
  $(document).on("click", ".remarks", function(){
    var id = $(this).data('id');
    
    let trRow = "";

    $.ajax({
      url: base_url+'templic/getRemarks',
          type:"POST",
          dataType: "json",
          data: {'lic_id': id},
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

    //END license creation
})