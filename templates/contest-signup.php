<?php
	
	global $post;

	$entry_fee_required = get_post_meta( $post->ID, 'entry_fee_required', true );
	$entry_fee_amount = money_format('%i', floatval(get_post_meta( $post->ID, 'entry_fee_amount', true)));
	$stripe_public_key = get_post_meta( $post->ID, 'stripe_public_key', true );

	$button_label = get_post_meta( $post->ID, 'button_label', true );
	$button_label = !empty($button_label) ? $button_label : 'Enter Contest';
	$message = (isset($_REQUEST['error'])) ? $_REQUEST['error'] : '';
?>

<style>

.form.signup ul { list-style: none; padding-left: 0; }
.form.signup ul li { margin-left: 0; }
.form.signup ul label {
	display: block;
}

.form.signup input[type="checkbox"] ~ label {
	display: inline;
}

.form.signup .error {
	border: 1px solid red;
}

.form.signup .required {
	color: inherit;
}

</style>

<div class="alert"><?php echo $message ?></div>

<form class="form signup" action="?action=contest_submit" method="POST" data-get-payment="<?php echo $entry_fee_required ?>">
	<ul>
		<?php 
			global $post;
			echo urldecode( get_post_meta( $post->ID, 'form_html', true ) );
		?>
	</ul>
	<input type="hidden" name="stripeToken" />
	<button id="submit-button"><?php echo $button_label ?></button>
</form>

<script src="https://checkout.stripe.com/v2/checkout.js"></script>
<script>
jQuery(document).ready(function($){

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
					key:         '<?php echo $stripe_public_key ?>',
					amount:      <?php echo $entry_fee_amount * 100 ?>,
					currency:    'usd',
					name:        '<?php echo $post->post_title ?>',
					description: '1 Submission for <?php echo $post->post_title ?>',
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

});
</script>

<!--<script
			src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
			data-key="<?php echo $stripe_key ?>"
			data-amount="<?php echo $entry_fee_amount * 100 ?>"
			data-name="<?php echo $post->post_title ?>"
			data-description="1 Submission ($<?php echo $entry_fee_amount ?>)"
			data-currency="usd"
			data-label="<?php echo $button_label ?>">
		</script>-->


<!--<form class="form signup">
	<h3>Personal Information</h3>
	<ul>
		<li>
			<label>First Name</label>
			<input type="text" name="first_name" placeholder="First Name" />
		</li>
		<li>
			<label>Last Name</label>
			<input type="text" name="last_name" placeholder="Last Name" />
		</li>
		<li>
			<label>City</label>
			<input type="text" name="city" placeholder="City" />
		</li>
		<li>
			<label>State</label>
			<input type="text" name="state" placeholder="State (eg. NC)" maxlength="2" />
		</li>
		<li>
			<label>Email</label>
			<input type="text" name="email" placeholder="Email" />
		</li>
		<li>
			<label>Birthdate</label>
			<input type="date" name="birthdate" placeholder="Birthdate" />
		</li>
		<li>
			<label>Short Bio</label>
			<textarea></textarea>
		</li>
		<li>
			<label>Twitter</label>
			<input type="text" name="" placeholder="@something" />
		</li>
	</ul>
	<h3>Entry Information</h3>
	<ul>
		<li>
			<label>Video URL</label>
			<input type="text" name="video_url" placeholder="" />
		</li>
		<li>
			<label>Talent Category</label>
			<select>
				<option value="">--</option>
				<option value="Singing">Singing</option>
				<option value="Dancing">Dancing</option>
				<option value="Acting">Acting</option>
			</select>
		</li>
		<li>
			<button>Register</button>
		</li>
	</ul>
</form>-->