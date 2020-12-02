  $(document).ready(function(){
      
      var is_user = createMandap.getAttribute("is_user");

    $('#id_proof').change(function() {
      var file = $('#id_proof')[0].files[0].name;
      // alert(file);
      $('#id_proof_name').text(file);
      $('#id_proof_name_id').val(file);  

    });

    $('#police_noc').change(function() {
      var file = $('#police_noc')[0].files[0].name;
      // alert(file);
      $('#police_noc_name').text(file);
      $('#police_noc_name_id').val(file);  

    });

    $('#request_letter').change(function() {
      var file = $('#request_letter')[0].files[0].name;
      $('#request_letter_name').text(file);
      $('#request_letter_name_id').val(file);
    });

  var currentdate = new Date();
  $("#booking_date").datepicker({
    defaultDate: currentdate,      // Just for this demo longevity on SO ;)
    dateFormat: 'yy-mm-dd',
    minDate: new Date(),
  });


  $( "#mandap-form" ).validate({
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
      booking_date: {
        required: true,
      },
      booking_address: {
        required: true,
      },
      reason: {
        required: true,
      },
      mandap_size: {
        required: true,
      }
    },
    messages: {
      applicant_name: "Please provide user name.",
      applicant_email_id: "Please provide email id.",
      applicant__mobile_no: {
        required: "Please provide mobile no.",
        maxlength: "Your mobile no. must be of 10 digits."
      },
      applicant_address: "Please provide address.",
      booking_place: "please provide mandap place.",
      mandap_size: "please provide mandap size.",
      booking_date : "please select a date of booking.",
      reason : "please provide booking reason.",
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
      var form_data = new FormData(document.getElementById("mandap-form"));
      $.ajax({
          type: 'POST',
          url: base_url +'mandap/save',
          dataType: "Json",
          data: form_data,
          processData:false,
          contentType:false,
          cache:false,
          async:false,
          success: function(res) {
            console.log(res.status);
            if(res.status =='1') {
              swal("Good Job!",res.messg,"success")
              .then((value) => {
                  if(is_user == '1'){
                      location.reload();
                  }else{
                    window.location = base_url + 'mandap';      
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
    var hall_id = $(this).attr('data-mandap');
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
    var mandap_id = $(this).attr('data-madap');
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
          url: base_url +'mandap/addremarks',
          dataType: "Json",
          data:$('#remarks-form').serialize(),
          success: function(res) {
            console.log(res.status);
            $('#modal-status').modal('toggle');
            if(res.status =='1') {
              swal("Good Job!",res.messg,"success")
              .then((value) => {
                window.location = base_url + 'mandap';
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

