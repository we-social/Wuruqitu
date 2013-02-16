(function () {
	$(function () {
		var $gallery = $('#my_gallery');
		$gallery.gifPlayer({
			fakeToReal: function (s) {
				// 静态图src到动态图src转换
				return s.replace(/_\.gif$/, '.gif');
			},
			realToFake: function (s) {
				// 动态图src到静态图src转换
				return s.replace(/\.gif$/, '_.gif');
			},
			multiPlay: false // 是否允许同时播放
		});
	});
})(window.jQuery);
