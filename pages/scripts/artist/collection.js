$(document).ready(function() {

	//title
	$('#name-inp').keyup(function() {

		var self = $(this);
		var name = self.val();
		var id = self.data('id');

		if (name) {

			$.ajax({
				method: 'POST',
				url: '../app/modules/ajax_collection_name.php',
				data: {id: id, name: name},

			}).done(function() {

				self.next().show();
				
			});	

		} else {

			self.next().hide();
		}
	})
});