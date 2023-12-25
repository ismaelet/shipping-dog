$(window).ready(function () {
	//love button
	$('.love-btn[data-active="1"]').click(function () {
		//setup variabiles
		const self = $(this)
		const designID = self.data('id')
		const loved = self.data('loved')
		const hroot = self.data('hroot')
		const parameters = { designID: designID }

		if (loved) {
			api_call(hroot, 'DELETE', 'favorite', false, parameters).then((response) => {
				//console.log(response);
				self.removeClass('active')
				self.data('loved', 0)
			})
		} else {
			api_call(hroot, 'POST', 'favorite', false, parameters).then((response) => {
				//console.log(response);
				self.addClass('active')
				self.data('loved', 1)
			})
		}
	})
})
