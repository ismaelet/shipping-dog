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

	//follow button
	$('.follow-btn[data-active="1"]').click(function () {
		//setup variabiles
		const self = $(this)
		const sellerId = self.data('id')
		const followed = self.data('followed')
		const hroot = self.data('hroot')
		const parameters = { sellerId: sellerId }

		if (followed) {
			api_call(hroot, 'DELETE', 'follower', false, parameters).then((response) => {
				//console.log(response);
				self.html('segui')
				self.data('followed', 0)
			})
		} else {
			api_call(hroot, 'POST', 'follower', false, parameters).then((response) => {
				//console.log(response);
				self.html('seguendo')
				self.data('followed', 1)
			})
		}
	})

	$('.follow-btn').hover(
		function () {
			if (parseInt($(this).data('followed'))) {
				$(this).html('non seguire')
			} else {
				$(this).html('+ segui')
			}
		},
		function () {
			if (parseInt($(this).data('followed'))) {
				$(this).html('seguendo')
			} else {
				$(this).html('segui')
			}
		}
	)
})
