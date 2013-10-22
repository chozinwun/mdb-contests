<style>

.form.signup ul { list-style: none; padding-left: 0; }

</style>

<h2>Contest Signup</h2>
<form class="form signup">
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
</form>