<?php
	
	global $post;

	$entry_fee_required = get_post_meta( $post->ID, 'entry_fee_required', true );
	$entry_fee_amount = money_format('%i', get_post_meta( $post->ID, 'entry_fee_amount', true));
	$stripe_key = get_post_meta( $post->ID, 'stripe_key', true );
	$button_label = get_post_meta( $post->ID, 'button_label', true );

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

</style>


<form class="form signup" action="?action=contest_submit" method="POST">
	<ul>
		<?php 
			global $post;
			echo urldecode( get_post_meta( $post->ID, 'form_html', true ) );
		?>
	</ul>

	<?php if ($entry_fee_required): ?>
		
		<script
			src="https://checkout.stripe.com/v2/checkout.js" class="stripe-button"
			data-key="<?php echo $stripe_key ?>"
			data-amount="<?php echo $entry_fee_amount * 100 ?>"
			data-name="<?php echo $post->post_title ?>"
			data-description="1 Submission ($<?php echo $entry_fee_amount ?>)"
			data-currency="usd"
			data-label="<?php echo $button_label ?>">
		</script>

	<?php else: ?>
		
		<input type="submit" value="Enter Contest" />

	<?php endif; ?>
</form>



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