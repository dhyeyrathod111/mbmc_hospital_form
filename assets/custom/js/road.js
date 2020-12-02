jQuery.validator.addMethod("lettersonly", function(value, element) {
  return this.optional(element) || /^[a-z\s.(/]+$/i.test(value);
}, "Letters only please");

$( "#road-form" ).validate({
  rules: {
    
      road_title: {
          required: true,
        //   lettersonly: true
      },
  },
  messages: {
    road_title: "Please provide road title.",
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
        url: base_url +'road/save',
        dataType: "Json",
        data:$('#road-form').serialize(),
        success: function(res) {
          console.log(res.status);
          if(res.status =='1') {
            swal("Good Job!",res.messg,"success")
            .then((value) => {
              window.location = base_url + 'road';
            });
          } else if(res.status =='2'){
            swal("Warning!",res.messg,"warning");
          } 
        },
    });
    // return false;
  }
});