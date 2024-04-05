$(document).ready(function(){

	var pos = 1;
	var winBot = 0;
	var pause = false;

	//on window resize
	scroll();
	$(window).scroll(function() {scroll();});
	$(window).on('resize', function(){scroll();});

	function scroll() {
		if (!pause) {
			winBot = $(window).scrollTop() + ($(window).height() / 3);

			var max = parseInt($('.side-scrl').data('max'));
			var posMax = 0;

			$('.scrl-anch').each(function() {
				var ele = $(this);
				var posAnch = parseInt(ele.data('pos'));

				if (visible(ele) && posAnch > posMax) {posMax = posAnch;}
			})

			if (pos !== posMax) {
				pos = posMax;
				scrl_move(pos, max);
			}
		}
	}

	function visible(ele) {		
		var elePos = ele.offset().top;
		return (elePos <= winBot);
	}

	//on click
	$('.side-scrl-box').click(function() {
		pause = true;
		var pos = parseInt($(this).data('pos'));
		var max = parseInt($(this).parents('.side-scrl').data('max'));

		scrl_move(pos, max);
	});

	//move pointer
	function scrl_move(pos, max) {		
		var focus = $('.side-scrl-focus');

		var top = (pos - 1) * 100 / max;

		focus.addClass('side-scrl-shrink');

		focus.css('top', 'calc('+ top + '% + 10px)');

		$('.side-scrl-box').removeClass('active');
		$('#side-scrl' + pos).addClass('active');
	}

	$('.side-scrl-focus').on('animationend', function() {
		pause = false;
		$(this).removeClass('side-scrl-shrink');
    });
});