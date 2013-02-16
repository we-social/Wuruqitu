(function ($) {
	(function () {
		// screen lock
		var _locked = false;
		function lockScreen() {
			var $b = $('body').addClass('ey-lock');
			$('<div>').attr('id', 'ey_overlay').appendTo($b);
			_locked = true;
		}
		function unlockScreen() {
			var $b = $('body').removeClass('ey-lock');
			$b.children('#ey_overlay').remove();
			_locked = false;
		}
		function toggleScreen() {
			_locked ? unlockScreen() : lockScreen();
		}
		window.lockScreen = lockScreen;
		window.unlockScreen = unlockScreen;
		window.toggleScreen = toggleScreen;
		$(document).on('keydown', function (e) {
			if (e.keyCode === 114) {
				// F3
				e.preventDefault();
				toggleScreen();
			}
		});
	})();
	(function () {
		// menu lock
		function lockMenuHandler(e) {
			e.preventDefault();
		}
		var _locked = false;
		function lockMenu() {
			$(document).on('contextmenu', lockMenuHandler);
			_locked = true;
		}
		function unlockMenu() {
			$(document).off('contextmenu', lockMenuHandler);
			_locked = false;
		}
		function toggleMenu() {
			_locked ? unlockMenu() : lockMenu();
		}
		window.lockMenu = lockMenu;
		window.unlockMenu = unlockMenu;
		window.toggleMenu = toggleMenu;
		$(document).on('keydown', function (e) {
			if (e.keyCode === 115) {
				// F4
				e.preventDefault();
				toggleMenu();
			}
		});
	})();

	window.safeHtml = function (txt) {
		// string processing
		return txt
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/ /g, '&nbsp;')
			.replace(/\n/g, '<br>');
	};
	window.toBits = function (str, bits) {
		str += '';
		for (var i = str.length; i < bits; i++) {
			str = '0' + str;
		}
		return str;
	};
	window.enterstr = function (str, max) {
		var reg = new RegExp('[a-zA-Z0-9\\][\';/.,=!@#^&*()_+<>?:"{}|-]{' + (max + 1) + '}');
		var i = str.search(reg);
		while (i !== -1) {
			str = str.substring(0, i + max) + '\n' + str.substring(i + max);
			i = str.search(reg);
		}
		return str;
	};

	window.scrollToTop = function (fn) {
		fn = fn ? fn : function () {};
		if (document.body.scrollTop) {
			$('body').animate(
				{
					scrollTop: 0
				},
				fn
			);
		} else if (document.documentElement.scrollTop) {
			var t = setInterval(function () {
				if (document.documentElement.scrollTop > 0) {
					document.documentElement.scrollTop -= 8;
				} else {
					clearInterval(t);
					fn();
				}
			}, 20);
		} else {
			fn();
		}
	};
	window.countPlus = function ($tar, n) {
		// bootstrap-based extension
		var num = Number($tar.text());
		$tar.text(num + n);
	};

	$.fn.returnFalse = function (selector, events) {
		return $(this).delegate(selector, events, function () {
			return false;
		});
	};
	$.fn.popupAlert = function (opt) {
		var $area = $(this);
		var tags = ['<div class="alert alert-', opt.theme, '">', opt.msg, '</div>'].join('');
		var $alert = $(tags)
			.hide()
			.css('position', 'absolute')
			.css(opt.css || {});
		if (opt.duration) {
			$alert
				.css({
					'padding-right': '12px'
				})
				.addClass('ey-alert-flash');
		} else {
			var tags = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
			$alert.prepend(tags).addClass('ey-alert-static');
		}
		$alert.appendTo($area).show(500);
		if (opt.duration) {
			$alert.delay(opt.duration).hide(500, function () {
				$(this).remove();
			});
		}
		return $area;
	};
	$.fn.toggleProgress = function (flag, callback, atOnce) {
		var $prog = $(this);
		if (flag) {
			if ($prog.is(':visible')) {
				callback && callback();
			} else {
				if (atOnce) {
					$prog.stop().show();
				} else {
					$prog.stop().fadeIn(500, callback);
				}
			}
		} else {
			if ($prog.is(':hidden')) {
				callback && callback();
			} else {
				if (atOnce) {
					$prog.stop().hide();
				} else {
					$prog.fadeOut(500, callback);
				}
			}
		}
		return $prog;
	};
})(window.jQuery);
