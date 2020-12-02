$( "#unit-form" ).validate({
   rules: {
    unit_value: {
      required: true,
    },
    unit_label: {
      required : true,
    }, 
    // unit_cost: {
    //   required : true,
    // } 
  },
  messages: {
    unit_value: "Please provide Unit Value.",
    unit_label: "Please provide Unit Label.",
    // unit_cost: "Please provide Unit Cost."
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
      var form_data = new FormData(document.getElementById("unit-form"));
      $.ajax({
          type: 'POST',
          url: base_url +'unit/save',
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
                window.location = base_url + 'unit';
              });
            } else if(res.status =='2'){
              swal("Warning!",res.messg,"warning");
            } 
          },
      });
      return false;
    }
  });