$('document').ready(function() {

	$('.nav-cbx').prop('checked', false);
	$('.nav-cbx.active').prop('checked', true);

	setTimeout(function() {$('.no-trans').removeClass('no-trans');}, 500);

	//setup
	$('.nav-cbx:checked').each(function() {
		var ele = $(this);
		expand(ele);
	});

	//navigation
	$('.nav-cbx').click(function() {
		$('.no-trans').removeClass('no-trans');
		var ele = $(this);
		expand(ele);
	});

	//expand ddown
	function expand(ele) {

		$('.nav-ddown').addClass('closed');
		$('.nav-cbx').not(ele).prop('checked', false);

		if (ele.prop('checked')) {
			ele.parent().find('.nav-ddown').removeClass('closed');
		}
	}

	//open mobile
	$('#nav-open').click(function() {
		$('#nav').toggleClass('open');
		$('#content').toggleClass('over-hide');
	});
});