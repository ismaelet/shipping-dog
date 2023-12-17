const changeNameSelector = '.js-change-name'
const changeNameConfirmationSelector = '.js-change-name-confirmation'

document.addEventListener('DOMContentLoaded', function () {
	const changeName = document.querySelector(changeNameSelector)
	const changeNameConfirmation = document.querySelector(changeNameConfirmationSelector)

	const designId = document.querySelector('#body').dataset.designId

	var timer

	changeName.addEventListener('keyup', () => {
		const name = changeName.value

		if (name) {
			call_class_method('Ecommerce\\Design', 'updateName', name, designId)

			changeNameConfirmation.classList.add('active')

			setTimeout(function () {
				changeNameConfirmation.classList.remove('active')
			}, 1000)
		}
	})

	// hex color
	$('#color-bgr-box').click(function () {
		$('#color-bgr-inp').click()
	})

	$('#color-bgr-inp').on('change', function () {
		var hex = $(this).val()

		$('#color-bgr').css('background-color', hex)
		$('#color-bgr-hex').html(hex)
	})

	// available colors
	$('body').on('change', '.colorsA .cbx', function () {
		var self = $(this)

		var color = self.data('color')

		var parent = self.parents('.colorsA')
		var sibling_parent = parent.parents('.colors-pan').find('.colorsD')
		var sibling = sibling_parent.find('.colorD-' + color)

		// prevent from not having available colors
		if (!parent.find('.cbx:checked').length) self.prop('checked', true)

		// activate possible default colors
		if (self.prop('checked')) {
			sibling.parent().addClass('active')
		} else {
			sibling.prop('checked', false)
			sibling.parent().removeClass('active')
		}

		// prevent form not having a default color
		if (!sibling_parent.find('.cbx:checked').length) sibling_parent.find('.active .cbx').first().prop('checked', true).change()
	})

	// default colors
	$('body').on('change', '.colorsD .cbx', function () {
		var self = $(this)
		var parent = self.parents('.colorsD')

		parent.find('.cbx').prop('checked', false)
		self.prop('checked', true)
	})

	$('body').on('change', '.item-panel .colorsD .cbx', function () {
		const self = $(this)
		const panel = self.parents('.item-panel')
		const item = self.parents('.item')
		const colorId = self.data('color-id')
		const dark = parseInt(self.data('dark'))

		item.find('.image').each(async function () {
			const section = $(this).parents('.section')
			const clothing = section.find('.clothing')
			const typeId = clothing.data('type-id')
			const details = section.find('.details')
			const positionId = section.data('image-position')

			let parameters = {
				typeId: typeId,
				colorId: colorId,
				positionId: positionId,
			}

			const fileUrls = await AJAX('product-types-images', parameters, 'json', true)

			clothingImageUrl = fileUrls['clothing-full-url']
			detailsImageUrl = fileUrls['details-full-url']

			clothing.css('background-image', 'url(' + clothingImageUrl + ')')
			details.css('background-image', 'url(' + detailsImageUrl + ')')
		})

		if (dark) {
			panel.find('.design.light').removeClass('active')
			panel.find('.design.dark').addClass('active')
		} else {
			panel.find('.design.light').addClass('active')
			panel.find('.design.dark').removeClass('active')
		}
	})

	// item panel
	$('body').on('click', '.item-panel-open', function () {
		const box = $(this).parents('.item')

		if (box.hasClass('active')) {
			$('body').addClass('fixed')
			box.find('.item-panel').addClass('active')
		}
	})

	$('body').on('click', '.item-panel-close', function () {
		$('body').removeClass('fixed')
		$(this).parents('.item-panel').removeClass('active')
	})

	$('body').on('click', '.item-panel .tab', function () {
		const self = $(this)
		const id = self.data('id')
		const panel = self.parents('.item-panel')

		panel.find('.tab').removeClass('active')
		self.addClass('active')

		panel.find('.section').removeClass('active')
		panel.find('.section' + id).addClass('active')
	})

	// mockup position and size
	$('body').on('change', '.mockup-size', function () {
		const self = $(this)
		const product = self.parents('.item')
		const section = self.parents('.section')
		const imagePosition = section.data('image-position')
		const design = product.find('[data-image-position="' + imagePosition + '"] .design')
		const size = self.val()
		const land = self.data('land')

		if (land === 1) {
			design.css('background-size', size + '% ' + 'auto')
		} else {
			design.css('background-size', 'auto ' + size + '%')
		}

		self.next().html(size + ' %')
	})

	$('body').on('change', '.mockup-y', function () {
		const self = $(this)
		const product = self.parents('.item')
		const section = self.parents('.section')
		const imagePosition = section.data('image-position')
		const design = product.find('[data-image-position="' + imagePosition + '"] .design')
		const x = section.find('.mockup-x').val()
		const y = self.val()

		design.css('background-position', x + '% ' + y + '%')

		self.next().html(y + ' %')
	})

	$('body').on('change', '.mockup-x', function () {
		const self = $(this)
		const product = self.parents('.item')
		const section = self.parents('.section')
		const imagePosition = section.data('image-position')
		const design = product.find('[data-image-position="' + imagePosition + '"] .design')
		const x = self.val()
		const y = section.find('.mockup-y').val()

		design.css('background-position', x + '% ' + y + '%')

		self.next().html(x + ' %')
	})

	$('body').on('change', '.mockup-inp input', function () {
		const designBox = $(this).parents('.item-panel').find('.design-box')

		clearTimeout(timer)

		designBox.addClass('active')

		timer = setTimeout(function () {
			designBox.removeClass('active')
		}, 2000)
	})

	// position toggler
	$('body').on('click', '.item-panel .toggler__button', async function () {
		const section = $(this).parents('.section')
		const sectionColors = section.parent().find('.section.colors')
		const enabled = section.data('enabled') ? 0 : 1
		const id = section.parents('.item-panel').data('id')

		var positions = []

		section.data('enabled', enabled)
		section.find('.js-enabled').data('enabled', enabled)
		section.toggleClass('disabled')

		if (enabled) {
			section.find('input').prop('disabled', false)
		} else {
			section.find('input').prop('disabled', true)
		}

		section
			.parent()
			.find('.section.positions .js-enabled')
			.each(function () {
				const self = $(this)
				const position = self.data('position')
				const enabled = self.data('enabled')

				if (enabled) {
					positions.push(position)
				}
			})

		if (positions.length) {
			await call_class_method('Ecommerce\\Product', 'updatePositionIds', '[' + positions.join(',') + ']', id)
		}

		if (positions == '') {
			section.parents('.item').removeClass('active')
			sectionColors.addClass('disabled')
			sectionColors.find('input').prop('disabled', true)
		} else {
			section.parents('.item').addClass('active')
			sectionColors.removeClass('disabled')
			sectionColors.find('input').prop('disabled', false)
		}
	})
})

$(window).on('load', async function () {
	const box = $('.async-items')
	const designId = box.data('design-id')

	let html = await AJAX('edit-item-list', { designId: designId }, 'text', false)

	box.html(html)
	$('#items-load-bgr').fadeOut()
})
