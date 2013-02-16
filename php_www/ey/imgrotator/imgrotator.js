(function ($) {
	$.fn.imgrotator = function (enable) {
		return $(this).find('.ey-rotator-btn')[enable ? 'show' : 'hide']();
	};
	$(function () {
		$('body').delegate('.ey-img-rotator .ey-rotator-btn', 'click', function () {
			var $btn = $(this);
			if ($btn.is('.disabled')) return;

			$btn.addClass('disabled');
			var $rotator = $btn.closest('.ey-img-rotator');
			var $tar = $rotator.find('.ey-rotator-img');
			$tar.height($tar.width());
			var deg = parseInt($tar.attr('data-degrees'));

			deg = (deg + 90) % 360;
			$tar.attr('data-degrees', '' + deg);
			$rotator.find('.ey-rotator-deg').val(deg);
			$btn.removeClass('disabled');
		});
	});
})(window.jQuery);
