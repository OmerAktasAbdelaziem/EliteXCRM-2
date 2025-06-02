$(function() {
    "use strict";

    if ($('.header-notifications-list').length) {
        new PerfectScrollbar(".header-notifications-list");
    }

    if ($('.chat-content').length) {
		$('.chat-content').each(function() {
			new PerfectScrollbar(this);
		});
		
		function autoResize($textarea) {
			$textarea.css('height', 'auto');
			$textarea.css('height', $textarea[0].scrollHeight + 'px');
		}
		
		$('textarea').each(function() {
			autoResize($(this));
	
			$(this).on('input', function() {
				autoResize($(this));
			});
		});
	}
	
});
