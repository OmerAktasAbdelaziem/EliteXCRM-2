$(function() {
	"use strict";
	
	function updateVal(input) {
		const closestDiv = input.closest('div');
		if (closestDiv) {
			const closestRangeDate = closestDiv.querySelector('.rangeDate');
			if (closestRangeDate) {
				closestRangeDate.value = input.value;
			} else {
				console.log('closestRangeDate not found');
			}
		} else {
			console.log('closest div not found');
		}
	}	

	if ($('.from-to-range').length) {
		duDatepicker('.from-to-range', {
			format: 'd/m/yyyy',
			range: true,
			clearBtn: true,
			events: {
				dateChanged: function (data, pickerElement) {
					updateVal(pickerElement.input);
					var form = pickerElement.input.form;
					if (form) {
						form.submit();
					}
				},
				onRangeFormat: function (from, to) {
					let fromFormat = 'd/m/yyyy',
						toFormat = 'd/m/yyyy';

					if (from.getMonth() === to.getMonth() && from.getFullYear() === to.getFullYear()) {
						fromFormat = 'd/m/yyyy';
						toFormat = 'd/m/yyyy';
					} else if (from.getFullYear() === to.getFullYear()) {
						fromFormat = 'd/m/yyyy';
						toFormat = 'd/m/yyyy';
					}

					return from.getTime() === to.getTime()
						? this.formatDate(from, 'd/m/yyyy')
						: [this.formatDate(from, fromFormat), this.formatDate(to, toFormat)].join(' - ');
				},
			},
		});
	}
		
	if ($('.datepicker').length) {
		$('.datepicker').pickadate({
			selectMonths: true,
			selectYears: true
		});
	}
	if ($('.timepicker').length) {
		$('.timepicker').pickatime();
	}

	if ($('.datetimepicker').length) {
		$('.datetimepicker').bootstrapMaterialDatePicker({
			format: 'DD/MM/YYYY',
			time: false,
			date: true,
			clearButton: true,
			nowButton: true,
			switchOnClick: true,
			weekStart: 1,
		});
	}

	if ($('.monthPicker').length) {
		$('.monthPicker').datepicker({
			autoclose: true,
			minViewMode: 1,
			format: 'mm/yyyy'
		});
	}

	if ($('.fromToTimepicker').length) {
		$('.fromToTimepicker').each(function() {
			let id = $(this).attr('id');
			let selectingFromDate = {};
			let fromDate = {};
			let toDate = {};

			selectingFromDate[id] = true;
			fromDate[id] = null;
			toDate[id] = null;

			$(this).bootstrapMaterialDatePicker({
				format: 'DD/MM/YYYY',
				time: false,
				date: true,
				clearButton: true,
				nowButton: false,
				switchOnClick: false,
				weekStart: 1,
			})
			.on('change', function(e, date) {
				if (selectingFromDate[id]) {
					fromDate[id] = date;
					selectingFromDate[id] = false;
					$(this).bootstrapMaterialDatePicker('setMinDate', fromDate[id]);
					$(this).val(moment(fromDate[id]).format('DD/MM/YYYY') + ' - ');
					$('#'+id).val(moment(fromDate[id]).format('DD/MM/YYYY') + ' - ');
				} else {
					toDate[id] = date;
					selectingFromDate[id] = true;
					$(this).bootstrapMaterialDatePicker('setMaxDate', toDate[id]);
					$(this).val(moment(fromDate[id]).format('DD/MM/YYYY') + ' - ' + moment(toDate[id]).format('DD/MM/YYYY'));
					$('#'+id).val(moment(fromDate[id]).format('DD/MM/YYYY') + ' - ' + moment(toDate[id]).format('DD/MM/YYYY'));
					var formId = $('#'+id).attr('form');
					$('#' + formId).submit();
				}
			});

			$('.dtp-btn-clear').on('click', function() {
				const closestDtp = $(this).closest('.dtp');
				const dtpId = closestDtp.attr('id');
				if (dtpId) {
					const matchingElements = $(`[data-dtp="${dtpId}"]`);
					matchingElements.each(function() {
						$(this).val('');
						$(this).bootstrapMaterialDatePicker('setMinDate', null);
						$(this).bootstrapMaterialDatePicker('setMaxDate', null);
						selectingFromDate[id] = true;
					});
				}
			});
		});
	}

});