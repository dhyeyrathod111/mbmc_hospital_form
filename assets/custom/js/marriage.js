  $(document).ready(function(){
  $('#husband_aadhaar_card').change(function() {
    $('#husband_aadhaar_card_name').text($('#husband_aadhaar_card')[0].files[0].name);
    $('#husband_aadhaar_card_name_id').val($('#husband_aadhaar_card')[0].files[0].name);  
  });

  $('#wife_aadhaar_card').change(function() {
    var file = $('#wife_aadhaar_card')[0].files[0].name;
    $('#wife_aadhaar_card_name').text(file);
    $('#wife_aadhaar_card_id').val(file);  
  });

  $('#first_witness_id_proof').change(function() {
    var file = $('#first_witness_id_proof')[0].files[0].name;
    $('#first_witness_id_proof_name').text(file);
    $('#first_witness_id_proof_id').val(file);  
  });

  $('#second_witness_id_proof').change(function() {
    var file = $('#second_witness_id_proof')[0].files[0].name;
    $('#second_witness_id_proof_name').text(file);
    $('#second_witness_id_proof_name_id').val(file);
  });

  $('#third_witness_id_proof').change(function() {
    var file = $('#third_witness_id_proof')[0].files[0].name;
    $('#third_witness_id_proof_name').text(file);
    $('#third_witness_id_proof_name_id').val(file);
  });

  $('#bill').change(function() {
    var file = $('#bill')[0].files[0].name;
    $('#bill_name').text(file);
    $('#bill_id').val(file);
  });

  $('#lagan_patrika').change(function() {
    var file = $('#lagan_patrika')[0].files[0].name;
    $('#lagan_patrika_name').text(file);
    $('#lagan_patrika_name_id').val(file);
  });

  $('#ration_card').change(function() {
    var file = $('#ration_card')[0].files[0].name;
    $('#ration_card_name').text(file);
    $('#ration_card_name').val(file);
  });

  

  $("#marriage_date").datepicker({
    defaultDate: new Date(),      // Just for this demo longevity on SO ;)
    dateFormat: 'yy-mm-dd',
    minDate: new Date(),
  });

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
  
  $( "#marriage-form" ).validate({
    rules: {
      application_no: {
        required: true,
        maxlength: 25,
      },
      
      marriage_date: {
        required: true,
        maxlength: 25,
      },
      husband_name: {
        required: true,
        maxlength: 25,
      },
      husband_age :{ 
        required:true,
        maxlength: 100,
        number: true,
      },
      husband_religious: {
        required:true,
        maxlength: 20,
      },
      husband_marriage_status:{
        required : true,
        maxlength: 100,
      },
      husband_address:{
        required:true,
        maxlength: 200,
      },
      wife_name: {
        required: true,
        maxlength: 25,
      },
      wife_age :{ 
        maxlength: 100,
        required:true,
        number: true,
      },
      wife_religious: {
        maxlength: 100,
        required:true,
      },
      wife_marriage_status:{
        maxlength: 25,
        required : true
      },
      wife_address:{
        maxlength: 200,
        required:true
      },
      priest_name: {
        maxlength: 25,
        required: true,
      },
      priest_age :{ 
        maxlength: 100,
        required:true,
        number: true,
      },
      priest_religious: {
        maxlength: 25,
        required:true,
      },
      priest_address:{
        maxlength: 200,
        required:true
      },
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
      var form_data = new FormData(document.getElementById("marriage-form"));
      $.ajax({
          type: 'POST',
          url: base_url +'marriage/save',
          dataType: "Json",
          data: form_data,
          processData:false,
          contentType:false,
          cache:false,
          async:false,
          success: response => {
            if (response.status == true) {
              swal("Good job!", response.message, "success");
              $('#submit_btn').text('Please wait...!!').attr('disabled',true);
              setTimeout(function(){ 
                  
                  if(is_user == '1'){
                      location.reload();
                  }else{
                    window.location.href = base_url + 'marriage';   
                  }
                  
              }, 1000);
            } else {
              if (response.image_validation_message) {
                swal("Opps...!", response.image_validation_message, "error");
              } else {
                swal("Opps...!", response.message, "error");
              }
            }
          },
          error: response =>{
            swal("Opps...!", "Sorry, we have to face some technical issues please try again later." , "error");
          }
      });
      return false;
    }
  });

  $( "#marriage-form-edit" ).validate({
    rules: {
      application_no: {
        required: true,
        maxlength: 25,
      },
      
      marriage_date: {
        required: true,
        maxlength: 25,
      },
      husband_name: {
        required: true,
        maxlength: 25,
      },
      husband_age :{ 
        required:true,
        maxlength: 100,
        number: true,
      },
      husband_religious: {
        required:true,
        maxlength: 20,
      },
      husband_marriage_status:{
        required : true,
        maxlength: 100,
      },
      husband_address:{
        required:true,
        maxlength: 200,
      },
      wife_name: {
        required: true,
        maxlength: 25,
      },
      wife_age :{ 
        maxlength: 100,
        required:true,
        number: true,
      },
      wife_religious: {
        maxlength: 100,
        required:true,
      },
      wife_marriage_status:{
        maxlength: 25,
        required : true
      },
      wife_address:{
        maxlength: 200,
        required:true
      },
      priest_name: {
        maxlength: 25,
        required: true,
      },
      priest_age :{ 
        maxlength: 100,
        required:true,
        number: true,
      },
      priest_religious: {
        maxlength: 25,
        required:true,
      },
      priest_address:{
        maxlength: 200,
        required:true
      },
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
      $('#submit_btn').prop('disabled', true);
      e.preventDefault();
      var form_data = new FormData(document.getElementById("marriage-form-edit"));
      $.ajax({
          type: 'post',
          url: base_url + 'edit_form_process',
          data: form_data,
          processData:false,
          contentType:false,
          cache:false,
          async:false,
          success: response => {
            if (response.status == true) {
              swal("Good job!", response.message, "success");
              $('#submit_btn').text('Please wait...!!').attr('disabled',true);
              setTimeout(function(){ window.location.href = base_url + 'marriage'; }, 1000);
            } else {
              if (response.image_validation_message) {
                swal("Opps...!", response.image_validation_message, "error");
              } else {
                swal("Opps...!", response.message, "error");
              }
            }
            $('#submit_btn').prop('disabled', true);
          },
          error: response => {
            swal("Opps...!", "Sorry, we have to face some technical issues please try again later." , "error");
          }
      });
      return false;
    }
  });

  
  $(document).on('click','.status_button',function(){
    var hall_id = $(this).attr('data-hall');
    var dept_id = $(this).attr('data-dept');
    var app_id = $(this).attr('data-app');
    var status = $(this).attr('data-status');
    var role_id = $(this).attr('data-role');
    var user_id = $(this).attr('data-user');
    $('#status').val(status);
    $('#status').selectpicker('refresh');

    // alert(status);
    if(status !='') {
      $('#hall_id').val(hall_id);
      $('#dept_id').val(dept_id);
      $('#app_id').val(app_id);
      $('#status').selectpicker('refresh');
      $('#status').html('');
      $.ajax({
        type: 'POST',
        url: base_url +'status/getStatusByDeptRole',
        dataType: "Json",
        data:{'dept_id':dept_id,'status':status,'user_id':user_id,'role_id':role_id},
        success: function(res) {
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
    var pwd_id = $(this).attr('data-hall');
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
          url: base_url +'marriage/addremarks',
          dataType: "Json",
          data:$('#remarks-form').serialize(),
          success: function(res) {
            console.log(res.status);
            $('#modal-status').modal('toggle');
            if(res.status =='1') {
              swal("Good Job!",res.messg,"success")
              .then((value) => {
                window.location = base_url + 'marriage';
              });
            } else if(res.status =='2'){
              swal("Warning!",res.messg,"warning");
            } 
          },
        });
        // return false;
      }
    });
});

