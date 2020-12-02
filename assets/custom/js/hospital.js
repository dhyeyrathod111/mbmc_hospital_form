

// Dhyey Rathod start
    

    $(document).on('change','input[type=radio][name=is_midwife]',event => {
        let is_midwife = event.target.value;
        if (is_midwife == 'Yes') {
            $('.midwife_container').show();
        } else if (is_midwife == 'No') {
            $('.midwife_container').hide();
        }
    });
    $(document).on('click','.addmidwifebtn',event => {
        $('#onemidwifeField').clone().appendTo(".midwifeTableBody");
    });
    $(document).on('click','.midwifeDeleteBtn',event => {
        if ($('.midwife_table tr').length > 2) {
            event.target.parentElement.parentElement.remove();
        }
    });


    $(document).on('change','input[type=radio][name=is_supervision]',event => {
        let is_supervision = event.target.value;
        if (is_supervision == 'Yes') {
            $('.supervision_container').show();
        } else if (is_supervision == 'No') {
            $('.supervision_container').hide();
        }
    });
    $(document).on('click','.addsupervisionbtn',event => {
        $('#onesupervisionField').clone().appendTo(".supervisionTableBody");
    });
    $(document).on('click','.supervisionDeleteBtn',event => {
        if ($('.supervision_table tr').length > 2) {
            event.target.parentElement.parentElement.remove();
        }
    });
    
    

    $(document).on('click','.surgeonBtn',event => {
        $('#surgeonField').clone().appendTo(".surgeonTableBody");
    });
    $(document).on('click','.surgeonDeleteBtn',event => {
        if ($('.surgeon_table tr').length > 2) {
            event.target.parentElement.parentElement.remove();
        }
    });
    
    $(document).on('click','.fsk_add_row',event => {
        $('#oneRow').clone().appendTo(".fsk_tablebody");
    });
    $(document).on('click','.fsk_delete_row',event => {
        if ($('.fsk_table tr').length > 2) {
            event.target.parentElement.parentElement.remove();
        }
    });


    $(document).on('click','.addFeesbtn',event => {
        $('#oneFeesField').clone().appendTo(".feesTableBody");
    });
    $(document).on('click','.fessDeleteBtn',event => {
        if ($('.fees_table tr').length > 2) {
            event.target.parentElement.parentElement.remove();
        }
    });

    $(document).on('change','input[type=radio][name=is_alien]',event => {
        let is_alien = event.target.value;
        if (is_alien == 'Yes') {
            $('.alien_container').show();
        } else if (is_alien == 'No') {
            $('.alien_container').hide();
        }
    });
    $(document).on('click','.addalienbtn',event => {
        $('#onealienField').clone().appendTo(".alienTableBody");
    });
    $(document).on('click','.alienDeleteBtn',event => {
        if ($('.alien_table tr').length > 2) {
            event.target.parentElement.parentElement.remove();
        }
    });



    $(document).on('click','#add_one_flore', event => { event.preventDefault();
        $('#singel_flore_data').clone().appendTo(".flore_container");
    });
    $(document).on("click",".delete_floor",Event => { Event.preventDefault();
        if ($('.flore_details_container').length > 1) {
            Event.target.parentElement.parentElement.parentElement.remove();
        } 
    });

    const load_user_apps_datatable = props => {
        var dataTable = $('#hospital_userapps_table').DataTable({
            "processing": true,
            "serverSide": true,
            "autoWidth" : false,
            "order": [],
            "columnDefs": [
                {
                    "targets": [],
                    "orderable": false,
                },
            ],
            "ajax": {
                url: base_url + 'hospital/datatable_userapplist',
                type: "POST",
                data: {
                    fromDate: $('#fromDate').val(),
                    toDate: $('#toDate').val(),
                }
            },
            "bDestroy": true
        });
    }

    $(document).on('change', '#fromDate,#toDate', ()=>load_user_apps_datatable());


    $( "#payment_approvel_form" ).validate({
        submitHandler: form => {
            var form_data = JSON.stringify($(form).serializeArray());
            $('#submit_btn_text').text('Processing....').prop("disabled",true);
            $.ajax({
                type: "POST",
                url: $(form).attr('action'),
                data: JSON.parse(form_data),
                success: response => {
                    $('#submit_btn_text').text('Approved').prop("disabled",false);
                    if (response.status == true) {
                        notify_success(response.message);
                    } else {
                        notify_error(response.message);
                    }
                    setTimeout(function(){ location.reload() }, 3000);
                },
                error: response=>{
                    notify_error('Sorry, we have to face some technical issues please try again later.');
                    console.log(response);
                }
            });
        }
    });

    
    $(document).on("click",".payment_approvel_btn",event => { 
        event.preventDefault();
        let app_id = event.target.getAttribute('app_id');
        $.ajax({
            type: "POST",
            url: base_url + 'hospital/payment_approvel_modal',
            data:{app_id:app_id},
            success: response => {
                if (response.status == true) {
                    $('#payment_approvel_form').html(response.html_str);
                    $('#payment_approvel_modal').modal('show');
                } else {
                    swal("Opps...!!",response.message,"error").then(() => location.reload());
                }
            },
            error: response => {
                console.log(response.responseText);
                toster_alert_error("Sorry, we have to face some technical issues please try again later.");
            }
        });
    });


    $( "#payment_request_form" ).validate({
        rules: {
            description:{
                required:true,
            },
        },
        errorPlacement: function ( error, element ) {
            console.log(element);
            error.addClass( "ui red pointing label transition" );
            error.insertAfter(element.after());
        },
        submitHandler: form => {
            var form_data = JSON.stringify($(form).serializeArray());
            $('#submit_btn_text').text('Processing....').prop("disabled",true);
            $.ajax({
                type: "POST",
                url: $(form).attr('action'),
                data: JSON.parse(form_data),
                success: response => {
                    $('#submit_btn_text').text('Send Payment Reqeust').prop("disabled",false);
                    if (response.status == true) {
                        notify_success(response.message);
                    } else {
                        notify_error(response.message);
                    }
                    setTimeout(function(){ location.reload() }, 3000);
                },
                error: response=>{
                    notify_error('Sorry, we have to face some technical issues please try again later.');
                    console.log(response);
                }
            });
        }
    });



$(document).on("click",".payment_request_btn",event => { event.preventDefault();
    let app_id = event.target.getAttribute('app_id');
    $.ajax({
        type: "POST",
        url: base_url + 'hospital/payment_reqeust_popup',
        data:{app_id:app_id},
        success: response => {
            if (response.status == true) {
                $('#payment_request_form').html(response.html_str);
                $('#payment_request_modal').modal('show');
            } else {
                swal("Opps...!!",response.message,"error").then(() => location.reload());
            }
        },
        error: response => {
            console.log(response.responseText);
            toster_alert_error("Sorry, we have to face some technical issues please try again later.");
        }
    });
});

$( "#inspection_form" ).validate({
    submitHandler : form => {
        var form_data = JSON.stringify($(form).serializeArray());
        $('#inspection_form_submitbtn').text('Processing....').prop("disabled",true);
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: JSON.parse(form_data),
            success: response => {
                if (response.status == true) {
                    notify_success(response.message);
                } else {
                    notify_error(response.message);
                }
                setTimeout(function(){ $('#inspection_form_modal').modal('hide') }, 3000);
            },
            error: response=>{
                notify_error('Sorry, we have to face some technical issues please try again later.');
                console.log(response.responseText);
            }
        });
    }
});

$(document).on("click",".inspection_form_btn",event => { event.preventDefault();
    let app_id = event.target.getAttribute('app_id');
    $.ajax({
        type: "POST",
        url: base_url + 'hospital/inspection_form_display',
        data: {app_id:app_id},
        success: response => {
            if (response.status == true) {
                $('#inspection_form').html(response.html_str);

                $("#bio_medical_valid_date").datepicker({dateFormat: 'yy-mm-dd'});
                $("#mpcb_certificate_valid_date").datepicker({dateFormat: 'yy-mm-dd'});

                $('#inspection_form_modal').modal('show');
            } else {
                swal("Opps...!!",response.message,"error").then(() => location.reload());
            }
        },
        error: response => {
            console.log(response.responseText);
            swal("Opps...!!",'Sorry, we have to face some technical issues please try again later.',"error").then(() => location.reload());
        }
    });
});


$(document).on("click","#addRow",Event => { Event.preventDefault();
    let main_node = $("#oneStaffField").clone();
    main_node.find("input").each((index , node) => node.value = '');
    $(".tableBody").append(main_node);
});

$(document).on("click",".delete_row",Event => { Event.preventDefault();
    if ($('.staff_grid tr').length > 2) {
        Event.target.parentElement.parentElement.remove();
    } else {
        toster_alert_info('You should add atleast one staff information.')
    }
});

const toster_alert_success = message => {
    $.toast({
        heading: 'Success...!!!',
        text: message,
        position: 'top-right',
        stack: false,
        allowToastClose: false,
        icon: 'success'
    });
}

const toster_alert_info = message => {
    $.toast({
        heading: 'Warning...!!!',
        text: message,
        position: 'top-right',
        stack: false,
        allowToastClose: false,
        icon: 'info'
    });
}

const toster_alert_error = (message='') => {
    if (message == '') {
        message = "Sorry, we have to face some technical issues please try again later."
    } 
    $.toast({
        heading: 'Opps..!!',
        text: message,
        position: 'top-right',
        stack: false,
        allowToastClose: false,
        icon: 'error'
    });
}

const userListDataTable = () => {
    var hospital = $('#hospital_userapp').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth" : false,
        "order": [],
        "columnDefs": [
            {
                "orderable": false,
            },
        ],
        "ajax": {
            url: base_url + '/authuser/get_userHospital',
            type: "POST",
        },
        "bDestroy": true,
    });
}

  
// Dhyey Rathod end




$(document).ready(function(){



    // Dhyey code start
    $('#staff_designation,#fs_for_kitchen_user_type,#approval_status,#staff_qualification').selectpicker('destroy');
    $("#application_date_hospital").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
    $("#Certificate_expirydate").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
    $("#hospital-form :input").each((index , item)=>item.setAttribute('autocomplete','off'));
    // Dhyey code end 



    var is_user = createHospital.getAttribute("is_user");
    
  function getDesignation(){
    let option = "<option value = '0'>Select designation</option>";
    let designation = "";
    $.ajax({
      url:  base_url+"designation/get-designation",
      type: "POST",
      dataType: "json",
      async: false,
      success: function(res){
        console.log(res);
        $.each(res.designation, function(ind,val){
          console.log(val.design_title);
          option += "<option value = '"+val.design_id+"'>"+val.design_title+"</option>";
        })
        designation = "<select class='selectpicker form-control' id='designation' name='designation[]' data-live-search='true'>"+option+"</select>";
      }
    });
    return designation;
  }//End Function

  function getQualification(){
    let option = "<option value = '0'>Select qualification</option>";
    let qualification = "";
    $.ajax({
      url:  base_url+"qualification/get-qualification",
      type: "POST",
      dataType: "json",
      async: false,
      success: function(res){
        // console.log(res);
        $.each(res.qualification, function(ind,val){

          option += "<option value = '"+val.qual_id+"'>"+val.qual_title+"</option>";

        })
        qualification = "<select class='selectpicker form-control' id='qualification' name='qualification[]' data-live-search='true'>"+option+"</select>";
      }
    });
    return qualification;
  }//End Function


  $(".addRow").click(function(){
    var row = "<tr>\n\
                <td class = 'text-center name'>\n\
                  <input type='text' class='form-control' name='staff_name[]'' id='staff_name' placeholder='Enter Staff Name'>\n\
                </td>\n\
                <td class = 'text-center activity'>"+getDesignation()+"</td>\n\
                <td class = 'text-center noOfTrees'>\n\
                  <input type = 'number' class='form-control' name = 'age[]' id = 'age[]' placeholder='Enter Age' required=''>\n\
                </td>\n\
                <td class = 'text-center name'>"+getQualification()+"</td>\n\
                <td class = 'text-center action'>\n\
                  <span style = 'cursor:pointer' class = 'delete' data-id = '2'><i class='fas fa-trash'></i></span>\n\
                </td></tr>";
    
    $(".tableBody").append(row);

    $('.selectpicker').selectpicker('refresh');

  });


  $(".tableBody").on('click', '.delete',function(){
    obj = $(this);
    var staff_id = $(this).attr('data-staff-id');
    // console.log($(this).parent().parent().remove());
    // return false;
    if( staff_id != '0'){

      $.ajax({
        url: base_url+"hospital/staffDelete",
        type: "POST",
        dataType: "json",
        data: {'staff_id': staff_id},
        async: false,
        success: function(res){
          if(res.status =='1') {
            
            swal("Good Job!",res.messg,"success")
            .then((value) => {
              $(obj).parent().parent().remove();
            });
          } else if(res.status =='2'){
            swal("Warning!",res.messg,"warning");
          } 
        }
      });

    }else{
      swal("Warning!",res.messg,"warning");
    }
  });

  $( "#hospital-form" ).validate({
    rules: {
      // application_no: {
      //   required: true,
      // },
      // applicant_name: {
      //   required: true,
      // },
      // applicant_email_id: {
      //   required: true,
      //   email: true
      // },
      // applicant_mobile_no: {
      //   required: true,
      //   maxlength: 10,
      // },
      // applicant_address:{
      //   required: true,
      // },
      // // letter_no: "required",
      // applicant_nationality: {
      //   required: true,
      // },
      // technical_qualification: {
      //   required: true,
      // },
      // hospital_name: {
      //   required: true,
      // },
      // contact_person: {
      //   required: true,
      // },
      // contact_no: {
      //   required: true,
      // },
      // hospital_address: {
      //   required: true,
      // },
      // floor_space: {
      //   required: true,
      // },
      // maternity_beds: {
      //   required: true,
      // },
      // patient_beds: {
      //   required: true,
      // },
      

    },
    messages: {
      applicant_name: "Please provide user name.",
      applicant_email_id: "Please provide email id.",
      applicant_mobile_no: {
        required: "Please provide mobile no.",
        maxlength: "Your mobile no. must be of 10 digits."
      },
      applicant_address: "Please provide the address.",
      applicant_nationality: "Please provide the nationality.",
      technical_qualification: "Please provide the technical qualifiction.",
      hospital_name: "Please provide hospital name.",
      hospital_address: "Please provide the Hospital address.",
      contact_no:"Please provide the contact no.",
      contact_person:"Please provide the contact person name",
      floor_space: "Please provide the floor space.",
      maternity_beds: "Please provide no. of beds.",
      patient_beds:"Please provide no. of beds.",
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
        var form_data = new FormData(document.getElementById("hospital-form"));
        $.ajax({
            type: 'POST',
            url: base_url +'hospital/save',
            dataType: "Json",data: form_data,processData:false,contentType:false,
            cache:false,async:false,
            success : response => {
                debugger ; return ;

                if (response.status) {
                    swal("Good Job!",response.message,"success").then(() => location.reload());
                } else {
                    swal("Opps...!!",response.message,"error").then(() => location.reload());
                }
            },
            error: response => {
              debugger ; return ;
                swal("Opps...!!",'Sorry, we have to face some technical issues please try again later.',"error").then(() => location.reload());
            }
        });
    }
});
  
  $(document).on('click','.status_button',function(){
    var hospital_id = $(this).attr('data-hospital');
    var dept_id = $(this).attr('data-dept');
    var app_id = $(this).attr('data-app');
    var status = $(this).attr('data-status');
    var role_id = $(this).attr('data-role');
    var user_id = $(this).attr('data-user');
    $('#status').val(status);
    $('#status').selectpicker('refresh');
    if(status !='') {
        $('#hospital_id').val(hospital_id);
        $('#dept_id').val(dept_id);
        $('#sub_dept_id').val($(this).attr('data-sub-dept'));
        $('#app_id').val(app_id);
        $('#status').selectpicker('refresh');
        $('#status').html('');
        $.ajax({
            type: 'POST',
            url: base_url +'hospital/get_application_status',
            dataType: "Json",
            data:{'dept_id':dept_id,'status':status,'user_id':user_id,'role_id':role_id,'app_id':app_id,'hospital_id':hospital_id},
            success: response => {
                if (response.status) {
                    $('#remarks-form').html(response.html_string);
                    $('#add_remark_model').modal('show');
                } else {
                    swal("Opps...!!",response.message,"error").then(() => location.reload());
                }
            },
            error: response => {
                swal("Opps...!!",'Sorry, we have to face some technical issues please try again later.',"error").then(() => location.reload());
            }
        });
    }
});




  $(document).on('click','.remarks_button',function(){
    var pwd_id = $(this).attr('data-pwd');
    var app_id = $(this).attr('data-app');

    // alert(app_id);
    if(app_id !='') {
      
      $('#status').html('');
      $.ajax({
        type: 'POST',
        url: base_url +'remarks/remarksbyid',
        dataType: "Json",
        data:{'app_id':app_id},
        success: function(res) {
           
            var status = res.status
            var result = res.remarks;
            var tbody ='';
            if(res.status =='1') {
               // console.log(res);
              $.each(result , function(index, val) { 
                // console.log(index);
                tbody +='<tr><td>'+val.id+'</td><td>'+val.remarks+'</td><td>'+val.user_name+'</td><td>'+val.created_at+'</td></tr>';
              });
              
            } else if(res.status =='2'){
              tbody ="";
            } 
            console.log(tbody);
            $('#remarks-body').html(tbody);
        },
    });
    }
  });

    $( "#remarks-form" ).validate({
        rules: {
            remarks: {
                required: true,
            },
            status : {
                required: true,  
            }
        },
        messages: {
            remarks: "Please provide your remarks.",
        },

      errorPlacement: function ( error, element ) {
        console.log(element);
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
        $.ajax({
            type: 'POST',
            url: base_url +'hospital/addremarks',
            dataType: "Json",
            data:$('#remarks-form').serialize(),
            success: function(res) {
                if(res.status =='1') {
                  swal("Good Job!",res.messg,"success")
                  .then((value) => {
                    location.reload()
                  });
                } else if(res.status =='2'){
                  swal("Warning!",res.messg,"warning");
                } 
            },
        });
        // return false;
      }
    });

    $('#ownership_agreement').change(function() {
      var file = $('#ownership_agreement')[0].files[0].name;
      // alert(file);
      $('#ownership_agreement_name').html(file);
      $('#ownership_agreement_name_id').val(file);
    });

    $('#tax_receipt').change(function() {
      var file = $('#tax_receipt')[0].files[0].name;
      $('#tax_receipt_name').text(file);
      $('#tax_receipt_name_id').val(file);
    });

    $('#doc_certificate').change(function() {
    var file = $('#doc_certificate')[0].files[0].name;
    $('#doc_certificate_name').text(file);
    $('#doc_certificate_name_id').val(file);
  });

  $('#reg_certificate').change(function() {
    var file = $('#reg_certificate')[0].files[0].name;
    $('#reg_certificate_name').text(file);
    $('#reg_certificate_name_id').val(file);
  });

  $('#staff_certificate').change(function() {
    var file = $('#staff_certificate')[0].files[0].name;
    $('#staff_certificate_name').text(file);
    $('#staff_certificate_name_id').val(file);
  });

  $('#nursing_staff_deg_certificate').change(function() {
    var file = $('#nursing_staff_deg_certificate')[0].files[0].name;
    $('#nursing_staff_deg_certificate_name').text(file);
    $('#nursing_staff_deg_certificate_name_id').val(file);
  });

  $('#nursing_staff_reg_certificate').change(function() {
    var file = $('#nursing_staff_reg_certificate')[0].files[0].name;
    $('#nursing_staff_reg_certificate_name').text(file);
    $('#nursing_staff_reg_certificate_name_id').val(file);
  });

  $('#bio_des_certificate').change(function() {
    var file = $('#bio_des_certificate')[0].files[0].name;
    $('#bio_des_certificate_name').text(file);
    $('#bio_des_certificate_name_id').val(file);
  });

  $('#society_noc').change(function() {
    var file = $('#society_noc')[0].files[0].name;
    $('#society_noc_name').text(file);
    $('#society_noc_name_id').val(file);
  });

  $('#fire_noc').change(function() {
    var file = $('#fire_noc')[0].files[0].name;
    $('#fire_noc_name').text(file);
    $('#society_noc_name_id').val(file);
  });

});