$(document).ready(function() {

	function fix_pos(ele) {

		var offset;
		
		var widMax = $(window).width();

		var right = ele.offset().left + ele.outerWidth();
	
		offset = Math.round(right - widMax + 16, 0);

		if (offset > 0) {
			ele.css('left', -offset + 'px');
		}
	}

	$('.tip-box').hover(

		function() { 

			var ele = $(this).find('.tip-icon-txt');

			ele.addClass('active');
			fix_pos(ele);

		}, function () {

			$(this).find('.tip-icon-txt').removeClass('active');
	});

	$('.tip-icon').click(function() {
		$(this).next().addClass('active');
	});

	$('.tip-icon-txt').click(function() {
		$(this).removeClass('active');
	});
});