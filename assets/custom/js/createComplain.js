$(document).ready(function(){

	var is_visitor = createComplain.getAttribute('is_visitor');
	var is_user = createComplain.getAttribute("is_user");
	var is_type = createComplain.getAttribute("is_type");
	
	//Edit form modification
	if(is_type == 'edit'){
		  $("#treeCuttingformEdit").validate({
				rules: {
						form_no: {
							required: true,
						},
						application_date: {
							required: true,
						},
						applicant_name: {
							required: true,
						},
						applicant_mobile_no: {
							required: true,
							minlength:9,
							maxlength:10,
							number: true
						},
						applicant_email: {
							required: true,
							email: true
						},
						applicant_address: {
							required:true,
						},
						survey_no: {
							// number: true
							number:true
						},
						city_survey_no: {
							// required: true,
							number: true
						},
						ward_no: {
							required: true,
							number: true
						},
						plot_no: {
							// required: true,
							number: true
						},
						// no_of_trees: {
						// 	required: true,
						// 	number: true
						// },
						declaration: {
							required: true,
						}
				},
				messages: {
						applicant_name: "Please Enter Application Name",
						applicant_mobile_no: "Please Enter Correct Mobile Number",
						applicant_email: "Please Enter Email Address",
						applicant_address: "Please Enter Address",
						survey_no: "Please Enter Correct Survey No.",
						city_survey_no: "Please Enter Correct City Survey No",
						ward_no: "Please Enter Correct Ward No",
						plot_no: "Please Enter Correct Plot No.",
						no_of_trees: "Please Enter Correct No. Of Trees",
						declaration: "Please Select Declaration",
						ownership_file: "This field is required"
				},
				errorPlacement: (error, element) => {
						// error.addClass( "ui red pointing label transition" );
						// error.insertAfter( element.after() );
				},
				invalidHandler: (event, validator) => {
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
				submitHandler: function(form,event) {

						let temp_array = [];

						$('.gardenId_array').each((index , value)=>{
							if ($(value).parent().find('.refund_selecter').prop("checked")) {
								temp_array.push(1);
							} else {
								temp_array.push(2);
							}
						});

						var form_data = new FormData(document.getElementById("treeCuttingformEdit"));

						form_data.append('refund', temp_array);

						var blueprint = $("#perType").find(":selected").data("blueprint");
						form_data.append("is_blueprint",blueprint);
						$.ajax({
								type: "POST",
								url: base_url + 'update_treecutting_application',
								data: form_data,
								processData:false, contentType:false, cache:false,async:false,
								success: response => {
									debugger ;
										if (response.status === true) {
												swal("Success!", response.message , "success");
												setTimeout(() =>{
														window.location.href = base_url + 'garden';
												},2000);
										} else {
												swal("Opps..!!!", response.message , "error");
										}
								},
								error: response => {
										swal("Opps..!!!", "Something went wrong" , "error");console.log(response);  
								}
						});
				}
			});
			
			if ($('#permission_type_data').val() == 0) {
				$('#perType').val(0);
				$('#perType').prop('disabled','true');
			} else {
				$('#perType').val($('#permission_type_data').val()).selectpicker('refresh');
			}

			if ($('#permission_type_data').val() == "3") {
				$("#blueprint").find("#blueprint").prop('required',false);
			}  

			if ($('#permission_type_data').val() != 0) {
				$(document).find('#error').remove();
				$(document).find("#blueprint_status").val($(this).find(':selected').data("blueprint"));
				$(document).find(".noOfTrees").show();
				$(document).find(".activity").hide();
				$(document).find(".condition").hide();
			} else {
					$(".blueprint").find("span").remove();
					$(document).find(".noOfTrees").hide();
					$(document).find(".activity").show();
					$(document).find(".condition").show();
					setTimeout(function(){
						$(document).find('#error_per').remove();
					}, 2000);
			}
	}
	//End Edit Form Modification

    function CapitalizeWord(str)
    {
      return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    }

    $('#treeName, #treeName_edit, #processName, #processName_edit').keyup(function(){
       var text =  $(this).val();
       text = CapitalizeWord(text);
       $(this).val(text);
    });

    // $('.form-check-input').val(0);
    // $(".form-check-input").change(function(){
    //   if(this.checked) {
    //         $('.form-check-input').val(1);
    //     }else{
    //       $('.form-check-input').val(0);  
    //     }
        
    // });

    $(document).on('click','.cancel',function(){
      location.href = base_url+'garden';
    });

    $("#perType").change(function(){
      if($(this).val() !== '0' && $(this).val() !== '5'){
        $(document).find('#error').remove();
		$(document).find("#blueprint_status").val($(this).find(':selected').data("blueprint"));

		//hide columns
		$(document).find(".noOfTrees").show();
		$(document).find(".activity").hide();
		$(document).find(".condition").hide();
				
        if($(this).find(':selected').data("blueprint") != '0'){
			$(".blueprint").css({'display': 'block'});
			if($(this).val() != '3'){
				$(".blueprint").find("#blueprint").prop('required',true);
				$(".blueprint").find("span").remove();
				$(".blueprint").find("label").append("<span class = 'red'>*</span>");
			}else{
				$(".blueprint").find("#blueprint").prop('required',false);
				$(".blueprint").find(".red").remove();
			}
        }else{
	        $(".blueprint").find("#blueprint").prop('required',false);
			$(".blueprint").find("span").remove();
		}
      }else{
		// $(document).find("#per_type_error").append('<label id="error_per" class="error ui red pointing label transition">This field is required.</label>').css({'color':'red', 'font-weight':'400 !important'});
		$(".blueprint").find("#blueprint").prop('required',true);
		$(".blueprint").find("span").remove();
		$(".blueprint").find("label").append("<span class = 'red'>*</span>");
		$(document).find(".noOfTrees").hide();
		$(document).find(".activity").show();
		$(document).find(".condition").show();
        setTimeout(function(){
          $(document).find('#error_per').remove();
        }, 2000);
      }
    });

    //validate js
    $("#treeCuttingform").validate({
      rules: {
        form_no: {
          required: true,
        },
        // application_date: {
        //   required: true,
        // },
        applicant_name: {
          required: true,
        },
        applicant_mobile_no: {
          required: true,
          minlength:9,
          maxlength:10,
          number: true
        },
        applicant_email: {
          required: true,
          email: true
        },
        applicant_address: {
          required:true,
        },
        survey_no: {
          // minlength:9,
          // maxlength:10,
          number: true
        },
        city_survey_no: {
          // required: true,
          // number: true
        },
        ward_no: {
          required: true,
          number: true
        },
        plot_no: {
          // required: true,
          number: true
        },
        // no_of_trees: {
        //   required: true,
        //   number: true
        // },
        declaration: {
          required: true,
				},
				ownership_file: {
					required: true,
				}
      },
      messages: {
        applicant_name: "Please Enter Application Name",
        applicant_mobile_no: "Please Enter Correct Mobile Number",
        applicant_email: "Please Enter Email Address",
        applicant_address: "Please Enter Address",
        survey_no: "Please Enter Correct Survey No.",
        city_survey_no: "Please Enter Correct City Survey No",
        ward_no: "Please Enter Correct Ward No",
        plot_no: "Please Enter Correct Plot No.",
        no_of_trees: "Please Enter Correct No. Of Trees",
				declaration: "Please Select Declaration",
				ownership_file: "This field is required"
      },
      errorPlacement: function ( error, element ) {
        // console.log(error);
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

        //check input file
        var statusInput = false;

        var totalUploadCnt = 0;
				var totalFileCnt = 0;
				
				//tree image check
        $('.uploadFiles').each(function(){
          totalUploadCnt++;          
          if($(this).val() == ''){
            $(this).parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({'color':'red', 'font-weight':'400 !important'});

            setTimeout(function(){
              $(document).find('#error').remove();
            }, 2000);
            return;
          }else{
            totalFileCnt++;
          }
        });

        if(totalUploadCnt == totalFileCnt){
          statusInput = true;
        }
				//End tree image
        
				//End check for permission type
				var permissionType = $("#perType").val();

				if(permissionType != '0' && permissionType != '5'){
					//permission type present
					//no of trees
					var totalTreeCnt = 0;
					var totalTreeCntCheck = 0;
					var statusTreeCnt = false;

					$(".total_trees").each(function(){
						totalTreeCnt++;
						
						if($(this).val() == '0'){
							$(this).parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({'color':'red', 'font-weight':'400 !important'});
							
							setTimeout(function(){
								$(document).find('#error').remove();
							}, 2000);
							return;
						}else{
							statusTreeCnt++;
						}
					});

					if(activityTotal == activityTotalCheck){
						statusProcess = true;
					}

				}else{
					// permission type is 0
					//activity
					var activityTotal = 0;
					var activityTotalCheck = 0;
					var statusProcess = false;
					$(".processName").each(function(){
						activityTotal++;
						
						if($(this).val() == '0'){
							$(this).parent().parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({'color':'red', 'font-weight':'400 !important'});
							
							setTimeout(function(){
								$(document).find('#error').remove();
							}, 2000);
							return;
						}else{
							activityTotalCheck++;
						}
					});

					if(activityTotal == activityTotalCheck){
						statusProcess = true;
					}
					//condition
					var conditionTotal = 0;
					var conditionTotalCheck = 0;
					var statusCondition = false;
					$(".condition").each(function(){
						conditionTotal++;
						
						if($(this).val() == '0'){
							$(this).parent().parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({'color':'red', 'font-weight':'400 !important'});
							
							setTimeout(function(){
								$(document).find('#error').remove();
							}, 2000);
							return;
						}else{
							conditionTotalCheck++;
						}
					});

					if(conditionTotal == conditionTotalCheck){
						statusCondition = true;
					}
					//end condition
				}

				//tree name condition
				var treeNameTotal = 0;
				var treeNameCheckTotal = 0;
				var statusTreeName = false;
				$(".treename").each(function(){
					treeNameTotal++;
					if($(this).val() == '0'){
						$(this).parent().parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({'color':'red', 'font-weight':'400 !important'});

						setTimeout(function(){
              $(document).find('#error').remove();
            }, 2000);
            return;
					}else{
						treeNameCheckTotal++;
					}
				});

				if(treeNameTotal == treeNameCheckTotal){
					statusTreeName = true;
				}
				//end tree name condition
				
				//reason for permisson
				var reasonTotal = 0;
				var reasonTotalCheck = 0;
				var statusReason = false;
				$(".reason_for_permission").each(function(){
					reasonTotal++;
					if($(this).val() == ''){
            $(this).parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({'color':'red', 'font-weight':'400 !important'});

            setTimeout(function(){
              $(document).find('#error').remove();
            }, 2000);
            return;
          }else{
            reasonTotalCheck++;
          }
				});

				if(reasonTotal == reasonTotalCheck){
					statusReason = true;
				}

				//end reason for permission
				// console.log("input:"+statusInput+"reason:"+statusReason+"treename:"+statusTreeName+"process"+statusProcess+"cond:"+statusCondition);

				var condnCheck = "";

				if(permissionType != 0){
					condnCheck = "statusTree && statusTreeCnt && statusReason && statusInput";
				}else{
					condnCheck = "statusTree && statusProcess && statusCondition && statusReason && statusInput";
				}
			
        if(condnCheck){
					
          var form_data = new FormData(document.getElementById("treeCuttingform"));  

          swal({
            title: 'Are You Sure Want To Submit?',
              text: '',
              icon: 'warning',
              buttons: true,
              dangerMode: true,
          }).then((willactive) => {
            if(willactive){
              $.ajax({
                 url: base_url+'garden/submitComplaint',
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

                      // $(".alert-success").css({'display':'block'});
                      // $(".alert-success").find("#alert-success").text("Complain Registered SuccessFully");

                      setTimeout(function(){
                        // $(".alert-success").css({'display':'none'});
                        if(is_user == '1'){
                            location.reload();
                        }else{
                            location.href = base_url+'garden';    
                        }
                        
                      },2000);

                    }else{
                      sweet_alert("Warning!",'Some Error Occured',"warning");
                      // $(".alert-danger").css({'display':'block'});
                      // $(".alert-danger").find("#alert-danger").text("Some Error Occured");

                      // setTimeout(function(){
                      //   $(".alert-danger").css({'display':'none'});
                      // },1000);
                    } 
                 }
             });
            }
          });

          
        }
      }
    });


    //End validate js

    //formsubmit
    // $("#treeCuttingform").submit(function(e){
    //   e.preventDefault(); 
    //      $.ajax({
    //          url:'<?= base_url()?>garden/submitComplaint',
    //          type:"post",
    //          data:new FormData(this),
    //          processData:false,
    //          contentType:false,
    //          cache:false,
    //          async:false,
    //          success: function(data){
    //              // console.log(data);
    //              var data = $.parseJSON(data);
    //               console.log(data.success);
    //             if(data.success == '1'){

    //               $(".alert-success").css({'display':'block'});
    //               $(".alert-success").find("#alert-success").text("Complain Registered SuccessFully");

    //               setTimeout(function(){
    //                 $(".alert-success").css({'display':'none'});
    //                 location.reload(true);
    //               },2000);

    //             }else{
    //               $(".alert-danger").css({'display':'block'});
    //               $(".alert-danger").find("#alert-danger").text("Some Error Occured");

    //               setTimeout(function(){
    //                 $(".alert-danger").css({'display':'none'});
    //               },1000);
    //             } 
    //          }
    //      });
    // });

    //Edit Section
    // if($("#nocStatus").val() == '2'){
    //   $(".nocDiv").css({'display':'none'});
    // }

    // $('#nocStatus').change(function(){
    //   if($(this).val() == '1'){
    //     $(".nocDiv").css({'display':'block'});
    //   }else{
    //     $(".nocDiv").css({'display':'none'});
    //   }
    // });

    $('.form-check-input').val(0);
    $(".form-check-input").change(function(){
      if(this.checked) {
            $('.form-check-input').val(1);
        }else{
          $('.form-check-input').val(0);  
        }
        
    });

    function getTreeName(){
      let treeDrop = "<option value = '0'>Select Tree</option>";
      let treeDropdown = "";
      $.ajax({
        url:  base_url+"garden/getonlyTreeName",
        type: "POST",
        dataType: "json",
        async: false,
        success: function(res){
          // console.log(res);
          $.each(res, function(ind,val){
            treeDrop += "<option value = '"+val.tree_id+"'>"+val.treeName+"</option>";
          })
          treeDropdown = "<select class='selectpicker form-control treename' id='treeName' name='treeName[]' data-live-search='true'>"+treeDrop+"</select>";
        }
      });
      return treeDropdown;
    }//End Function


    function getActivity(){
      let activityDrop = "<option value = '0'>Select Activity</option>";
      let processDrop = "";
      $.ajax({
        url: base_url+"garden/getonlyProcessName",
        type: "POST",
        dataType: "json",
        async: false,
        success: function(res){
					// console.log(res);
					var array = ['3','4'];
          $.each(res, function(ind,val){
						if(!array.includes(val.garper_id)){
							activityDrop += "<option value = '"+val.garper_id+"' data-blueprint = '"+val.is_blueprint+"'>"+val.permission_type+"</option>";
						}
          })
          processDrop = "<select class='selectpicker form-control processName' id='processName' name='processName[]' data-live-search='true'>"+activityDrop+"</select>";
             
        }
      });
      return processDrop;
		}//End Function
		
		function getNewTreeNo(){
			let treeNo = "";
			$.ajax({
				url: base_url+"garden/getTreeNo",
        type: "POST",
        dataType: "json",
        async: false,
        success: function(res){
					treeNo = res;
				}
			});
			
			return treeNo;
		}

    var inc = 1;
    $(".addRow").click(function(){

				var permissionType = $(document).find("#perType").val();
				var row = "";

				var conditionDrop = "<select class = 'selectpicker form-control condition' name = 'condition[]' data-live-search = 'true' required><option value = '0'>Select Condition</option><option value = '1'>Hazardous</option><option value = '2'>Non Hazardous</option></select>";

				var vis_val = createComplain.getAttribute("is_visitor");
				var refundSec = (vis_val == 1) ? "<td class = 'text-center refundSection'><input type = 'checkbox' name = 'refund[]' required></td>" : "";
				
				 row = "<tr><td class = 'text-center treeNo' style = 'padding-top: 20px'><input type = 'hidden' name = 'tree_no[]' value = '"+getNewTreeNo()+"'>"+getNewTreeNo()+"</td><td class = 'text-center name'>"+getTreeName()+"</td><td class = 'text-center activity'>"+getActivity()+"</td><td class = 'text-center condition'>"+conditionDrop+"</td></td><td class = 'text-center noOfTrees'><input type = 'number' min = '0' name = 'total_trees[]' id = 'total_trees_"+inc+"' placeholder='Action On No Of Trees' class = 'form-control' required = ''></td><td class = 'text-center permission'><input type='text' class='form-control reason_for_permission' name='reason_for_permission[]' id='reason_for_permission_"+inc+"' placeholder='Enter Reason' required = ''></td><td class = 'text-center photo'><input id='file-upload_"+inc+"' name='filesNew[]' class = 'form-control uploadFiles' type='file' required = ''><span class = 'file_name' ></span></td>"+refundSec+"<td class = 'text-center action'><span style = 'font-size: 25px; cursor:pointer' class = 'delete' data-id = '2'><i class='fas fa-trash'></i></span></td></tr>";

	      // console.log(row);
				$(".tableBody").append(row);
				
				if(permissionType != '0'){
					$(document).find(".noOfTrees").show();
					$(document).find(".activity").hide();
					$(document).find(".condition").hide();
				}else{
					$(document).find(".noOfTrees").hide();
					$(document).find(".activity").show();
					$(document).find(".condition").show();
				}

				if (is_visitor == '0') {
					$('.refund_checkbox').hide();
				} 	

      	inc++;

      	$('.selectpicker').selectpicker('refresh');

    });

    $(".tableBody").on('click', '.delete',function(){
      // alert($(this).data('id'));
      if($(this).data('id') != '0'){
        var gardenId = $(this).data('gardenid');
        
        let resCheck = "";

        $.ajax({
          url: base_url+"garden/gardenDelete",
          type: "POST",
          dataType: "json",
          data: {'gardenId': gardenId},
          async: false,
          success: function(res){
            resCheck = res.success;
          }
        });
        if(resCheck == 1){
          $(this).parent().parent().remove();  
        }else{
          alert("error occured");
        }
      }else{
        alert("Cant Delete But Can Update");
      }
	});
		
    // $("#treeCuttingformEdit").validate({
    //   rules: {
    //     form_no: {
    //       required: true,
    //     },
    //     application_date: {
    //       required: true,
    //     },
    //     applicant_name: {
    //       required: true,
    //     },
    //     applicant_mobile_no: {
    //       required: true,
    //       minlength:9,
    //       maxlength:10,
    //       number: true
    //     },
    //     applicant_email: {
    //       required: true,
    //       email: true
    //     },
    //     applicant_address: {
    //       required:true,
    //     },
    //     survey_no: {
    //       required: true,
    //       number: true
    //     },
    //     city_survey_no: {
    //       required: true,
    //       number: true
    //     },
    //     ward_no: {
    //       required: true,
    //       number: true
    //     },
    //     plot_no: {
    //       required: true,
    //       number: true
    //     },
    //     no_of_trees: {
    //       required: true,
    //       number: true
    //     },
    //     declaration: {
    //       required: true,
    //     }
    //   },
    //   messages: {
    //     application_date: "Please Select Application Date",
    //     applicant_name: "Please Enter Application Name",
    //     applicant_mobile_no: "Please Enter Correct Mobile Number",
    //     applicant_email: "Please Enter Correct Email Address",
    //     applicant_address: "Please Enter Address",
    //     survey_no: "Please Enter Correct Survey No.",
    //     city_survey_no: "Please Enter Correct City Survey No",
    //     ward_no: "Please Enter Correct Ward No",
    //     plot_no: "Please Enter Correct Plot No.",
    //     no_of_trees: "Please Enter Correct No. Of Trees",
    //     declaration: "Please Select Declaration"
    //   },
    //   errorPlacement: function ( error, element ) {
    //     console.log(error);
    //     error.addClass( "ui red pointing label transition" );
    //     error.insertAfter( element.after() );
    //   },

    //   invalidHandler: function(event, validator) {
    //   // 'this' refers to the form
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
		// 		var form_data = new FormData(document.getElementById("treeCuttingformEdit"));
		// 		var blueprint = $("#perType").find(":selected").data("blueprint");
		// 		form_data.append("is_blueprint",blueprint);

    //     swal({
    //         title: 'Are You Sure Want To Edit?',
    //           text: '',
    //           icon: 'warning',
    //           buttons: true,
    //           dangerMode: true,
    //       }).then((willactive) => {
    //         if(willactive){
    //           $.ajax({
    //              url: base_url+'garden/complainEdit',
    //              type:"post",
    //              data:form_data,
    //              processData:false,
    //              contentType:false,
    //              cache:false,
    //              async:false,
    //              success: function(data){
    //                  // console.log(data);
    //                  var data = $.parseJSON(data);
    //                   // console.log(data.success);
    //                 if(data.success == '1'){
                      
    //                   sweet_alert("Good Job!",'Complain Edited SuccessFully',"success");

    //                   // $(".alert-success").css({'display':'block'});
    //                   // $(".alert-success").find("#alert-success").text("Complain Registered SuccessFully");

    //                   setTimeout(function(){
    //                     // $(".alert-success").css({'display':'none'});
    //                     location.href = base_url+'garden';
    //                   },2000);

    //                 }else{
    //                   // $(".alert-danger").css({'display':'block'});
    //                   // $(".alert-danger").find("#alert-danger").text("Some Error Occured");
    //                   sweet_alert("Warning!",'Some Error Occured',"warning");

    //                   // setTimeout(function(){
    //                   //   $(".alert-danger").css({'display':'none'});
    //                   // },1000);
    //                 } 
    //              }
    //          });
    //         }
    //       });
    //   }
		// });
		
    //submit form
    // $("#treeCuttingformEdit").submit(function(e){
    //   e.preventDefault();
    //   $.ajax({
    //        url: base_url+'garden/complainEdit',
    //        type:"post",
    //        data:new FormData(this),
    //        processData:false,
    //        contentType:false,
    //        cache:false,
    //        async:false,
    //        success: function(data){
    //            // console.log(data);
    //            var data = $.parseJSON(data);
    //             console.log(data.success);
    //           if(data.success == '1'){

    //             $(".alert-success").css({'display':'block'});
    //             $(".alert-success").find("#alert-success").text("Complain Registered SuccessFully");

    //             setTimeout(function(){
    //               $(".alert-success").css({'display':'none'});
    //               location.reload(true);
    //             },2000);

    //           }else{
    //             $(".alert-danger").css({'display':'block'});
    //             $(".alert-danger").find("#alert-danger").text("Some Error Occured");

    //             setTimeout(function(){
    //               $(".alert-danger").css({'display':'none'});
    //             },1000);
    //           } 
    //        }
    //    });
    // });

    //index sections
    $(".deleteComplain").click(function(){
      var complainId = $(this).data('id');

      $.ajax({
        url: base_url+"garden/complainDelete",
        type: "POST",
        dataType: "json",
        data: {'complainId': complainId},
        async: false,
        success: function(res){
          // console.log(res.success);
          if(res.success == '1')
          {
            // $(".alert-success").css({'display':'block'});
            // $(".alert-success").find("#alert-success").text("Complain Deleted Successfully");
            sweet_alert("Success!",'Complain Deleted Successfully',"success");

            setTimeout(function(){
              // $(".alert-success").css({'display':'none'});
              location.reload(true);
            },1000);
          }else{
            // $(".alert-danger").css({'display':'block'});
            // $(".alert-danger").find("#alert-danger").text("Some Error Occured");
            sweet_alert("Warning!",'Some Error Occured',"warning");
            // setTimeout(function(){
            //   $(".alert-danger").css({'display':'none'});
            // },1000);
          }
        }
      });
    });

    //approval Modal
    $(document).on("click",".approvalStatus", function(){
      var approvalId = $(this).data('appid');
      let selectRows = "";
      let select = "";
      let html_str = "";
      let res = "";
			let is_payable = createComplain.getAttribute("is_payable");

      //get roledata
      let next_role_id = $(this).data('nextroleid');
      let modalOpenStatus = $(this).data('modalopenstatus');
      let currLoginRole = $(this).data('loginrole');
      let currLogindeptId = $(this).data('deptid');
			let prevroleid = $(this).data('lastroleid');
			
      $.ajax({
        url: base_url+"garden/getAppStatus",
        type: "POST",
        dataType: "json",
        data: {'approvalId': approvalId, 'deptId': currLogindeptId, 'roleId': currLoginRole},
        async: false,
        success: function(res){
					console.log(res);
          if (res.response_status == true) {

						var inspectionRow = "<tr class = 'text-center'><td>Inspection Fee@"+res['deposit_stack']['current_inspection_fees']+"</td><td rowspan = '4' style = 'vertical-align : middle;'>"+res['deposit_stack']['Tree_count']+"</td><td>"+res['deposit_stack']['total_inspection_amt']+"</td></tr>";
						var depositRow = "<tr class = 'text-center'><td>Deposit Fee@"+res['deposit_stack']['current_deposit_amt']+"</td><td>"+res['deposit_stack']['Total_deposit']+"</td></tr>";
						var TotalRow = "<tr class = 'text-center'><td>Net Total</td><td>"+res['deposit_stack']['netTotal']+"</td>";

						var tableRows = inspectionRow+depositRow+TotalRow;
            
            $.each(res.status, function(ind, val){
              selectRows += "<option value = '"+val.status_id+"'>"+val.status_title+"</option>";
						});
						
						$(".depBody").html(tableRows);
						
          } else {
            if (res.user_details.dept_id != 0) {
              html_str = '<center class="mt-5"><span class="text-danger mt-5">Your Date For Deposit And Inspection Is Exausted Please Update It.</span></center>';
              $(".appneddeposite").html(html_str);
            }
            $('.approve').hide(); 
          $.each(res.status, function(ind, val){
            selectRows += "<option value = '"+val.status_id+"'>"+val.status_title+"</option>";
          });

          }
        }
      });

      select = "<select class='selectpicker form-control' name='app_status' data-live-search='true'>"+selectRows+"</select>";

      $(document).find(".app_status").children().remove();
      
      $(".app_status").append(select);
      
			$('.selectpicker').selectpicker('refresh');
			
      $(document).find("#complain_id_app").val(approvalId);
      // console.log(currLoginRole+"-"+next_role_id+"-"+modalOpenStatus);
      // console.log(prevroleid+"-"+currLoginRole);
      if(prevroleid == currLoginRole){
        $(document).find(".approve").css({'display':'block'});
      }else if((currLoginRole != next_role_id) && (modalOpenStatus == '1') && next_role_id != '0'){
        $(document).find(".approve").css({'display':'none'});
      }else if(next_role_id == '0' && modalOpenStatus == '1'){
        $(document).find(".approve").css({'display':'block'});
      }

      $('#modal-approval').modal('show');
    });
    //END Approval Modal

    //docs Model

    $(document).on('click','.docs', function(){
      var complainId = $(this).data('id');
      let gardendata = "";
      let trRow = "";
      let path = base_url+"uploads/gardenImages/";
      //get gardendata 
      $.ajax({
        url: base_url+"garden/getGardenDataById",
        type: "POST",
        dataType: "json",
        data: {'complainId': complainId},
        async: false,
        success: function(res){
          // console.log(res.gardenData);
          
          var srNo = 1;
          $.each(res.gardenData, function(ind,val){
            trRow += "<tr class = 'text-center'><td>"+srNo+"</td><td>"+val.tree_no+"</td><td>"+val.treeName+"</td><td>"+val.permission_name+"</td><td>"+val.totalTrees+"</td><td>"+val.condition_name+"</td><td>"+val.reason_permission+"</td><td><a href = '"+path+val.enc_image+"' download><i class = 'fa fa-download' aria-hidden='true'></i></a></td></tr>";
             // $("#docs-body").append(tr);    
             // console.log(trRow);
            srNo++;
          });

        }
      });

      $("#docs-body").children().remove();
      $("#docs-body").append(trRow);
      
      // $("#docs-table > thead, #docs-body, tr").css({'display': 'table', 'width': "100%", 'table-layout': "fixed"});
      $('#modal-docs').modal('show');
    });

    //change status
    $(document).on("click",".complainStatus", function(){
      var statusId = $(this).data('id');
      if(statusId == '1'){
        title = "Do You Want To Inactive?";
      }else{
        title = "Do You Want To Activate?";
      }
      var appId = $(this).data("appid");

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
              url: base_url+'garden/complainDelete',
              type: "POST",
              dataType: "json",
              data: {'complainId': appId, 'appId': statusId},
              async: false,
              success: function(res){
                // console.log(res.success);
                if(res.success == '1')
                {
                  sweet_alert("Good Job!",'Complain Inactive SuccessFully',"success");
                    setTimeout(function(){
                      location.href = base_url+'garden';
                    },2000);
                }else{
                  sweet_alert("Warning!",'Activated SuccessFully',"warning");
                }

              }
            });
          }
        });  
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
             url: base_url+'garden/complainApprove',
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
    
    //get remarks
    $(document).on("click",".remarkscheck", function(){
      // alert("data");
      var appId = $(this).data("id");
      let trRow = "";
      
      //remarksGet
      $.ajax({
        url: base_url+"garden/remarksGet",
        type: "POST",
        dataType: "json",
        data: {'complainId': appId},
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
			
			if(trRow == ''){
				trRow += "<tr class = 'text-center'><td colspan = '4'>No Data Found</td></tr>";
			}

      $("#remarks-body").children().remove();
      $("#remarks-body").append(trRow);
      // $(document).find("#complain_id_app").val(complainId);
      // $("#docs-table > thead, #docs-body, tr").css({'display': 'table', 'width': "100%", 'table-layout': "fixed"});
      $('#modal-remarks').modal('show');

    });

    jQuery.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "Letters only please");

    $("#addTree").validate({
      rules: {
        'treeName' : {
          required: true,
          lettersonly: true
        }
      },
      messages: {
        treeName: "Enter Correct Tree Name",
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
        var formdata = new FormData(document.getElementById("addTree"));

        $.ajax({
             url: base_url+'garden/treeNameSubmit',
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

                  // $(".alert-success").css({'display':'block'});
                  // $(".alert-success").find("#alert-success").text("Complain Registered SuccessFully");

                  swal("Success!", "Registered Successfully", "success");

                  setTimeout(function(){
                    // $(".alert-success").css({'display':'none'});
                    location.href = base_url+"garden/addTree"
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


    //create process
    $("#addProcess").validate({
      rules: {
        'processName' : {
          required: true,
          lettersonly: true
        }
      },
      messages: {
        processName: "Enter Correct Process Name",
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
        var formdata = new FormData(document.getElementById("addProcess"));

        $.ajax({
             url: base_url+'garden/processSubmit',
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

                  swal("Success!", "Registered Successfully", "success");

                  setTimeout(function(){
                    // $(".alert-success").css({'display':'none'});
                    location.href = base_url+"garden/addProcess"
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

    $(document).on("click",".save", function(){
      var treeNameEdit = $("#treeName_edit").val();
      var treeIdEdit = $(document).find("#treeid_edit").val();
      var pattern = /^[a-zA-Z\s]+$/;
      var status = true;

      if(!pattern.test(treeNameEdit) && treeNameEdit == ''){
          status = false;
          return false;
      }

      if(status){

        $.ajax({
          url: base_url+"garden/treeEdit",
          type: "POST",
          dataType: "json",
          data: {'treeName': treeNameEdit, 'treeId': treeIdEdit},
          async: true,
          success: function(res){
            // console.log(res);
            if(res.success == '1'){
              // $(".alert-success-edit").css({'display':'block'});
              // $(".alert-success-edit").find("#alert-success").text("License Type Edited SuccessFully");
              sweet_alert("Good Job!","Edited Successfully","success");

              setTimeout(function(){
                // $(".alert-success-edit").css({'display':'none'});
                location.href = base_url+"garden/addTree";
              },2000);
              
            }else{
              sweet_alert("Warning!","Failed","warning");
            }
          },
        });

      }else{
        sweet_alert("Warning!","Please Enter Proper Value","warning");
      }

    });

    $(document).on("click",".saveProcess", function(){
      var processNameEdit = $("#processName_edit").val();
      var processIdEdit = $(document).find("#processid_edit").val();
      var pattern = /^[a-zA-Z\s]+$/;
      var status = true;

      if(!pattern.test(processNameEdit) && processNameEdit == ''){
          status = false;
          return false;
      }

      if(status){

        $.ajax({
          url: base_url+"garden/processEdits",
          type: "POST",
          dataType: "json",
          data: {'processName': processNameEdit, 'processId': processIdEdit},
          async: true,
          success: function(res){
            // console.log(res);
            if(res.success == '1'){
              // $(".alert-success-edit").css({'display':'block'});
              // $(".alert-success-edit").find("#alert-success").text("License Type Edited SuccessFully");
              sweet_alert("Good Job!","Edited Successfully","success");

              setTimeout(function(){
                // $(".alert-success-edit").css({'display':'none'});
                location.href = base_url+"garden/addProcess";
              },2000);
              
            }else{
              sweet_alert("Warning!","Failed","warning");
            }
          },
        });

      }else{
        sweet_alert("Warning!","Please Enter Proper Value","warning");
      }

		});
		
		//reject and agree 
		//open modal
		$(document).on("change", "#declaration", function(){
			$(".terms-modal").modal({backdrop: 'static', keyboard: false});
		});

		$(document).on("click", ".reject", function(){
			//close modal declaration unchecked
			$(".terms-modal").modal("hide");
			$(document).find("#declaration").prop("checked",false);
		});

		$(document).on("click", ".agree", function(){
			//close modal declaration changed
			$(".terms-modal").modal("hide");
			$(document).find("#declaration").prop("checked",true);
		});
		//end reject and agree

		//refunds modal
		$(document).on("click", ".refunds", function(){
			var complain_id = $(this).data("appid");
			var loginRole = $(this).data("loginrole");
			let tableRow = "";
			let path = base_url+"uploads/gardenImages/";
			//get rufundable Garden Data (Non Hazardous)
			$.ajax({
				url: base_url+"garden/getRefundableData",
				type: "POST",
				dataType: "json",
				data: {'complainId': complain_id},
				async: false,
				success: function(res){
					// console.log(res['refundableData']);
					var srNo = 1;
					$.each(res['refundableData'], function(index, value){
						var payBtn;
						if(loginRole == '3' && res['show'] == 1){
							payBtn = (value.refundable == '2') ? 'Non Refundable' : (value.refund_approval == '1') ? '<span class = "btn btn-danger btn-sm canRef" data-complainid = "'+value.complain_Id+'" data-gardenid = "'+value.gardenId+'">Cancel Refund</span>' : '<span class = "btn btn-info btn-sm refPaid" data-gardenid = "'+value.gardenId+'" data-complainid = "'+value.complain_Id+'" style = "cursor:pointer">Refund</span>';
						}else{
							payBtn = "";
						}

						if(value.conditionStatus == '2')
						{
							tableRow += "<tr class = 'text-center'><td>"+srNo+"</td><td>"+value.tree_no+"</td><td>"+value.treeNames+"</td><td>"+value.permissionType+"</td><td>"+value.treeNo+"</td><td>"+value.conditionType+"</td><td>"+value.reason_permission+"</td><td><a href = '"+path+value.enc_image+"' download><i class = 'fa fa-download' aria-hidden='true'></i></a></td><td>"+payBtn+"</td></tr>";
						}

					});
				}
			});		

			if(tableRow == ''){
				tableRow = "<tr class = 'text-center'><td></td><td></td><td></td><td></td><td colspan = '2'>No Data Found</td><td></td><td></td><td></td></tr>";
			}
			
			$("#refunds-body").children().remove();
      $("#refunds-body").append(tableRow);	
			
			$("#modal-refunds").modal("show");
		});

		//approve payment
		// ALTER TABLE `gardendata` ADD `refund_approval` INT NOT NULL COMMENT '0:Not Approve, 1:Approve' AFTER `refundable`;

		$(document).on('click', '.refPaid', function(){
			let gardenId = $(this).data("gardenid");
			let complainId = $(this).data("complainid");
			var $this = $(this);
			// ALTER TABLE `gardendata` ADD `refund_approved_by` INT NOT NULL AFTER `refund_approval`;
			$.ajax({
				url: base_url+"garden/approveRefund",
				type: "POST",
				dataType: "json",
				data: {'complainId': complainId, 'gardenId': gardenId},
				async: false,
				success: function(res){
					// console.log(res);
					if(res.success == '1'){
						$this.parent().append("<span class = 'btn btn-danger btn-sm canRef' data-complainid = '"+complainId+"' data-gardenid = '"+gardenId+"' style = 'cursor:pointer'>Cancel Refund</span>");
						$this.remove();
					}
				}
			});	
		});


		//deactivate refund
		$(document).on("click", ".canRef", function(){
			let complainId = $(this).data("complainid");
			let gardenId = $(this).data("gardenid");
			var $this = $(this);

			$.ajax({
				url: base_url+"garden/approveRefundCancel",
				type: "POST",
				dataType: "json",
				data: {'complainId': complainId, 'gardenId': gardenId},
				async: false,
				success: function(res){
					if(res.success == '1'){
						$this.parent().append("<span class = 'btn btn-info btn-sm refPaid' data-gardenid = '"+gardenId+"' data-complainid = '"+complainId+"' style = 'cursor:pointer'>Refund</span>");
						$this.remove();
					}
				}
			});
		});
		//END Deactivation 
		//End Refunds Modal

});
