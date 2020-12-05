$(document).ready(function(){
    
    var is_user = createLab.getAttribute("is_user");
        
  function CapitalizeWord(str)
  {
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
  }

  $('#applicant_name, #applicant_qualification, #lab_name, #contact_person, .staff_name').keyup(function(){
     var text =  $(this).val();
     text = CapitalizeWord(text);
     $(this).val(text);
  });    
    
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
        designation = "<select class='selectpicker form-control' id='designation' name='designation[]' data-live-search='true' required=''>"+option+"</select>";
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
        qualification = "<select class='selectpicker form-control' id='qualification' name='qualification[]' data-live-search='true' required=''>"+option+"</select>";
      }
    });
    return qualification;
  }//End Function


  $(".addRow").click(function(){
    var row = "<tr>\n\
                <td class = 'text-center name'>\n\
                  <input type='text' class='form-control staff_name' name='staff_name[]'' id='staff_name' placeholder='Enter Staff Name' required=''>\n\
                </td>\n\
                <td class = 'text-center activity'>"+getDesignation()+"</td>\n\
                <td class = 'text-center noOfTrees'>\n\
                  <input type = 'number' class='form-control' name = 'age[]' id = 'age[]' placeholder='Enter Age' required=''>\n\
                </td>\n\
                <td class = 'text-center name'>"+getQualification()+"</td>\n\
                <td class = 'text-center action'>\n\
                  <span style = 'cursor:pointer' class = 'delete deleteRow' data-id = '2'><i class='fas fa-trash'></i></span>\n\
                </td></tr>";
    
    $(".tableBody").append(row);

    $('.selectpicker').selectpicker('refresh');
    
    $(".staff_name").keyup(function(){
      var text =  $(this).val();
      text = CapitalizeWord(text);
      $(this).val(text);
    })
    
  });
  
    $(document).on("click", ".deleteRow", function(){
        $(this).parent().parent().remove();
    })    

  $(".tableBody").on('click', '.delete',function(){
    obj = $(this);
    var staff_id = $(this).attr('data-staff-id');
    // console.log($(this).parent().parent().remove());
    // return false;
    if( staff_id != '0'){

      $.ajax({
        url: base_url+"lab/staffDelete",
        type: "POST",
        dataType: "json",
        data: {'staff_id': staff_id},
        async: false,
        success: function(res){
            console.log(res);
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
  
  jQuery.validator.addMethod("lettersonly", function(value, element) {
    return this.optional(element) || /^[a-z\s]+$/i.test(value);
   }, "Letters only please");

  $( "#lab-form" ).validate({
    rules: {
      application_no: {
        required: true,
      },
      applicant_name: {
        required: true,
        lettersonly: true
      },
      applicant_email_id: {
        required: true,
        email: true
      },
      applicant_mobile_no: {
        required: true,
        minlength:9,
        maxlength: 10,
        number: true
      },
      applicant_address:{
        required: true,
      },
      applicant_qualification: {
        required: true,
        lettersonly: true
      },
      lab_name: {
        required: true,
        lettersonly: true
      },
      contact_person: {
        required: true,
        lettersonly: true
      },
      contact_no: {
        required: true,
        minlength: 9,
        maxlength: 10,
        number: true
      },
      lab_address: {
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
        required: "Please provide mobile number",
        maxlength: "Your mobile number must be of 10 digits."
      },
      applicant_address: "Please provide the address.",
      applicant_nationality: "Please provide the nationality.",
      applicant_qualification: "Please provide the applicant qualification.",
      lab_name: "Please provide lab name.",
      lab_address: "Please provide the lab address.",
      contact_no:"Please provide the contact no.",
      contact_person:"Please provide the contact person name",
      bio_waste_valid: "Please provide bio waste validity date.",
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
      var form_data = new FormData(document.getElementById("lab-form"));
      $.ajax({
        type: 'POST',
        url: base_url +'lab/save',
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
                    window.location = base_url + 'lab';      
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
    var lab_id = $(this).attr('data-lab');
    var dept_id = $(this).attr('data-dept');
    var app_id = $(this).attr('data-app');
    var status = $(this).attr('data-status');
    var role_id = $(this).attr('data-role');
    var user_id = $(this).attr('data-user');
    $('#status').val(status);
    $('#status').selectpicker('refresh');

    // alert(status);
    if(status !='') {
      $('#lab_id').val(lab_id);
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
            url: base_url +'lab/addremarks',
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