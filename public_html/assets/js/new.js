$(function() {
    "use strict";

	document.querySelectorAll('.generate-password').forEach(function(button) {
		button.addEventListener('click', function() {
			var password = generatePassword(12);
			var closestPasswordInput = this.closest('.input-group').querySelector('.password');
			closestPasswordInput.value = password;
		});
	});
	
	function generatePassword(length) {
		var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
		var password = "";
		for (var i = 0; i < length; i++) {
			var randomIndex = Math.floor(Math.random() * charset.length);
			password += charset[randomIndex];
		}
		return password;
	}

	$(document).on('click', '.deleteForm', function() {
		let formAction = $(this).attr('formaction');
		let dataTab = $(this).data('tab');
	
		$('#deleteForm').attr('action', formAction);
	
		if (dataTab !== undefined) {
			$('#deleteForm').data('tab', dataTab);
			$('#deleteForm').attr('data-tab', dataTab);
		}
	});	

	$(document).on('click', '.handleForm', function() {
		let formAction = $(this).attr('formaction');
		let dataTab = $(this).data('tab');
	
		$('#handleForm').attr('action', formAction);
	
		if (dataTab !== undefined) {
			$('#handleForm').data('tab', dataTab);
			$('#handleForm').attr('data-tab', dataTab);
		}
	});	

	$(document).on('click', '.edit-comment', function() {
		var form = $(this).closest('form');
		var textarea = form.find('textarea');

		textarea.prop('readonly', false).css('cursor', 'text').focus().removeClass('border-0');
		$(this).addClass('d-none');
		form.find('.submit-comment, .cancel-comment').removeClass('d-none');
	});

	$(document).on('click', '.cancel-comment', function() {
		var form = $(this).closest('form');
		var textarea = form.find('textarea');
		var originalComment = $(this).data('comment');

		textarea.prop('readonly', true).css('cursor', 'default').blur().addClass('border-0');
		textarea.val(originalComment);
		form.find('.submit-comment, .cancel-comment').addClass('d-none');
		form.find('.edit-comment').removeClass('d-none');
	});

	$(document).on('click', 'input[type="checkbox"][data-col]', function() {
		var checked = false;
		var colValue = $(this).attr('data-col');
		$('input[type="checkbox"][data-col="' + colValue + '"]').each(function() {
			var targetElements = $('.' + colValue);

			if ($(this).is(':checked')) {
				targetElements.removeClass('d-none');
				checked = true;
			} else {
				if (checked == false) {
					targetElements.addClass('d-none');
					targetElements.find('input[type="checkbox"]').prop('checked', false);
				}
			}
		});
	});

	$(document).on('change', 'select[data-col]', function() {
		var colValue = $(this).attr('data-col');
		var targetElements = $('.' + colValue);
		var name = $('#name');
		var save_as_template = $('#save_as_template');

		if ($(this).val() === "") {
			targetElements.removeClass('d-none');

			if (save_as_template.is(':checked')) {
				name.attr('required', true);
			} else {
				name.removeAttr('required');
			}
		} else {
			targetElements.addClass('d-none');
			name.removeAttr('required');
		}
	});

	$(document).on('change', '.check-all-table', function() {
		var $this = $(this);
	
		if ($this.data('target')) {
			var targetClass = $this.data('target');
			$('.' + targetClass).each(function() {
				if ($(this).closest('tr').css('display') !== 'none') {
					$(this).prop('checked', $this.prop('checked'));
				}
			});
	
			let checkedCount = $('.check-number:checked').length;
			$('span.number').text(checkedCount);
		}
		
		if ($(this).is(':checked')) {
			$(this).closest('div').find('.hidden-field').prop('disabled', false);
		} else {
			$(this).closest('div').find('.hidden-field').prop('disabled', true);
		}

		var selectedValues = $('.' + targetClass + ':checked').map(function () {
			return $(this).val();
		}).get();
		
		$('#client_emails').val(selectedValues.join(','));
	});

	$(document).on('change', '.check-all', function() {
		var $this = $(this);
		var container = $this.closest('div');
		var checkboxes = container.find('input[type="checkbox"]').not('.hide');
	
		if (checkboxes.length) {
			checkboxes.prop('checked', $this.is(':checked'));
		}
	
		$('input[type="checkbox"][data-col]').each(function() {
			var colValue = $(this).attr('data-col');
			var checked  = false;
			$('input[type="checkbox"][data-col="' + colValue + '"]').each(function() {
				var targetElements = $('.' + colValue);
		
				if ($(this).is(':checked')) {
					targetElements.removeClass('d-none');
					checked = true;
				} else {
					if (checked == false) {
						targetElements.addClass('d-none');
						targetElements.find('input[type="checkbox"]').prop('checked', false);
					}
				}
			});
		});
	});

	$(document).on('change', '.multi-multiple', function() {
		$(this).closest('div').find('.multi-multiple').prop('checked', $(this).is(':checked'));
	});

	$(document).on('click', '.nav-link', function() {
		$('input[type="checkbox"]').not('.is_ftd').prop('checked', false);
		$('span.number').text('0');
	
		var tabId = $(this).attr('id');
		if (tabId === 'action_tab' || tabId === 'deleted_tab') {
			$('#employee').addClass('d-none');
		} else {
			$('#employee').removeClass('d-none');
		}
	});

	$(document).on('change', '.check-number', function() {
		let checkedCount = $('.check-number:checked').length;
		var selectedValues = $('.check-number:checked').map(function () {
			return $(this).val();
		}).get();
		
		$('#client_emails').val(selectedValues.join(','));
		
		$('span.number').text(checkedCount);
	});

	$(document).on('click', '.multi-edit-btn', function() {
		let checkedValues = $('input[type="checkbox"][form="addemployee"][name="clientid[]"]:checked')
			.map(function() {
				return this.value;
			}).get();
	
		let valueString = checkedValues.join(',');
	
		$('.client_id').val(valueString);
	});

	$(document).on('click', '.history_tab', function() {
		$('.history').removeClass('col-lg-10');
		$('.history').addClass('col-lg-12');
		$('.comment-tab').addClass('d-none');
		$('.action-tab').addClass('d-none');
	});

	$(document).on('click', '.tab', function() {
		$('.history').removeClass('col-lg-12');
		$('.history').removeClass('col-lg-10');
		$('.comment-tab').removeClass('d-none');
		$('.action-tab').removeClass('d-none');
	});

	$(document).on('click', '.closed_tab', function() {
		$('.history').removeClass('col-lg-12');
		$('.history').addClass('col-lg-10');
		$('.comment-tab').addClass('d-none');
		$('.action-tab').removeClass('d-none');
	});

	$(document).on('click', '.plus_comment', function() {
		$(this).addClass('d-none');
		
		$(this).closest('.comment-container').find('.submit_comment').removeClass('d-none');
		$(this).closest('.comment-container').find('.comment').removeClass('d-none');
		$(this).closest('.comment-container').find('.x_comment').removeClass('d-none');
	});
	
	$(document).on('click', '.x_comment', function() {
		$(this).addClass('d-none');
		
		$(this).closest('.comment-container').find('.submit_comment').addClass('d-none');
		$(this).closest('.comment-container').find('.comment').addClass('d-none');
		$(this).closest('.comment-container').find('.plus_comment').removeClass('d-none');
	});	

	$(document).on('change', '.filter-select', function() {
		var formId = $(this).attr('form');
		$('#' + formId).submit();
	});
	
	if ($("#from-date-actions").length) {

		var fromDate = $("#from-date-actions");
		var toDate   = $("#to-date-actions");

		fromDate.on("change", function() {
			var selectedDate = fromDate.val();
			toDate.bootstrapMaterialDatePicker("setMinDate", selectedDate);
		});
		function filterFunction(settings, data, dataIndex) {
			var fromDate = $('#from-date-actions').val();
			var toDate   = $('#to-date-actions').val();
			var dateCol  = data[0];

			var minDate = moment(fromDate, 'DD/MM/YYYY');
			var maxDate = moment(toDate, 'DD/MM/YYYY');
			var date    = moment(dateCol, 'DD/MM/YYYY');

			if ((fromDate == '' || date >= minDate) && (toDate == '' || date <= maxDate)) {
				return true;
			}
			return false;
		}

		table.buttons().container().appendTo( '#all-actions_wrapper .col-md-6:eq(0)' );

		$('#from-date-actions, #to-date-actions').on('change', function() {
			table.draw();
		});
	}

	$(document).on('change', '.related', function() {
		if ($(this).is(':checked')) {
			$(this).closest('div').find('.hidden-field').prop('disabled', false);
		} else {
			$(this).closest('div').find('.hidden-field').prop('disabled', true);
		}
	});

	$(document).on('keyup mousemove input', '.se-wrapper-inner', function () {
        var body = $(this).html();
        $('#editor_classic').val(body);
        $('#editor_classic').html(body);
    });

	$(document).on('click', '#save_as_template', function() {
		var targetElement = $('#name');

		if ($(this).is(':checked')) {
			targetElement.attr('required', true);
		} else {
			targetElement.removeAttr('required');
		}
	});

	setInterval(function() {
        $('.se-btn-primary:disabled').prop('disabled', false);
    }, 3000);

	if ($(".filterable").length) {
		$('#filter_name, #filter_email, #filter_phone, #filter_country, #filter_status').on('input change', function () {
			$('.check-all-table').prop('checked', false);
			filterTable();
		});
	
		function filterTable() {
			let nameFilter = $('#filter_name').val();
			let emailFilter = $('#filter_email').val();
			let phoneFilter = $('#filter_phone').val();

			let countryTextFilter = [];
			$('#filter_country option:selected').each(function() {
				countryTextFilter.push($(this).text().toLowerCase());
			});

			let statusTextFilter = [];
			$('#filter_status option:selected').each(function() {
				statusTextFilter.push($(this).text().toLowerCase());
			});

			nameFilter = (nameFilter && nameFilter !== '') ? nameFilter.toLowerCase() : '';
			emailFilter = (emailFilter && emailFilter !== '') ? emailFilter.toLowerCase() : '';
			phoneFilter = (phoneFilter && phoneFilter !== '') ? phoneFilter.toLowerCase() : '';
			countryTextFilter = countryTextFilter.length > 0 ? countryTextFilter : [];
			statusTextFilter = statusTextFilter.length > 0 ? statusTextFilter : [];


	
			$('tbody tr').each(function () {
				const name = $(this).data('name') ? $(this).data('name').toLowerCase() : '';
				const email = $(this).data('email') ? $(this).data('email').toLowerCase() : '';
				const phone = $(this).data('phone') ? $(this).data('phone').toLowerCase() : '';
				const country = $(this).data('country') ? $(this).data('country').toLowerCase() : '';
				const status = $(this).data('status') ? $(this).data('status').toLowerCase() : '';
	
				const isNameMatch = nameFilter ? name.includes(nameFilter) : true;
				const isEmailMatch = emailFilter ? email.includes(emailFilter) : true;
				const isPhoneMatch = phoneFilter ? phone.includes(phoneFilter) : true;
				const isCountryMatch = countryTextFilter.length > 0 ? countryTextFilter.some(s => country.includes(s)) : true;
				const isStatusMatch = statusTextFilter.length > 0 ? statusTextFilter.some(s => status.includes(s)) : true;
				console.log(countryTextFilter);
				console.log(countryTextFilter);
	
				if (isNameMatch && isEmailMatch && isPhoneMatch && isCountryMatch && isStatusMatch) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		}
	}

	if ($("#client_emails2").length) {
		$('#client_emails2').on('itemAdded', function(event) {
			console.log('Email added: ' + event.item);
			console.log($('#client_emails2').val());
		});
	
		$('#client_emails2').on('itemRemoved', function(event) {
			console.log('Email removed: ' + event.item);
		});
	
		$('#client_emails2').on('beforeItemAdd', function(event) {
			if (!isValidEmail(event.item)) {
				alert('Please enter a valid email address.');
				event.cancel = true;
			}
		});
	
		function isValidEmail(email) {
			const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
			return emailRegex.test(email);
		}
	}
});