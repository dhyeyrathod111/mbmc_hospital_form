$(document).ready(function(){
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
      // letter_no: "required",
      applicant_nationality: {
        required: true,
      },
      technical_qualification: {
        required: true,
      },
      hospital_name: {
        required: true,
      },
      contact_person: {
        required: true,
      },
      contact_no: {
        required: true,
      },
      hospital_address: {
        required: true,
      },
      floor_space: {
        required: true,
      },
      maternity_beds: {
        required: true,
      },
      patient_beds: {
        required: true,
      },
      // ownership_agreement: {
      //   required: true,
      // },
      // tax_receipt: {
      //   required: true,
      // },
      // doc_certificate: {
      //   required: true,
      // },
      // reg_certificate: {
      //   required: true,
      // },
      // staff_certificate: {
      //   required: true,
      // },
      // nursing_staff_deg_certificate: {
      //   required: true,
      // },
      // nursing_staff_reg_certificate: {
      //   required: true,
      // },
      // bio_des_certificate: {
      //   required: true,
      // },
      // society_noc: {
      //   required: true,
      // },
      // fire_noc_name: {
      //   required: true,
      // }

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
      console.log('Form submitted');
      var form_data = new FormData(document.getElementById("hospital-form"));
      $.ajax({
        type: 'POST',
        url: base_url +'hospital/save',
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
                    window.location = base_url + 'hospital';      
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
    var hospital_id = $(this).attr('data-hospital');
    var dept_id = $(this).attr('data-dept');
    var app_id = $(this).attr('data-app');
    var status = $(this).attr('data-status');
    var role_id = $(this).attr('data-role');
    var user_id = $(this).attr('data-user');
    $('#status').val(status);
    $('#status').selectpicker('refresh');

    // alert(status);
    if(status !='') {
      $('#hospital_id').val(hospital_id);
      $('#dept_id').val(dept_id);
      $('#sub_dept_id').val($(this).attr('data-sub-dept'));
      $('#app_id').val(app_id);
      $('#status').selectpicker('refresh');
      $('#status').html('');
      $.ajax({
        type: 'POST',
        url: base_url +'status/getStatusByDeptRole',
        dataType: "Json",
        data:{'dept_id':dept_id,'status':status,'user_id':user_id,'role_id':role_id},
        success: function(res) {
            // console.log(res);
            var status = res.status
            var result = res.result;
            var option ='<option value="">---Select Status---</option>';
            if(res.status =='1') {

              $.each(result , function(index, val) { 
                option +="<option value='"+val.status_id+"'>"+val.status_title+"</option>";
                // console.log(val.status_id)
              });
              
            } else if(res.status =='2'){
              option ="";
            } 
            $('#status').html(option);
            $('#status').selectpicker('refresh');
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
        remarks: {
            required: true,
        },
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
            url: base_url +'hospital/addremarks',
            dataType: "Json",
            data:$('#remarks-form').serialize(),
            success: function(res) {
                console.log(res.status);
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