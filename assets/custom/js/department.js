$("#dept-form").validate({
	rules: {
		dept_title: {
			required: true,
			remote: {
				url: base_url + '/validate_edit_dept',
				type: 'POST',
				data: {
					dept_id: () => {
						return $('#dept_id').val()
					}
				}
			}
		},
		dept_email: {
			required: true,
		}
	},
	messages: {
		dept_title: {
			required: "Please provide department title.",
			remote: 'This department is already in used'
		},
		dept_email: {
			required: "Please Provide department mail id",
		}
	},

	errorPlacement: function (error, element) {
		console.log(element);
		error.addClass("ui red pointing label transition");
		error.insertAfter(element.after());
	},

	invalidHandler: function (event, validator) {
		// 'this' refers to the form
		var errors = validator.numberOfInvalids();
		console.log(errors);
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
		// console.log('Form submitted');
		var status = true;

		$(".roleDrop :selected").each(function () {
			if ($(this).val() == 0) {
				$(this).parent().parent().append('<label id="error" class="error ui red pointing label transition">This field is required.</label>').css({
					'color': 'red',
					'font-weight': '400 !important'
				});
				status = false;
				setTimeout(function () {
					$(document).find('#error').remove();
				}, 2000);

				return;
			}
		});

		var checkArray = [];

		$("input[type='checkbox']").each(function () {
			if ($(this).is(":checked")) {
				//checked
				checkArray.push("1");
			} else {
				//unchecked
				checkArray.push("2");
			}
		});

		if (status) {
			var formData = new FormData(document.getElementById("dept-form"));
			formData.append("payableArray", JSON.stringify(checkArray));

			$.ajax({
				type: 'POST',
				url: base_url + 'dept/save',
				data: formData,
				processData: false,
				contentType: false,
				cache: false,
				async: false,
				success: function (res) {
					// console.log(res.status);
					var res = JSON.parse(res);
					// console.log(res);
					// $('#modal-add').modal('toggle');
					if (res.status == '1') {
						swal("Good Job!", res.message, "success")
							.then((value) => {
								window.location = base_url + 'departments';
							});
					} else if (res.status == '2') {
						swal("Warning!", res.message, "warning");
					}
				},
			});

		}
		// return false;
	}
});

function roleDropdown() {
	let optRole = "<option value = '0'>Select Role</option>";
	let roleDrop = "";
	$.ajax({
		url: base_url + "dept/roles",
		type: "POST",
		dataType: "json",
		async: false,
		success: function (res) {
			$.each(res, function (ind, val) {
				optRole += "<option value = '" + val.role_id + "'>" + val.role_title + "</option>";
			});
			roleDrop = "<select class='selectpicker form-control' id = 'role' name = 'role[]' data-live-search ='true' required = '' tabindex = '-98'>" + optRole + "</select>";
		}
	});


	return roleDrop;
}

var inc = 2;

$("#add-role-btn").click(function () {
	$('.tableBody').append('<tr><td class = "text-center">' + inc + '</td><td class="text-center activity"><div class="dropdown bootstrap-select form-control roleDrop">' + roleDropdown() + '</div> </td><td class="text-center activity"><div class="form-check"><label> <input type="checkbox" name="check[]" id = "check" class = "check"> </label> </div></td> <td class="text-center action"><span style="font-size: 25px; cursor:pointer" class="delete" data-id = "' + inc + '"><i class="fas fa-trash"></i></span></td></tr>');
	inc++;
	$('.selectpicker').selectpicker('refresh');
});

$(document).on("click", ".delete", function () {
	if ($(this).data('id') != '0') {
		$(this).parent().parent().remove();
	}
});


$(document).on("click", ".deleteRow", function () {
	var access_id = $(this).data("accessid");
	let deleteStatus = "";
	$.ajax({
		type: 'POST',
		url: base_url + 'dept/deleteRow',
		data: {
			"accessId": access_id
		},
		cache: false,
		async: false,
		success: function (res) {
			var res = JSON.parse(res);
			// console.log(res.success);
			deleteStatus = res.success;
		}
	});

	if (deleteStatus == '1') {
		$(this).parent().parent().remove();
	}
})
