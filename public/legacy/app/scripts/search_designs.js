$(document).ready(function() {

	$('.searchS-des').keyup(function() {
		
		var ele = $(this);
		var root = ele.data('root');

		$.ajax({
			
			type: 'POST',
			
			url: root + '/app/functions/search/designs.php',

			data: 'search=' + ele.val(),

			success: function(data){
				$(ele).next().show();
				$(ele).next().find('.searchS-box').html(data);
			}
		});
	});
	
	$('.searchS-pan').on('click', '.searchS-item', function (){
		$(this).parents('.searchS-pan').prev().val($(this).data('id'));
		$(this).parents('.searchS-pan').hide();
    });
});