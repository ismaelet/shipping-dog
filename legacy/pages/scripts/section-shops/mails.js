$(document).ready(function() {

	$('.copy').click(function() {

		var $temp = $("<input>");
		var text = $(this).prev().html().replace(/<br *\/?>/gi, ' ').trim();
	
		$('body').append($temp);

		$temp.val(text).select();
		
		document.execCommand('copy');
		
		$temp.remove();

	});

});