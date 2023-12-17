$(document).ready(function() {

	update();
	$(window).resize(function() {update();});

	function update() {

		$('.sett-slid').each(function() {
			var dot = $(this).find('.sett-slid-focus');

			var left = $(this).find('.sett-slid-dot.active').position().left;

			dot.css('left', left + 'px').show();
		});
	}

	$('.sett-slid-dot').click(function() {

		var self = $(this);
		var id = self.data('id');
		var box = self.parents('.sett-slid');
		var dot = box.find('.sett-slid-focus');

		dot.removeClass('no-trans').removeClass('focus1').removeClass('focus2');
		dot.addClass('focus' + id);

		var left = self.position().left;

		dot.css('left', left + 'px');

		box.find('input').prop('checked', false);
		self.find('input').prop('checked', true);
	});
});