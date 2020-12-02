$( "#role-form" ).validate({
    rules: {
      
        role_title: {
            required: true,
        },
        date_from: {
          required: true,
        },
        date_till: {
          required: true,
        }
    },
    messages: {
      role_title: "Please provide role title.",
      date_from: "Please Select Date From",
      date_till: "Please Select To data",
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
      // console.log('Form submitted');

      var fromDate = $(document).find("#date_from").val();

	    var toDate = $(document).find("#date_till").val();

      if(fromDate > toDate){
        subStatus = false;
        $(document).find("#date_from").parent().append('<label id="error" class="error ui red pointing label transition">From Cannot Be Greater Than To.</label>').css({'color':'red', 'font-weight':'400 !important'});
        setTimeout(function(){
              $(document).find('#error').remove();
            }, 2000);
        return;
      }

      swal({
        title: "Are you sure want to submit?",
        text: "",
        icon: "warning",
        buttons: true,
        dangerMode: true
      }).then((willActive) => {
        $.ajax({
          type: 'POST',
          url: base_url +'role/save',
          dataType: "Json",
          data:$('#role-form').serialize(),
          success: function(res) {
              console.log(res.status);

              $('#modal-add').modal('toggle');
              if(res.status =='1') {
                  // $('.alert-success').show();
                  // $('#alert-success').html(res.messg);
                  swal("Good Job!",res.messg,"success")
                  .then((value) => {
                    window.location = base_url + 'roles';
                  });
                  // setInterval( function () {
                  //   window.location = base_url + 'roles';
                  // }, 5000 );
                // role_table.draw();
              } else if(res.status =='2'){
                  swal("Warning!", res.messg, "warning");
              } 
            },
        });
      })
      // return false;
    }
  });
