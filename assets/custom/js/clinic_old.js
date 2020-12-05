

  $(document).on("click",".payment_approvel_btn",event => { 
        event.preventDefault();
        let app_id = event.target.getAttribute('app_id');
        $.ajax({
            type: "POST",
            url: base_url + 'clinic/payment_approvel_modal',
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

  const userListDataTable = () => {
    var hospital = $('#clinic_userapp').DataTable({
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
            url: base_url + '/clinic/get_userClinic',
            type: "POST",
            data: {
                fromDate: $('#fromDate').val(),
                toDate: $('#toDate').val(),
            }
        },
        "bDestroy": true,
    });
}

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
        url: base_url + 'clinic/inspection_form_display',
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

$(document).on("click",".payment_request_btn",event => { event.preventDefault();
    let app_id = event.target.getAttribute('app_id');
    $.ajax({
        type: "POST",
        url: base_url + 'clinic/payment_reqeust_popup',
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


$(document).on('change', '#fromDate,#toDate', userListDataTable());

  $(document).ready(function(){
      var is_user = createClinic.getAttribute("is_user");
      
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
        url: base_url+"clinic/staffDelete",
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

  $( "#clinic-form" ).validate({
    rules: {
      application_no: {
        required: true,
      },
      applicant_name: {
        required: true,
      },
      applicant_email_id: {
        required: true,
        email: true
      },
      applicant_mobile_no: {
        required: true,
        maxlength: 10,
      },
      applicant_address:{
        required: true,
      },
      applicant_qualification: {
        required: true,
      },
      clinic_name: {
        required: true,
      },
      contact_person: {
        required: true,
      },
      contact_no: {
        required: true,
      },
      clinic_address: {
        required: true,
      },
      immunization_details: {
         required: true,
      },
      bio_waste_valid: {
         required: true,
      }
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
      applicant_qualification: "Please provide the applicant qualifiction.",
      clinic_name: "Please provide clinic name.",
      clinic_address: "Please provide the clinic address.",
      contact_no:"Please provide the contact no.",
      contact_person:"Please provide the contact person name",
      bio_waste_valid: "Please provide the bio waste validity date.",
      immunization_details : "Please provide immunization details."
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
      var form_data = new FormData(document.getElementById("clinic-form"));
      $.ajax({
        type: 'POST',
        url: base_url +'clinic/save',
        dataType: "Json",
        data: form_data,
        processData:false,
        contentType:false,
        cache:false,
        async:false,
        success: function(res) {
          if(res.status =='1') {
            swal("Good Job!",res.messg,"success")
            .then((value) => {
                if(is_user == '1'){
                    location.reload();
                }else{
                    window.location = base_url + 'clinic';      
                }
              
            });
          } else if(res.status =='2'){
            swal("Warning!",res.messg,"warning");
          } 
        },
      });
      return false;
    }
  });

   
  
$(document).on('click','.status_button',function(){
    var clinic_id = $(this).attr('data-clinic');
    var dept_id = $(this).attr('data-dept');
    var app_id = $(this).attr('data-app');
    var status = $(this).attr('data-status');
    var role_id = $(this).attr('data-role');
    var user_id = $(this).attr('data-user');
    $('#status').val(status);
    $('#status').selectpicker('refresh');
    if(status != '') {
        $('#clinic_id').val(clinic_id);
        $('#dept_id').val(dept_id);
        $('#sub_dept_id').val($(this).attr("data-sub-dept"));
        $('#app_id').val(app_id);
        $('#role_id').val(role_id);
        $('#status').selectpicker('refresh');
        $('#status').html('');
        $.ajax({
            type: 'POST',
            url: base_url +'clinic/get_application_status',
            dataType: "Json",
            data:{'dept_id':dept_id,'status':status,'user_id':user_id,'role_id':role_id,'app_id':app_id,'clinic_id':clinic_id},
            success: response => {
                if (response.status) {
                    $('#remarks-form').html(response.html_string);
                    $('#add_remark_model').modal('show');
                } else {
                    swal("Opps...!!",response.message,"error").then(() => location.reload());
                }
            },
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
            status: {
                required: true,
            },
            health_officers:{
                required: true,
            },
            remarks:{
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
            url: base_url +'clinic/addremarks',
            dataType: "Json",
            data:$('#remarks-form').serialize(),
            success: function(res) {
                $('#modal-status').modal('toggle');
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
      console.log(file);
      $('#doc_certificate_name').text(file);
      $('#doc_certificate_name_id').val(file);
    });

    $('#bio_medical_certificate').change(function() {

      var file = $('#bio_medical_certificate')[0].files[0].name;
      console.log(file);
      $('#bio_medical_certificate_name').text(file);
      $('#bio_medical_certificate_name_id').val(file);
    });

  $('#aadhaar_card').change(function() {
    var file = $('#aadhaar_card')[0].files[0].name;
    $('#aadhaar_card_name').text(file);
    $('#aadhaar_card_id').val(file);
  });

});