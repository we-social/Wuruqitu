(function ($) {
	function stopPlaying($master) {
		$master
			.find('.ey-gif-player')
			.filter(function () {
				return $(this).data('playing');
			})
			.each(function (i, j) {
				stop($(j));
			});
	}
	function play($player) {
		if ($player.data('playing')) return;
		var $gif = $player.find('.ey-gif-pic');
		var $master = $player.closest('.ey-gif-master');
		$gif.attr('src', $master.data('fakeToReal')($gif.attr('src')));
		var $btn = $player.find('.ey-gif-btn');
		$btn.find('i').toggleClass('icon-play icon-stop');
		$player.data('playing', true);
	}
	function stop($player) {
		if (!$player.data('playing')) return;
		var $gif = $player.find('.ey-gif-pic');
		var $master = $player.closest('.ey-gif-master');
		$gif.attr('src', $master.data('realToFake')($gif.attr('src')));
		var $btn = $player.find('.ey-gif-btn');
		$btn.find('i').toggleClass('icon-play icon-stop');
		$player.data('playing', false);
	}
	function toggleGif() {
		var $btn = $(this);
		var $player = $btn.closest('.ey-gif-player');
		var $master = $player.closest('.ey-gif-master');
		if ($player.data('playing')) {
			// stop
			stop($player);
		} else {
			// play
			$master.data('multiPlay') || stopPlaying($master);
			play($player);
		}
	}
	$.fn.gifPlayer = function (opt) {
		return $(this).data(opt).addClass('ey-gif-master').delegate('.ey-gif-player .ey-gif-btn', 'click', toggleGif);
	};
})(window.jQuery);
