$( "#sku-form" ).validate({
   rules: {
    sku_title: {
      required: true,
    },
    dept_id: {
      required: true,
    } 
  },
  messages: {
    sku_title: "Please provide Sku Title.",
    dept_id: "Please choose department.",
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
      var form_data = new FormData(document.getElementById("sku-form"));
      $.ajax({
          type: 'POST',
          url: base_url +'sku/save',
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
                window.location = base_url + 'sku';
              });
            } else if(res.status =='2'){
              swal("Warning!",res.messg,"warning");
            } 
          },
      });
      return false;
    }
  });