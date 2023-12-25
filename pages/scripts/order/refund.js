$(document).ready(function() {

	$('.item-row .grid').click(function() {

		var cbx = $(this).find('.cbx');

		$(this).parent().toggleClass('active');
		cbx.prop('checked', !cbx.prop('checked'));

		$(this).parent().find('.action-box').toggleClass('hidden');
		
	});

	/*$('.item-cbx').click(function() {
		$('.inp_err').hide();
		
		if ($(this).prop('checked')) {$(this).closest('.item-tbl').next().show();}
		else {$(this).closest('.item-tbl').next().hide();}
	});*/
});