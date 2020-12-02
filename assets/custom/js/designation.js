$("#designation-form" ).validate({
  rules: {
      design_title: {
          required: true,
      },
  },
  messages: {
    design_title: "Please provide designation title.",
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
        url: base_url +'designation/save',
        dataType: "Json",
        data:$('#designation-form').serialize(),
        success: function(res) {
            console.log(res.status);
          if(res.status =='1') {
            swal("Good Job!",res.messg,"success")
            .then((value) => {
              window.location = base_url + 'designation';
            });
          } else if(res.status =='2'){
            swal("Warning!",res.messg,"warning");
          }  
        },
    });
    // return false;
  }
});