$(document).ready(function () {
	jQuery.validator.addMethod("lettersonly", function (value, element) {
		return this.optional(element) || /^[a-z\s]+$/i.test(value);
	}, "Letters only please");
	//form submit 
	$("#company_details_form").validate({
		rules: {
			company_name: {
				required: true,
				lettersonly: true
			},
			company_address: {
				required: true,
			}
		},
		messages: {
			company_name: "Please Enter Company Name",
			company_address: "Please Enter Company Address"
		},
		errorPlacement: function (error, placement) {
			error.addClass("ui red pointing label transition");
			error.insertAfter(element.after());
		},
		invalidHandler: function (event, validator) {
			// 'this' refers to the form
			var errors = validator.numberOfInvalids();
			// console.log(errors);
			if (errors) {
				var message = errors == 1 ?
					'You missed 1 field. It has been highlighted' :
					'You missed ' + errors + ' fields. They have been highlighted';
				$("div.error span").html(message);
				$("div.error").show();
			} else {
				$("div.error").hide();
			}
		},
		submitHandler: function (form, e) {
			e.preventDefault();
			var form_data = new FormData(document.getElementById("company_details_form"));
			swal({
				title: 'Are You Sure Want To Submit?',
				text: '',
				icon: 'warning',
				buttons: true,
				dangerMode: true,
			}).then((willActive) => {
				if (willActive) {
					$.ajax({
						url: base_url + 'company_details/save',
						type: "POST",
						data: form_data,
						processData: false,
						contentType: false,
						cache: false,
						async: false,
						success: function (res) {
							
							var data = $.parseJSON(res);
							if (data.success == '1') {
								swal("Good Job!", data.message, "success").then((value) => {
									window.location = base_url + 'company_details/';
								});
							} else {
								swal("Error!", data.message, "error");
							}
						}
					}); //end ajax
				} //end if
			}); //end then 
		}
	}); //End Validate
	//End form submit

	//status click
	$(document).on("click", ".statusBtn", function () {
		let companyId = $(this).data("companyid");
		let companyStatus = $(this).data("companystatus");
		swal({
			title: 'Are You Sure Want To Deactivate?',
			text: '',
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		}).then((willActive) => {
			if (willActive) {
				$.ajax({
					url: base_url + 'company_details/deactivate',
					type: "POST",
					data: {
						'company_id': companyId,
						'status': companyStatus
					},
					cache: false,
					async: false,
					success: function (res) {
						var res = $.parseJSON(res);

						if (res.success == '1') {
							swal("Good Job!", res.message, "success").then((value) => {
								window.location = base_url + 'company_details/';
							});
						} else {
							swal("Error!", res.message, "error");
						}
					}
				})
			}
		});
	});
	//end status click

	//get address listing
	$(document).on("click", ".addresslist", function () {
		let companyId = $(this).data("companyid");
		let row = "";
		$.ajax({
			url: base_url + 'company_details/addressList',
			type: "POST",
			data: {
				'company_id': companyId,
			},
			cache: false,
			async: false,
			success: function (res) {
				var res = $.parseJSON(res);
				console.log(res);
				if (res['success'] == '1') {
					var srNo = 1;
					$.each(res['res'], function (ind, val) {
						console.log(val);
						row += "<tr class = 'text-center'><td>"+srNo+"</td><td>"+val['company_address']+"</td></tr>";
						srNo++;
					});

					$(document).find("#address-body").children().remove();
					$(document).find("#address-body").append(row);
					$("#addressListmodal").modal("show");
				} else {
					row = "<tr class = 'text-center'><td colspan = '2'>No Data Found</td></tr>";
					$(document).find("#address-body").children().remove();
					$(document).find("#address-body").append(row);
					$("#addressListmodal").modal("show");
				}
			}
		});	
	});

	// edit add delete

	$(document).on("click", ".edit", function () {
		var address = $(this).parent().parent().find(".address").text();
		
		$(document).find("#addressEdit").text(address);
		$(document).find("#addressEdit").attr("data-addressid", $(this).data("addressid"));
		
		$("#editModal").modal("show");
	});

	//submit address
	$(document).on("click", ".editSubmit", function () {
		var addressid = $(this).parent().parent().find("#addressEdit").data("addressid");
		var address = $(this).parent().parent().find("#addressEdit").val();
		var companyId = $(document).find("#company_id").val();

		$.ajax({
			url: base_url + 'company_details/EditAddress',
			type: "POST",
			data: {
				'address_id': addressid,
				'address': address,
				'company_id': companyId
			},
			cache: false,
			async: false,
			success: function (res) {
				var res = $.parseJSON(res);

				if (res.success = '1') {
					swal("Good Job!", res.message, "success").then((value) => {
						location.reload();
					});
				} else {
					swal("Error!", res.message, "error");
				}
			}
		})

	});
	
	$(document).on("click", ".delete", function () {
		var addressId = $(this).data('addressid'); 


		$.ajax({
			url: base_url + 'company_details/deleteAddress',
			type: "POST",
			data: {
				'address_id': addressId,
			},
			cache: false,
			async: false,
			success: function (res) {
				var res = $.parseJSON(res);

				if (res.success = '1') {
					swal("Good Job!", res.message, "success").then((value) => {
						location.reload();
					});
				} else {
					swal("Error!", res.message, "error");
				}
			}
		})	
	})
});
