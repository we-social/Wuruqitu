(function ($) {
	$(function () {
		// main head
		var $vars = $('#my_smarty_vars');
		$('input', $vars).each(function (i, j) {
			window[j.name] = j.value;
		});
	});

	(function () {
		// good
		function good(upid) {
			var $up = $('#my_up_' + upid);
			var $btn = $up.find('.my-btn-good');
			var $count = $btn.addClass('disabled').find('.my-count');

			$.get('good.php?_t=' + upid, function (d) {
				log.debug(d);
				eval('d=' + d);
				log.info(d.msg);
				var $toolbar = $btn.closest('.btn-toolbar');

				var pass = d.state === 'pass';
				if (pass) {
					var $count = $btn.find('.my-count');
					countPlus($count, 1);
				}
				$up.find('.my-alertarea-good').popupAlert({
					theme: pass ? 'info' : 'warning',
					msg: d.msg,
					duration: 3000
				});
			});
		}
		window.good = good;
	})();

	(function () {
		// comment
		var $pane, $list, $prev, $next, $win;
		var $form, $txt, $btn;
		var wPane, cols;
		var $listProg;
		var $alertarea;

		function posComm() {
			var wWin = $(window).width();
			var $col = $pane.parent('.my-span');
			var col = parseInt($col.data('my-col'));
			var wCol = $col.width();
			function reset() {
				$pane.css({
					width: wPane + 'px',
					'margin-left': '0'
				});
			}

			reset();
			if (wPane >= wCol) {
				// pane wider
				if (wWin < 767) {
					// client narrow to single col
					$pane.css({
						width: wCol - 31 + 'px'
					});
				} else if (col === cols) {
					// in the last col
					$pane.css({
						'margin-left': wCol - 31 - wPane + 'px'
					});
				}
			}
		}
		function openComm(upid) {
			$alertarea.empty();
			var $up = $('#my_up_' + upid);
			$pane.insertAfter($up).data('upid', upid);
			posComm();
			updateComm(upid, 1);
			$win.on('resize', posComm);
			$pane.fadeIn(500);
		}
		function closeComm() {
			$pane.hide();
			$form.toggleForm(false);
			$listProg.toggleProgress(false, null, true);
			$win.off('resize', posComm);
			$txt.val('');
		}
		function toggleComm(upid) {
			if ($pane.is(':hidden')) {
				openComm(upid);
			} else {
				closeComm();
				if (upid !== $pane.data('upid')) {
					openComm(upid);
				}
			}
		}
		function updateComm(upid, page) {
			$list
				.stop()
				.css({
					'min-height': '30px'
				})
				.empty();
			$listProg.toggleProgress(true);

			$.get('comm.php?_rw=r&_t=' + upid + '&_p=' + page, function (d) {
				if (upid !== $pane.data('upid')) return;

				log.debug(d);
				eval('d=' + d);
				log.info(d.msg);
				var comms = d.comms,
					tags = '';
				for (var i = 0; i < comms.length; i++) {
					tags += [
						'<li>',
						'<p>',
						comms[i]['cmfrom'],
						':</p>',
						'<blockquote>',
						safeHtml(enterstr(comms[i]['cmtext'], 24)),
						'</blockquote>',
						'</li>'
					].join('');
				}
				$list.html(tags);
				$listProg.toggleProgress(false, function () {
					$list.animate(
						{
							'min-height': '0px'
						},
						500
					);
				});

				var pages = parseInt(d.pages);
				var $pager = $('.pager', $pane);
				if (pages > 1) {
					$pager.show();
					$prev.find('a').attr('href', 'javascript:updateComm(' + upid + ',' + (page - 1) + ')');
					$next.find('a').attr('href', 'javascript:updateComm(' + upid + ',' + (page + 1) + ')');
					if (page > 1) {
						$prev.removeClass('disabled');
					} else {
						$prev.addClass('disabled');
					}
					if (page < pages) {
						$next.removeClass('disabled');
					} else {
						$next.addClass('disabled');
					}
				} else {
					$pager.hide();
				}
			});
		}
		window.openComm = openComm;
		window.closeComm = closeComm;
		window.toggleComm = toggleComm;
		window.updateComm = updateComm;

		function commHandler(e) {
			e.preventDefault();
			$form.toggleForm(true);
			var upid = $pane.data('upid');
			$(':hidden', $form).val(upid);

			function ajaxHandler(d) {
				log.debug(d);
				eval('d=' + d);
				log.info(d.msg);
				var pass = d.state === 'pass';
				if (pass) {
					$txt.val('');
					updateComm(upid, 1);
					$count = $('#my_btn_comm_' + upid).find('.my-count');
					countPlus($count, 1);
				}
				$alertarea.popupAlert({
					theme: pass ? 'info' : 'warning',
					msg: d.msg,
					duration: 3000
				});
				$form.toggleForm(false);
			}
			var dat = $form.getFormData();
			log.debug(JSON.stringify(dat));
			$.ajax({
				url: $form.attr('action'),
				type: $form.attr('method'),
				data: dat,
				success: ajaxHandler
			});
		}

		$(function () {
			$pane = $('#my_pane_comm');
			$list = $('#my_list_comm');
			$prev = $('#my_prev_comm');
			$next = $('#my_next_comm');
			$win = $(window);
			wPane = $pane.width();
			cols = parseInt(_COLS);

			$form = $('#my_form_comm').submit(commHandler);
			$txt = $form.find('textarea');
			$btn = $form.find('[type=submit]');
			$listProg = $list.prev();

			$alertarea = $('#my_alertarea_comm');
		});
	})();

	(function () {
		// upload
		var $pane, $entry, $win;
		var $form, $txt, $btn;
		var wPane;
		var $alertarea;

		var $preview, $img, $rot;
		var hasfileAPI = window.FileReader;

		var get_$pic = function () {
			return $('#my_ipt_pic');
		};

		function reset() {
			var wWin = $win.width();
			var w = wWin - 100 < wPane ? wWin - 100 : wPane;
			$pane.css({
				width: w + 'px',
				left: (wWin - 32 - w) / 2 + 'px'
			});
		}
		function resizeImg() {
			var w = $preview.width();
			$img.width(w).height(w);
		}
		function clearForm(emptyTxt) {
			get_$pic().val('');
			emptyTxt && $txt.val('');

			if (hasfileAPI) {
				$img.attr('src', 'img/what.jpg').attr('data-degrees', '0');
				$rot.imgrotator(false);
				$win.off('resize', resizeImg);
			}
		}

		function openUpload() {
			if ($pane.is(':visible')) return;
			$entry.blur().addClass('disabled'); // clear focus box in opera

			scrollToTop(function () {
				reset();
				$win.on('resize', reset);
				$pane.fadeIn(500);
			});
		}
		function closeUpload() {
			if ($pane.is(':hidden')) return;
			$entry.removeClass('disabled');
			$form.toggleForm(false);
			$pane.hide();

			clearForm(true);
		}
		window.openUpload = openUpload;
		window.closeUpload = closeUpload;

		function upHandler(e) {
			e.preventDefault();
			function ajaxHandler(d) {
				log.debug(d);
				eval('d=' + d);
				log.info(d.msg);

				var pass = d.state === 'pass';
				clearForm(pass);
				if (pass) {
					setTimeout(function () {
						location.reload();
					}, 4000);
				}

				$alertarea.popupAlert({
					theme: pass ? 'info' : 'warning',
					msg: d.msg,
					duration: 3000
				});
				$form.toggleForm(false);
			}

			$form.toggleForm(true);
			var dat = $form.getFormData();
			log.debug(JSON.stringify(dat));
			$.ajaxFileUpload({
				url: $form.attr('action'),
				secureuri: false,
				fileElementId: 'my_ipt_pic',
				data: dat,
				dataType: 'text',
				success: function (d) {
					ajaxHandler(d);
				}
			});
		}
		$(function () {
			$pane = $('#my_pane_upload');
			$entry = $('#my_entry_upload');
			wPane = $pane.width();
			$win = $(window);

			$form = $('#my_form_upload');
			$form.submit(upHandler);
			$txt = $('textarea', $form);

			$btn = $('[type=submit]', $form);

			$preview = $('#my_preview');
			$rot = $preview.find('.ey-img-rotator');
			$img = $('#my_preview_img');
			$alertarea = $('#my_alertarea_upload');

			if (hasfileAPI) {
				$form.delegate('#my_ipt_pic', 'change', function (evt) {
					var files = evt.target.files;
					if (files.length < 1 || !files[0].type.match('image.*')) {
						clearForm(false);

						return;
					}

					var f = files[0];
					$img.attr('data-degrees', '0');
					$form.toggleForm(true);
					var reader = new FileReader();
					reader.onload = (function (theFile) {
						return function (e) {
							$img
								.one('load', function () {
									var $img = $(this);
									var w = $preview.width();
									$img.width(w).height(w).show(); // $preview.width true..
									$rot.imgrotator(true);

									$win.on('resize', resizeImg);
									$form.toggleForm(false);
								})
								.attr('src', e.target.result);
						};
					})(f);
					reader.readAsDataURL(f);
				});
			} else {
				$preview.hide();
			}
		});
	})();

	$(function () {
		// main tail
		$('#my_nav_' + _ACTIVE).addClass('active');

		var $body = $('body');
		$body.show();
		$body.delegate('.disabled, [disabled]', 'click keydown', function (e) {
			return false;
		});

		$('#my_row_ups').gifPlayer({
			fakeToReal: function (s) {
				return s.replace(/_\.gif$/, '.gif');
			},
			realToFake: function (s) {
				return s.replace(/\.gif$/, '_.gif');
			},
			multiPlay: false
		});

		$.get('stat.php');
	});
})(window.jQuery);
