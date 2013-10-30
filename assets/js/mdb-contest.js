(function($){

	$('#submit-button').on('click', function(e){
		
		e.preventDefault();

		var form = $('.form.signup');
		var errors = false;
		
		$('.alert').html('');

		$(form).find('.required').each(function(){

			if ( $(this).val() == '' ) {
				$(this).addClass('error');
				errors = true;

				$('.alert').html('Some required fields are missing');
			}

		});

		if (!errors) {

			// Show payment screen from Stripe if required
			if ( $(form).data('get-payment') && ( $(form).find('input[name="stripeToken"]').val() == '' ) ) {

				var token = function(res) {
					$(form).find('input[name="stripeToken"]').val(res.id);
					$(form).submit();
				};

				StripeCheckout.open({
					key:         $(this).data('stripe-key'),
					amount:      $(this).data('amount') * 100,
					currency:    'usd',
					name:        $(this).data('name'),
					description: $(this).data('description'),
					token:       token
				});

			// Submit the entry
			} else {

				$(form).submit();

			}

		}

	});

	// Remove errors when field has a value
	$('body').on('keyup change', '.error', function() {

		if ( $(this).val() != '' ) $(this).removeClass('error');

	});

})(jQuery);