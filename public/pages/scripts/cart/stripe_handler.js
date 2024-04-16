//SET UP ELEMENTS
const debug = false
const hroot = $('main form').data('hroot')

var stripe = Stringipe('pk_live_az7uYEu6NVoM7BEsKAHNhu3O00V6jkCcbm')
var elements = stripe.elements()
var cart_id, user_id, clientSecret, addr_name

var style = {
	base: {
		color: '#32325d',
		fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
		fontSmoothing: 'antialiased',
		fontSize: '16px',

		'::placeholder': {
			color: '#aab7c4',
		},
	},
	invalid: {
		color: '#fa755a',
		iconcolor: '#fa755a',
	},
}

var card = elements.create('card', { hidePostalCode: true, style: style })
card.mount('#card-element')

//GET SECRET CODE
var response = fetch(hroot + '/stripe_handler')
	.then(function (response) {
		if (debug) {
			return response.text()
		} else {
			return response.json()
		}
	})
	.then(function (responseJson) {
		if (debug) {
			console.log(responseJson)
		} else {
			var amount = responseJson.amount.toFixed(2)

			cart_id = responseJson.cart_id
			user_id = responseJson.user_id
			clientSecret = responseJson.client_secret
			addr_name = responseJson.addr_name

			$('#amnt').html(amount)
			$('#submit').prop('disabled', false)
		}
	})

//HANDLE ERRORS
card.addEventListener('change', ({ error }) => {
	const displayError = document.getElementById('card-errors')
	if (error) {
		displayError.textContent = error.message
	} else {
		displayError.textContent = ''
	}
})

//PROCESS PAYMENT
var submitButton = document.getElementById('submit')

submitButton.addEventListener('click', function (ev) {
	$('#submit').hide()
	$('.load-icon').show()

	stripe
		.confirmCardPayment(clientSecret, {
			payment_method: {
				card: card,
				billing_details: {
					name: addr_name,
				},
			},
		})

		.then(function (result) {
			if (result.error) {
				$('#card-errors').html('error')
				$('.load-icon').hide()
			} else {
				if (result.paymentIntent.status === 'succeeded') {
					$.post(hroot + '/listeners/stripe.php', { cart_id: cart_id, user_id: user_id }, function (res) {
						window.location.href = hroot + '/cart/success'
					})
				}
			}
		})
})
