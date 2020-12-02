$(document).ready(function(){
	$(document).on("click", ".payment", function(){
		var id = $(this).data("id");
		
		$(document).find("#rowId").val(id);
		$('#modal-payment').modal('show');
	});

	$(document).on("submit", "#paymentForm", function(e){
		e.preventDefault();

		var rowId = $(this).find("#rowId").val();
		var paymentSelect = $(this).find("#paymentType").val();
		var amount = $(this).find("#amount").val();
		if(rowId != '' && paymentSelect != '' && amount != ''){
			$.ajax({
				url: base_url+"reports/payment",
				type: "POST",
			    dataType: "json",
			    data: {'rowId': rowId, 'paymentSelect': paymentSelect, 'amount': amount},
			    async: true,
			    success: function(res){
			    	console.log(res);
			    	if(res.success == '1'){
						swal("Success!", res.message, "success").then((willactive) => {
		                  location.reload();
		                });
			    	}else{
			    		sweet_alert("Warning!",res.message,"warning");
			    	}
			    }
			});
		}else{
			sweet_alert("Warning!","Please Enter Proper Value","warning");
		}
	});
});