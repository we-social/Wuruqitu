(function ($) {
	$.fn.getFormData = function (file) {
		var data = {};
		var $ipt = $('input, textarea, select', this).filter('[name]:enabled');
		if (!file) {
			$ipt = $ipt.not(':file');
		}
		$ipt.each(function (i, j) {
			if (j.type === 'checkbox') {
				data[j.name] = j.checked ? j.value : '';
			} else if (j.type === 'radio') {
				if (j.checked) {
					data[j.name] = j.value;
				}
			} else {
				data[j.name] = j.value;
			}
		});
		return data;
	};

	$.fn.toggleForm = function (flag) {
		var $form = $(this);
		var $btn = $form.find('[type=submit]');
		var $prog = $form.find('.ey-progress-in-form');
		if (flag) {
			$btn.addClass('disabled');
			$form.addClass('disabled');
			$form.css({
				opacity: 0.5
			});
			$prog.toggleProgress(true);
		} else {
			$prog.toggleProgress(false);
			$form
				.css({
					opacity: 1
				})
				.removeClass('disabled');
			$btn.removeClass('disabled');
		}
		return $form;
	};
})(window.jQuery);
