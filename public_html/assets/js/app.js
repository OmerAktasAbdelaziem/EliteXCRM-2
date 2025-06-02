$(function() {
	"use strict";
	$(".mobile-search-icon").on("click", function() {
		$(".search-bar").addClass("full-search-bar")
	}), $(".search-close").on("click", function() {
		$(".search-bar").removeClass("full-search-bar")
	}), $(".mobile-toggle-menu").on("click", function() {
		$(".wrapper").addClass("toggled")
	}), $(".toggle-icon").click(function() {
		$(".wrapper").hasClass("toggled") ? ($(".wrapper").removeClass("toggled"), $(".sidebar-wrapper").unbind("hover")) : ($(".wrapper").addClass("toggled"), $(".sidebar-wrapper").hover(function() {
			$(".wrapper").addClass("sidebar-hovered")
		}, function() {
			$(".wrapper").removeClass("sidebar-hovered")
		}))
	}), $(document).ready(function() {
		$(window).on("scroll", function() {
			$(this).scrollTop() > 300 ? $(".back-to-top").fadeIn() : $(".back-to-top").fadeOut()
		}), $(".back-to-top").on("click", function() {
			return $("html, body").animate({
				scrollTop: 0
			}, 600), !1
		})
	}), $(function() {
	}), $(function() {
		$("#menu").metisMenu()
	}),$(".chat-toggle-btn").on("click", function() {
		$(".chat-wrapper").toggleClass("chat-toggled")
	}), $(".chat-toggle-btn-mobile").on("click", function() {
		$(".chat-wrapper").removeClass("chat-toggled")
	}), $(".email-toggle-btn").on("click", function() {
		$(".email-wrapper").toggleClass("email-toggled")
	}), $(".email-toggle-btn-mobile").on("click", function() {
		$(".email-wrapper").removeClass("email-toggled")
	}), $(".compose-mail-btn").on("click", function() {
		$(".compose-mail-popup").show()
	}), $(".compose-mail-close").on("click", function() {
		$(".compose-mail-popup").hide()
	}), $(".switcher-btn").on("click", function() {
		$(".switcher-wrapper").toggleClass("switcher-toggled")
	}), $(".close-switcher").on("click", function() {
		$(".switcher-wrapper").removeClass("switcher-toggled")
	}), $("#lightmode").on("click", function() {
		$("html").attr("class", "light-theme")
	}), $("#darkmode").on("click", function() {
		$("html").attr("class", "dark-theme")
	}), $("#semidark").on("click", function() {
		$("html").attr("class", "semi-dark")
	});
});
document.addEventListener('DOMContentLoaded', function() {
	var forms = document.querySelectorAll('form');

	forms.forEach(function(form) {
		form.addEventListener('submit', function() {
			var submitButtons = this.querySelectorAll('button[type="submit"]');
			submitButtons.forEach(function(button) {
				button.setAttribute('disabled', 'disabled');
			});
		});
	});
});

if ($('.entries-per-page').length) {
    $('.entries-per-page').on('change', function() {
        var selectedValue = $(this).val();
        var selectedTab = $(this).data('tab');
        var url = window.location.href;

        if (url.includes('?') || url.includes('#')) {
            if (url.includes('limit=')) {
                url = url.replace(/limit=\d+/, 'limit=' + selectedValue);
            } else {
                if (url.includes('?')) {
                    url += '&limit=' + selectedValue;
                } else {
                    url += '?limit=' + selectedValue;
                }
            }
        } else {
            url += '?limit=' + selectedValue;
        }

        if (selectedTab) {
            if (url.includes('tab=')) {
                url = url.replace(/tab=[^&]*/, 'tab=' + selectedTab);
            } else {
                if (url.includes('?')) {
                    url += '&tab=' + selectedTab;
                } else {
                    url += '?tab=' + selectedTab;
                }
            }
        }

        window.location.href = url;
    });
}

$(document).on('submit', 'form.ajax-form', function(e) {
	e.preventDefault();

	const form = $(this);
	const formData = form.serialize();
	const submitButton = form.find('button[type="submit"]');

	$.ajax({
		url: form.attr('action'),
		type: form.attr('method'),
		data: formData,
		success: function(response) {
			const tabValue = form.data('tab');
			if (tabValue) {
				const url = new URL(window.location.href);
				url.searchParams.set('tab', tabValue);
				window.location.href = url.href;
			} else {
				location.reload();
			}
		},
		error: function(xhr) {
			submitButton.removeAttr('disabled');
			alert('An error occurred while submitting the form.');
		}
	});
});

$(document).ready(function() {

	$('form.ajax-form-no-reload').on('submit', function(e) {
		e.preventDefault();
		
		var form = $(this);
		var formData = form.serialize();
		
		var submitButton = form.find('button[type="submit"]');
		$('#error_message').addClass('d-none alert-danger').removeClass('alert-success').html('');
		
		$.ajax({
			url: form.attr('action'),
			type: form.attr('method'),
			data: formData,
			success: function(response) {
				// $('.btn-close').click();
				// $('.refresh').click();
				// submitButton.removeAttr('disabled');
				// if (response.data == 1) {
				// 	$('#error_message').removeClass('d-none').html('Not enough money/margin for open this order');
				// }
				// if (response.data == 0) {
				// 	$('#error_message').removeClass('d-none alert-danger').addClass('alert-success').html('Order Created Successfully');
				// }
				// if (response.data == 3) {
				// 	$('#error_message').removeClass('d-none').html('Out of the trading session');
				// }
				submitButton.removeAttr('disabled');
			},
			error: function(xhr) {
				submitButton.removeAttr('disabled');
				
				alert('An error occurred while submitting the form.');
			}
		});
	});

	setTimeout(function() {
        $(".metismenu .d-none").removeClass('d-none');
    }, 1000);

	$('.metismenu li').hover(
		function() {
			$(this).addClass('mm-active').click();
			$(this).find('.mm-collapse').addClass('mm-show');
			$(this).find('.mm-collapse').addClass('mm-show').css('height', 'fit-content');
		},
		function() {
			$(this).removeClass('mm-active').click();
			$(this).find('.mm-collapse').removeClass('mm-show');
		}
	);
	
});
