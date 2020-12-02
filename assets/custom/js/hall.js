  $(document).ready(function(){
      
      var is_user = createHall.getAttribute("is_user");
      
  $('#id_proof').change(function() {
    var file = $('#id_proof')[0].files[0].name;
    // alert(file);
    $('#id_proof_name').text(file);
    $('#id_proof_name_id').val(file);  
  });

  $('#address_proof').change(function() {
    var file = $('#address_proof')[0].files[0].name;
    $('#address_proof_name').text(file);
    $('#address_proof_name_id').val(file);
  });


  $('.asset_used_unit').blur(function() {
    // alert('hih');
    unit = $(this).val();
    asset_id = $(this).attr('data-id');

    asset_unit_cost = $('#asset_unit_cost_'+ asset_id).val();
    used_cost = unit * asset_unit_cost;

    cost = $('#cost_'+ asset_id).val();
    final_amount = parseInt(cost) + parseInt(used_cost);

    $('#cost_'+ asset_id).val(final_amount);
  });

  $('.defected_services').blur(function() {
    // alert('hihi');
    defected_services = $(this).val();
    asset_id = $(this).attr('data-id');
    
    penalty_charges = $('#penalty_charges_'+ asset_id).val();
    console.log(penalty_charges);
    penalty_cost = defected_services * penalty_charges;

    $('#penalty_cost_'+asset_id).val(penalty_cost);

    cost = $('#cost_'+ asset_id).val();

    final_amount = parseInt(cost) + parseInt(penalty_cost);

    $('#cost_'+ asset_id).val(final_amount);
  });

  $( "#hall-asset-form" ).validate({
    rules: {
      asset_unit_cost: {
        required: true,
      },
      cost: {
        required: true,
      }
      // id_proof: {
      //   required: true,
      // },
      // address_proof: {
      //   required: true,
      // },

    },
    messages: {
      asset_unit_cost: "Please provide user name.",
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
      var form_data = new FormData(document.getElementById("hall-asset-form"));
      $.ajax({
          type: 'POST',
          url: base_url +'hall/asset-save',
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
                window.location = base_url + 'hall';
              });
            } else if(res.status =='2'){
              swal("Warning!",res.messg,"warning");
            } 
          },
      });
      return false;
    }
  });


  var booked = [];
  
  $('#sku_price_id').change(function(){
    $.ajax({
      type: 'POST',
      url: base_url +'price/getprice',
      dataType: "Json",
      data: {'sku_id':$(this).val()},
      success: function(res) {
        console.log(res.status);
          if(res.status =='1') {
            // option = "";
            result = res.result;
            $('#amount').val(result.amount);
            $('#sku_price_id').selectpicker('refresh');
          } 
      },
    });

    $.ajax({
      type: 'POST',
      url: base_url +'hall/getbookedhall',
      dataType: "Json",
      data: {'sku_price_id':$('#sku_price_id').val()},
      success: function(res) {
        console.log(res.status);
          if(res.status =='1') {
            booked = res.date;
          }  else {
            booked = res.date;;
          }
      },
    });
  });

  console.log(booked);
  var currentdate = new Date();
  var currentMonth = currentdate.getMonth();
  var currentDate = currentdate.getDate();
  var currentYear = currentdate.getFullYear();
  $("#booking_date").datepicker({
    defaultDate: currentdate,      // Just for this demo longevity on SO ;)
    dateFormat: 'yy-mm-dd',
    minDate: new Date(),
    maxDate: new Date(currentYear, currentMonth+1, currentDate),
    beforeShowDay: function(date){
      var dateISO = $.datepicker.formatDate('yy-mm-dd', date);
      if(booked.indexOf(dateISO)>-1){
        for (var i = 0; i < booked.length; i++) {
            return [true,"red",'No slot available'];
        }
           // Enabled, class
      } else {
        return [true]; 
      }
    }
  });

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
  
 jQuery.validator.addMethod("lettersonly", function(value, element) {
      return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "Letters only please");    
    
  $( "#hall-form" ).validate({
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
        maxlength:10,
        number: true
      },
      applicant_address:{
        required: true,
      },
      sku_price_id: {
        required: true,
      },
      amount: {
        required: true,
      },

      booking_date: {
        required: true,
      },
      reason: {
        required: true,
      },
      // id_proof: {
      //   required: true,
      // },
      // address_proof: {
      //   required: true,
      // },

    },
    messages: {
      applicant_name: "Please provide user name.",
      applicant_email_id: "Please provide email id.",
      applicant__mobile_no: {
        required: "Please provide mobile no.",
        maxlength: "Your mobile no. must be of 10 digits."
      },
      applicant_address: "Please provide the address.",
      // id_proof: "Please choose id proof.",
      // address_proof: "please choose the address proof.",
      sku_price_id: "please choose hall.",
      amount: "please enter amount.",
      booking_date : "please select a date of booking.",
      reason: "Please provide reason of booking."

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
      var form_data = new FormData(document.getElementById("hall-form"));
      $.ajax({
          type: 'POST',
          url: base_url +'hall/save',
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
                    window.location = base_url + 'hall';      
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
          url: base_url +'hall/addremarks',
          dataType: "Json",
          data:$('#remarks-form').serialize(),
          success: function(res) {
            console.log(res.status);
            $('#modal-status').modal('toggle');
            if(res.status =='1') {
              swal("Good Job!",res.messg,"success")
              .then((value) => {
                window.location = base_url + 'hall';
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

