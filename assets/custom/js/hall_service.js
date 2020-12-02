// $('#dept_id').change(function(){
//   $.ajax({
//     type: 'POST',
//     url: base_url +'hall-service/getsku',
//     dataType: "Json",
//     data: {'dept_id':$('#dept_id').val()},
//     success: function(res) {
//       console.log(res.status);
//         if(res.status =='1') {
//           option = "";
//           result = res.result;
//           $(result).each(function(index,val){
//             // console.log(index.sku_title);
//             option +="<option value='"+val.sku_id+"'>"+val.sku_title+"</option>";
//           });
//           $('#sku_id').append(option);
//           $('#sku_id').selectpicker('refresh');
//         } 
//     },
//   });
// });

// $('#unit_id').change(function(){
//   $.ajax({
//     type: 'POST',
//     url: base_url +'unit/getunit',
//     dataType: "Json",
//     data: {'unit_id':$(this).val()},
//     success: function(res) {
//       console.log(res.status);
//       if(res.status =='1') {
//        $('#amount').val(res.amount);
//       } 
//     },
//   });
// });
 
$( "#hall-service-form" ).validate({
   rules: {
    sku_id: {
      required: true,
    },
    asset_name: {
      required: true,
    },
    asset_unit_id: {
      required: true,
    },
    penalty_charges: {
      required: true,
    }, 
  },
  messages: {
    sku_id: "Please provide sku title.",
    asset_name: "Please provide service title.",
    asset_unit_id : "Please select the unit.",
    penalty_charges:"Please provide the penalty charges."
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
      var form_data = new FormData(document.getElementById("hall-service-form"));
      $.ajax({
        type: 'POST',
        url: base_url +'hall-service/save',
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
              window.location = base_url + 'hall-service';
            });
          } else if(res.status =='2'){
            swal("Warning!",res.messg,"warning");
          } 
        },
      });
      return false;
    }
  });