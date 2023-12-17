$(window).ready(function() {


	$('.follow-btn.working').click(function() {

		//setup variabiles
		const self = $(this);
		const id = self.data('id');
		const followed = self.data('followed');
		const root = self.data('root');
		const hroot = self.data('hroot');
		const url = hroot + '/app/elements/follow_button/php/element_follow_ajax.php';

		//run
		$.ajax({

			method: 'POST',
			url: url,
			data: {id: id, followed: followed, root: root},

			success: function(data) {}

		}).done(function() {

			if (followed) {
				self.html('segui');
				self.data('followed', 0);
			} else {
				self.html('seguendo');
				self.data('followed', 1);
			}

		});		
	});

	$('.follow-btn').hover(function() {
		if (parseInt($(this).data('followed'))) {$(this).html('non seguire');}
		else {$(this).html('+ segui');}
	}, function() {
		if (parseInt($(this).data('followed'))) {$(this).html('seguendo');}
		else {$(this).html('segui');}
	});

});