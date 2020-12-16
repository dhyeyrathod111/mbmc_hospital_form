  
    const load_user_apps_datatable = props => {
        var dataTable = $('#mandap_userapps_table').DataTable({
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
                url: base_url + 'mandap/datatable_userapplist',
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

    $(document).on('change', '#mandap_type', event => { event.preventDefault();
        if ($.inArray(event.target.value,['1','2']) == 1) {
            $('.mandapsizecontainer').show();$('.noofgatecontainer').hide();
        } else {
            $('.noofgatecontainer').show();$('.mandapsizecontainer').hide();
        }
    });

      
    
    $(document).on("change","#no_of_days",event => { event.preventDefault();
        if (event.target.value > 1) {
            $('.multydate_event_container').show();
        } else {
            $('.multydate_event_container').hide();
        }
    });

    $(document).on("click",".payment_request_btn",event => { event.preventDefault();
        let app_id = event.target.getAttribute('app_id');
        $.ajax({
            type: "POST",
            url: base_url + 'mandap/payment_reqeust_popup',
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
                console.log(response.responseText);swal("Opps...!!","Sorry, we have to face some technical issues please try again later.","error");
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

    $(document).on("click",".payment_approvel_btn",event => { 
        event.preventDefault();
        let app_id = event.target.getAttribute('app_id');
        $.ajax({
            type: "POST",
            url: base_url + 'mandap/payment_approvel_modal',
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



    


  $(document).ready(function(){
      
    var is_user = createMandap.getAttribute("is_user");

    $('#id_proof').change(function() {
        var file = $('#id_proof')[0].files[0].name;
        $('#id_proof_name').text(file);
        $('#id_proof_name_id').val(file);
    });

    $('#police_noc').change(function() {
        var file = $('#police_noc')[0].files[0].name;
        $('#police_noc_name').text(file);
        $('#police_noc_name_id').val(file);
    });

    $('#traffic_police_noc').change(function() {
        var file = $('#traffic_police_noc')[0].files[0].name;
        $('#traffic_police_noc_name').text(file);
        $('#traffic_police_noc_id').val(file);
    });

  var currentdate = new Date();
  $("#booking_date").datepicker({
    defaultDate: currentdate,      // Just for this demo longevity on SO ;)
    dateFormat: 'yy-mm-dd',
    minDate: new Date(),
  });


  $( "#mandap-form" ).validate({
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
      // booking_date: {
      //   required: true,
      // },
      // booking_address: {
      //   required: true,
      // },
      // reason: {
      //   required: true,
      // },
      // mandap_size: {
      //   required: true,
      // }
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
        var form_data = new FormData(document.getElementById("mandap-form"));
        $('#mandap_app_submit_btn').text('Processing....').prop("disabled",true);
        $.ajax({
            type: 'POST',url: base_url +'mandap/save',
            dataType: "Json",data: form_data,processData:false,contentType:false,cache:false,async:false,
            success : response => {
                $('#mandap_app_submit_btn').text('Submit').prop("disabled",false);
                if (response.status == true) {
                    swal("Good Job!",response.message,"success").then(() => location.reload());
                } else {
                    swal("Opps...!!",response.message,"error").then(() => location.reload());
                }
            },
            error : response => {
                console.log(response.responseText);
                toster_alert_error("Sorry, we have to face some technical issues please try again later.");
                $('#mandap_app_submit_btn').text('Submit').prop("disabled",false);
            }
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
        url: base_url +'mandap/get_application_status',
        data:{'dept_id':dept_id,'status':status,'user_id':user_id,'role_id':role_id,app_id:app_id},
        success : response => {
            if (response.status == true) {
                $('#remarks-form').html(response.html_string);$('#add_remark_model').modal('show');
            } else {
                swal("Opps...!!",response.message,"error");
            }
        },
        error: response => {
            console.log(response.responseText);
            swal("Opps...!!",'Sorry, we have to face some technical issues please try again later.',"error").then(() => location.reload());
        }
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
        $('#remark_submit').text('Processing....').prop("disabled",true);
        $.ajax({
            type: 'POST',
            url: base_url +'mandap/addremarks',
            data:$('#remarks-form').serialize(),
                success: response => {
                    $('#remark_submit').text('Save changes').prop("disabled",false);
                    if (response.status == '1') {
                        notify_success(response.messg);
                        setTimeout(function(){ $('#add_remark_model').modal('hide'); location.reload() }, 3000);
                    } else {
                        notify_error(response.messg);
                    }
                },
                error : response => {
                    notify_error(response.messg);
                    console.log(response.responseText);
                }
            });
        }
    });
});

