define(function (require) {

	var elgg = require('elgg');
	var $ = require('jquery');
	var Ajax = require('elgg/Ajax');
	$(document).on('change', '.campaigns-checkbox-register [type="checkbox"]', function (e) {
		if ($(this).prop('checked')) {
			$('.campaigns-checkout-register').slideDown();
			$('[name="username"]').prop('required', true);
			$('[name="password"]').prop('required', true);
		} else {
			$('.campaigns-checkout-register').slideUp();
			$('[name="username"]').prop('required', false);
			$('[name="password"]').prop('required', false);
		}
	});
	$(document).on('blur', '[name="email"]', function (e) {

		if (elgg.is_logged_in()) {
			return;
		}

		var ajax = new Ajax(false);
		var email = $(this).val();
		ajax.action('campaigns/is_registered', {
			data: {
				email: email
			}
		}).done(function (output) {
			if (output.is_registered) {
				$('.campaigns-checkbox-register').hide()
						.find('input[type="checkbox"]').prop('checked', false).trigger('change');
			} else {
				$('.campaigns-checkbox-register').show();
			}
		});
	});
});


