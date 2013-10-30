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
	<button id="submit-button" data-stripe-key="<?php echo $stripe_public_key ?>" data-amount="<?php echo $entry_fee_amount ?>" data-name="<?php echo $post->post_title ?>" data-description="1 Submission for <?php echo $post->post_title ?>"><?php echo $button_label ?></button>
</form>

<script src="https://checkout.stripe.com/v2/checkout.js"></script>