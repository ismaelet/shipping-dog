$(document).ready(function() {
	
	$('.searchS-store').keyup(function(){
		
		var element = this;
		
		$.ajax({
			type: "POST",
			url: "/system/general/php/search/shops.php",
			data:'srch='+$(this).val(),
			success: function(data){
				$(element).next().show();
				$(element).next().find('.searchS-box').html(data);
			}
		});
	});
	
	$('.searchS-pan').on('click', '.searchS-item', function (){
		$(this).parents('.searchS-pan').prev().val($(this).data('id'));
		$(this).parents('.searchS-pan').hide();
    });

    $('.searcher').mouseleave(function() {
    	$(this).find('.searchS-pan').hide();
    });
});