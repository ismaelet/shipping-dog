$(window).ready(function() {


	$('.love-btn.working').click(function() {

		//setup variabiles
		const self = $(this);
		const id = self.data('id');
		const loved = self.data('loved');
		const root = self.data('root');
		const hroot = self.data('hroot');
		const url = hroot + '/app/modules/ajax/love-button.php';

		//run
		$.ajax({

			method: 'POST',
			url: url,
			data: {id: id, loved: loved, root: root},

			success: function(data) {}

		}).done(function() {

			if (loved) {
				self.removeClass('active');
				self.data('loved', 0);
			} else {
				self.addClass('active');
				self.data('loved', 1);
			}

		});
		
	});
});