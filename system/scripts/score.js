$(document).ready(function() {

	//Click
	$('.scor-ele').click(function() {
		var scor = parseInt($(this).data('scor'));
		var group = $(this).parents('.scor-box');

		group.find('.scor-imp').val(scor);
	});

	//Hover
	$('.scor-ele').hover(function() {
		var scor = parseInt($(this).data('scor'));

		var group = $(this).parents('.scor-box');

		score(group, scor);

	}, function() {
		var group = $(this).parents('.scor-box');
		var scor = parseInt(group.find('.scor-imp').val());

		score(group, scor);
	});

	//Score
	function score(group, scor) {
		$(group).find('.scor-ele').each(function() {
			var ele_scor = parseInt($(this).data('scor'));

			if (ele_scor <= scor) {$(this).addClass('active');}
			else {$(this).removeClass('active');}
		});
	}
});