$(document).ready(function () {
	jQuery.validator.addMethod("lettersonly", function (value, element) {
		return this.optional(element) || /^[a-z\s]+$/i.test(value);
	}, "Letters only please");

	$("#defectliab_form").validate({
		rules: {
			liab_name: {
				required: true,
				lettersonly: true
			},
			mul_factor: {
				required: true,
				number: true
			}
		},
		messages: {
			liab_name: "Please Enter Defect Liablity Period",
			mul_factor: "Please Enter Multiplication Factor"
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
			var form_data = new FormData(document.getElementById("defectliab_form"));
			swal({
				title: 'Are You Sure Want To Submit?',
				text: '',
				icon: 'warning',
				buttons: true,
				dangerMode: true,
			}).then((willActive) => {
				if (willActive) {
					$.ajax({
						url: base_url + 'defect_liab/save',
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
									window.location = base_url + 'defect_liab/';
								});
							} else {
								swal("Error!", data.message, "error");
							}
						}
					});//end ajax
				}//end if
			});//end then 
		}
	});//End Validate

	//edit form submit
	$("#defectliab_form_edit").validate({
		rules: {
			liab_name: {
				required: true,
				lettersonly: true
			},
			mul_factor: {
				required: true,
				number: true
			}
		},
		messages: {
			liab_name: "Please Enter Defect Liablity Period",
			mul_factor: "Please Enter Multiplication Factor"
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
			var form_data = new FormData(document.getElementById("defectliab_form_edit"));
			swal({
				title: 'Are You Sure Want To Edit?',
				text: '',
				icon: 'warning',
				buttons: true,
				dangerMode: true,
			}).then((willActive) => {
				if (willActive) {
					$.ajax({
						url: base_url + 'defect_liab/save',
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
									window.location = base_url + 'defect_liab/';
								});
							} else {
								swal("Error!", data.message, "error");
							}
						}
					}); //end ajax
				} //end if
			}); //end then 
		}
	});
	//end edit form submit

	//deactivate liablity
	$(document).on("click", ".statusBtn", function () {
		let laibId = $(this).data("laibid");
		let laibStatus = $(this).data("laibstatus");
		swal({
			title: 'Are You Sure Want To Deactivate?',
			text: '',
			icon: 'warning',
			buttons: true,
			dangerMode: true,
		}).then((willActive) => {
			if (willActive) {
				$.ajax({
					url: base_url + 'defect_liab/deactivate',
					type: "POST",
					data: {
						'laib_id': laibId,
						'status': laibStatus
					},
					cache: false,
					async: false,
					success: function (res) {
						var res = $.parseJSON(res);

						if (res.success == '1') {
							swal("Good Job!", res.message, "success").then((value) => {
								window.location = base_url + 'defect_liab/';
							});
						} else {
							swal("Error!", res.message, "error");
						}
					}
				})
			}
		});
	});
	//End deactivate liablity
})
