<?php
	$form_html = get_post_meta( $post->ID, 'form_html', true );
?>

<style>
	
	a { cursor: pointer; }

	#input-templates, .input-creator { display: none; }

	.input-menu {
		display: block;
		padding: 15px 0;
		border-bottom: 1px solid #eee;
	}

	.input-creator {
		float: left;
	}

	.form-preview {
		float: right;
		max-width: 50%;
	}

	.form-preview li {
		padding: 10px;
		cursor: pointer;
	}

	.form-preview li label {
		display: block;
	}

	.active-field {
		padding: 9px;
		border: 1px dashed #CCC;
		position: relative;
	}

	.active-field .delete {
		right: 20px;
		position: absolute;
	}

	.dropdown-creator .template .delete-option { 
		display: none;
	}

</style>

<div id="form-creator">
	<div class="input-menu">
		<select class="input-type">
			<option value="text">Text</option>
			<option value="date">Date</option>
			<option value="textarea">Text Area</option>
			<option value="dropdown">Dropdown</option>
			<option value="checkbox">Checkbox</option>
			<option value="title">Title</option>
		</select>
		<a class="add-input">Create Field</a>
	</div>

	<ul class="form-preview">
		<h2>Preview Form</h2>
		<div class="html">
			<?php echo urldecode($form_html); ?>
		</div>
	</ul>

	<div class="input-creator">
		<h2>Field Properties</h2>
		<div class="for-text for-textarea for-dropdown for-checkbox">
			<p><strong>Label Text</strong></p>
			<p><input id="label-name-change" type="text" /></p>
		</div>
		<div class="for-text for-textarea for-dropdown for-checkbox">
			<p><strong>Field Name</strong></p>
			<p><input id="field-name-change" type="text" /></p>
		</div>
		<div class="for-text for-textarea">
			<p><strong>Placeholder Text</strong></p>
			<p><input id="placeholder-change" type="text" /></p>
		</div>
		<div class="for-text for-textarea">
			<p><strong>Maximum Length</strong></p>
			<p><input id="max-length-change" type="text" /></p>
		</div>
		<div class="for-text for-textarea for-dropdown for-checkbox">
			<p><strong>Required Field?</strong></p>
			<p><input id="require-field" type="checkbox" /></p>
		</div>
		<div class="for-dropdown">
			<h2>Dropdown Options</h2>
			<table class="dropdown-creator">
				<thead>
					<tr>
						<th>Label</th>
						<th>Value</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr class="template">
						<td><input class="option-label" type="text" /></td>
						<td><input class="option-value" type="text" /></td>
						<td><a class="add-option">Add</a></td>
						<td><a class="delete-option">Delete</a></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="clear"></div>
	<input id="form-html" type="hidden" name="form_html" value="<?php echo $form_html ?>" />
</div>



<script>
(function($){
	
	$(document).ready(function(){
		
		function update_form_html() {

			var form_html = $('.form-preview .html').clone();

			form_html.find('a.delete').remove();
			form_html.find('.active-field').removeClass('active-field');

			//console.log( 'SITE: ' + form_html.html() );

			$('#form-html').val( escape( form_html.html() ) );

		}

		$('.form-preview .html').sortable({

			stop: function( event, ui ) {
				update_form_html();
			}

		});

		$('.add-input').on('click', function(e){

			var input_type = $(this).prev().val();
			var input_type_fields = '.for-' + input_type;

			$('.input-creator input').val('');
			$('.active-field').find('.delete').remove();
			$('.active-field').removeClass('active-field');
			$('#label-name-change,#placeholder-change').val('');

			$('.input-creator > div').hide();
			$('.input-creator').find(input_type_fields).show();

			if ( input_type == 'text' ) {

				$('.form-preview .html').append('<li class="active-field text"><label>Text</label><input type="text" name="" placeholder="" /></li>');

			} else if ( input_type == 'date' ) {

				$('.form-preview .html').append('<li class="active-field date"><label>Date</label><input type="date" name="" placeholder="" /></li>');

			} else if ( input_type == 'textarea' ) {

				$('.form-preview .html').append('<li class="active-field textarea"><label>Text</label><textarea name="" placeholder=""></textarea></li>');

			} else if ( input_type == 'title' ) {

				$('.form-preview .html').append('<li class="active-field title"><h3>Title</h3></li>');

			} else if ( input_type == 'dropdown' ) {

				$('.form-preview .html').append('<li class="active-field dropdown"><label>Text</label><select name=""><option value=""></option></select></li>');				

			} else if ( input_type == 'checkbox' ) {

				$('.form-preview .html').append('<li class="active-field checkbox"><input type="checkbox" name="" /><label>Message</label></li>');				

			}

			$('.active-field').append('<a class="delete">Delete</a>');
			$('.form-preview, .input-creator').show();
			
			update_form_html();
		});

		$('body').on("click", ".form-preview .html > li:not(.active-field)", function() {

			// Set active field
			$('.active-field').find('.delete').remove();
			$('.active-field').removeClass('active-field');
			$(this).addClass('active-field');

			// Update variables
			var brackets = $('.active-field').find('input,textarea,select').attr('name').match(/\[(.*?)\]/);

			if (brackets) {
				var field_name = brackets[1];
			} else {
				var field_name = '';
			}

			
			$('#label-name-change').val( $('.active-field').find('label').text() );
			$('#field-name-change').val( field_name );
			$('#placeholder-change').val( $('.active-field').find('input,textarea').attr('placeholder') );
			$('#max-length-change').val( $('.active-field').find('input').attr('maxlength') );
			$('.active-field').append('<a class="delete">Delete</a>');
			$('.input-creator').show();

			if ( $(this).hasClass('dropdown') ) {

				$('.dropdown-creator tr:not(.template)').remove();

				$(this).find('option').each(function(i){

					if ( i == 0 ) {
						
						$('.dropdown-creator').find('.template').find('.option-label').val( $(this).text() );
						$('.dropdown-creator').find('.template').find('.option-value').val( $(this).attr('value') );

					} else {

						var option_template = $('.dropdown-creator').find('.template').clone();
						option_template.removeClass('template');
						option_template.find('input').val('');

						option_template.find('.option-label').val( $(this).text() );
						option_template.find('.option-value').val( $(this).attr('value') );

						$('.dropdown-creator tbody').append( option_template );

					}
					
				});
			}

			update_form_html();

		});

		$('body').on("click", ".active-field .delete", function() {

			$(this).closest('.active-field').remove();
			update_form_html();

		});

		$('body').on("click", ".dropdown-creator .add-option", function() {

			var option_template = $(this).closest('.dropdown-creator').find('.template').clone();
			
			option_template.removeClass('template');
			option_template.find('input').val('');

			$(this).closest('tbody').append(option_template);

			$('.active-field').find('select').append('<option></option>');
			$('.active-field select option:last-child()').attr('selected','selected');

			update_form_html();

		});

		$('body').on("click", ".dropdown-creator .delete-option", function() {

			var this_value = $(this).closest('tr').find('.option-value').val();

			$('.active-field select').html('');

			$(this).closest('tbody').find('tr').each(function(i){
				
				var current_value = $(this).closest('tr').find('.option-value').val();

				if ( current_value != this_value ) {
				
					var option_label = $(this).find('.option-label').val();
					var option_value = $(this).find('.option-value').val();

					$('.active-field select').append('<option value="' + option_value + '">' + option_label + '</option>');
				
				}

			});

			$('.active-field select option:last-child()').attr('selected','selected');
			$(this).closest('tr').remove();
			update_form_html();

		});

		$('#label-name-change').on('keyup', function(e){

			$('.active-field').find('label').text( $(this).val() );
			update_form_html();

		});

		$('#placeholder-change').on('keyup', function(e){

			$('.active-field').find('input,textarea').attr( 'placeholder', $(this).val() );
			update_form_html();

		});

		$('#max-length-change').on('keyup', function(e){

			$('.active-field').find('input').attr( 'maxlength', $(this).val() );
			update_form_html();

		});

		$('#field-name-change').on('keyup', function(e){

			$('.active-field').find('input,textarea,select').attr( 'name', 'fields[' + $(this).val() + ']' );
			update_form_html();

		});

		$('#require-field').on('click', function(e){

			if( $(this).is(':checked') ){
				$('.active-field').find('input,textarea,select').addClass( 'required' );
			} else {
				$('.active-field').find('input,textarea,select').removeClass( 'required' );
			}
			update_form_html();

		});

		$('body').on("keyup", ".option-label", function() {

			var index = $('.option-label').index( this ) + 1; 
			$('.active-field option:nth-child(' + index + ')').text( $(this).val() ).attr('selected','selected');
			update_form_html();

		});

		$('body').on("keyup", ".option-value", function() {

			var index = $('.option-value').index( this ) + 1;
			$('.active-field option:nth-child(' + index + ')').attr( 'value', $(this).val() ).attr('selected','selected');
			update_form_html();

		});

	});

})(jQuery);
</script>