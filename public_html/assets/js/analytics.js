
$(function() {
	"use strict";
	var e = {
		series: [],
		chart: {
			height: 240,
			type: "donut"
		},
		legend: {
			position: "bottom",
			show: !1
		},
		plotOptions: {
			pie: {
				donut: {
					size: "60%"
				}
			}
		},
		colors: [],
		dataLabels: {
			enabled: !1
		},
		labels: [],
		responsive: [{
			breakpoint: 480,
			options: {
				chart: {
					height: 200
				},
				legend: {
					position: "bottom"
				}
			}
		}]
	};
	var i = 0;
	Statuses.forEach(function(status) {
		e.series.push(status.leads);
		e.labels.push(status.name);
		if (i == 0) {
			e.colors.push("#17a00e");
		} else if (i == 1) {
			e.colors.push("#f41127");
		}
		else if (i == 2) {
			e.colors.push("#0d6efd");
		}
		else {
			e.colors.push("#ffc107");
		}
		i++;
		if (i == 4) {
			i = 0;
		}
	});
	new ApexCharts(document.querySelector("#chart15"), e).render();
	var e = {
		series: [{
			name: "This Month <span class='text-primary'>" + currentMonthDaysCount + "</b>",
			data: []
		}],		
		chart: {
			foreColor: "#9ba7b2",
			type: "area",
			height: 270,
			toolbar: {
				show: false
			},
			zoom: {
				enabled: false
			},
			dropShadow: {
				enabled: true,
				top: 3,
				left: 14,
				blur: 4,
				opacity: 0.12,
				color: ["#0d6efd", "#777", "#f41127"]
			},
			sparkline: {
				enabled: false
			}
		},
		markers: {
			size: 0,
			colors: ["#0d6efd"],
			strokeColors: "#fff",
			strokeWidth: 2,
			hover: {
				size: 7
			}
		},
		grid: {
			show: true,
			borderColor: 'rgba(0, 0, 0, 0.15)',
			strokeDashArray: 4,
		},
		plotOptions: {
			bar: {
				horizontal: false,
				columnWidth: "30%",
				endingShape: "rounded"
			}
		},
		dataLabels: {
			enabled: false
		},
		stroke: {
			show: true,
			width: 3,
			curve: "smooth"
		},
		colors: ["#0d6efd", "#777", "#f41127"],
		xaxis: {
			categories: []
		},
		fill: {
			opacity: 1
		},
		tooltip: {
			theme: "dark",
			fixed: {
				enabled: false
			},
			x: {
				show: true
			},
			y: {
				formatter: function(e) {
					return " " + e + " "
				}
			},
			marker: {
				show: false
			}
		}
	};
	var last_days = 0;
	var days = 0;
	var diff = 0;

	days_leads.forEach(function(lead) {
		e.series[0].data.push(lead.count);
		days++;
	});

	e.series.push({
		name: "Last Month <span class='text-secondary'>" + lastMonthDaysCount + "</span>",
		data: []
	});

	last_days_leads.forEach(function(lead) {
		e.series[1].data.push(lead.count);
		last_days++;
	});

	if (days > last_days) {
		diff = days;
	} else if (last_days > days) {
		diff = last_days;
	}

	for (let j = 1; j <= diff; j++) {
		e.xaxis.categories.push(j);
	}

	new ApexCharts(document.querySelector("#chart19"), e).render();
});

$(document).ready(function() {
	if ($('#period').length) {
		$('#period').on('change', function() {
			$('#filter-form').submit();
		});
	}
	$('.ajax-filter').on('submit', function(e) {
        e.preventDefault(); 

        const form = $(this);
        const dataClass = form.data('class');
        const targetElement = $('.' + dataClass);

        $.ajax({
            type: 'GET',
            url: form.attr('action'),
            data: form.serialize(),
            success: function(response) {
                targetElement.empty().html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });
	function updateModelType(select) {
		const modelType = $(select).find(':selected').data('model');
		
		const modelTypeId = $(select).data('typeid');
		
		document.getElementById(modelTypeId).value = modelType || '';
	}

	$('.user-select').on('change', function() {
        updateModelType(this);
    });
});