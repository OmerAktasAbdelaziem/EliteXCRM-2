$(function() {
	"use strict";
	
	
	$('.single-select:not(.inside-modal)').select2({
        theme: 'bootstrap4',
        width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
        placeholder: $(this).data('placeholder'),
        allowClear: Boolean($(this).data('allow-clear')),
    });
	$('.multiple-select').select2({
		theme: 'bootstrap4',
		width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
		placeholder: $(this).data('placeholder'),
		allowClear: Boolean($(this).data('allow-clear')),
	});

	$('.multiple-select').select2({
		theme: 'bootstrap4',
		width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
		placeholder: $(this).data('placeholder'),
		allowClear: Boolean($(this).data('allow-clear')),
	});


    function formatCountry(option) {
        if (!option.id) {
            return option.text;
        }
        var flagUrl = $(option.element).data('flag');
        var $option = $(
            '<span><img src="' + flagUrl + '" style="width: 20px; margin-right: 10px;" /> ' + option.text + '</span>'
        );
        return $option;
    }

    $('.flag_country').select2({
        templateResult: formatCountry,
        templateSelection: formatCountry,
        width: '100%'
    });

	
	});

	$(document).ready(function() {
		// Trigger Select2 initialization for the select element inside the modal when it is shown
		$('.single-select.inside-modal').each(function() {
			var dropdownParent = $(this).closest('.modal');
	
			$(this).select2({
				theme: 'bootstrap4',
				width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
				placeholder: $(this).data('placeholder'),
				allowClear: Boolean($(this).data('allow-clear')),
				dropdownParent: dropdownParent
			});
		});
	});