<?php
	/**
	* Plugin Name: MDB Contests
	* Description: Creates a contest & contestant custom post type
	* Version: 1.0
	* Author: Marcus Battle
	* Author URI: http://marcusbattle.com
	*/

	function mdb_contests_init() {

		// Contest Post Type
		$contest_labels = array(
			'name'               => _x( 'Contests', 'post type general name' ),
			'singular_name'      => _x( 'Contest', 'post type singular name' ),
			'add_new'            => _x( 'Add Contest', 'Contest' ),
			'add_new_item'       => __( 'Add New Contest' ),
			'edit_item'          => __( 'Edit Contest' ),
			'new_item'           => __( 'New Contest' ),
			'all_items'          => __( 'All Contests' ),
			'view_item'          => __( 'View Contest' ),
			'search_items'       => __( 'Search Contests' ),
			'not_found'          => __( 'No Contests found' ),
			'not_found_in_trash' => __( 'No Contests found in the Trash' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Contests',
			'can_export'			=> true
		);
		
		$contest_args = array(
			'labels'        => $contest_labels,
			'description'   => 'Holds data for contests',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor' ),
			'has_archive'   => true,
			'show_in_nav_menus' => true,
			'rewrite' 			=> array( 'slug' => 'contests' ),
			'capability_type' => 'page',
			'publicly_queryable' => true
		);

		// Contestant Post Type
		$contestant_labels = array(
			'name'               => _x( 'Contestants', 'post type general name' ),
			'singular_name'      => _x( 'Contestant', 'post type singular name' ),
			'add_new'            => _x( 'Add Contestant', 'Contestant' ),
			'add_new_item'       => __( 'Add New Contestant' ),
			'edit_item'          => __( 'Edit Contestant' ),
			'new_item'           => __( 'New Contestant' ),
			'all_items'          => __( 'All Contestants' ),
			'view_item'          => __( 'View Contestant' ),
			'search_items'       => __( 'Search Contestants' ),
			'not_found'          => __( 'No Contestants found' ),
			'not_found_in_trash' => __( 'No Contestants found in the Trash' ), 
			'parent_item_colon'  => '',
			'menu_name'          => 'Contestants',
			'can_export'			=> true
		);
		
		$contestant_args = array(
			'labels'        => $contestant_labels,
			'description'   => 'Holds data for contestants',
			'public'        => true,
			'menu_position' => 5,
			'supports'      => array( 'title' ),
			'has_archive'   => true,
			'show_in_nav_menus' => true,
			'rewrite' 			=> array( 'slug' => 'contestants' ),
			'capability_type' => 'post',
			'publicly_queryable' => true
		);
		
		register_post_type( 'contest', $contest_args );		
		register_post_type( 'contestant', $contestant_args );
	}

	function mdb_contests_admin_init() {

		wp_register_script( 'mdb-contest-admin-js', plugins_url( '/assets/js/mdb-contest-admin.js', __FILE__ ) );
		wp_enqueue_script( 'mdb-contest-admin-js' );
	}

	function mdb_contest_meta_boxes() {
		add_meta_box( 'mdb-contest-confirmation', 'Confirmation Message', 'mdb_contest_confirmation_box', 'contest', 'normal', 'default' );
		add_meta_box( 'mdb-contest-form', 'Entry Form', 'mdb_contest_entry_form_box', 'contest', 'normal', 'default' );

		add_meta_box( 'mdb-contest', 'Contest Attributes', 'mdb_contest_attributes_box', 'contest', 'side', 'default' );
		add_meta_box( 'mdb-contest-fee', 'Entry Fee', 'mdb_contest_entry_fee_box', 'contest', 'side', 'default' );

		add_meta_box( 'mdb-contestant', 'Contestant Attributes', 'mdb_contestant_attributes_box', 'contestant', 'normal', 'default' );
	}

	function mdb_contest_confirmation_box( $post ) {

		$message = get_post_meta( $post->ID, 'confirmation_message', true );

		echo "<textarea name=\"confirmation_message\" style=\"width: 100%\">$message</textarea>";

	}

	function mdb_contest_entry_form_box( $post ) {

		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'templates/contest-form-creator.php' );
		ob_flush();

	}

	function mdb_contest_attributes_box( $post ) {

		$start_date = get_post_meta( $post->ID, 'contest_start_date', true );
		$end_date = get_post_meta( $post->ID, 'contest_end_date', true );
		$start_time = get_post_meta( $post->ID, 'contest_start_time', true );
		$end_time = get_post_meta( $post->ID, 'contest_end_time', true );

		echo "<p><strong>Start Date</strong></p>";
		echo "<input type=\"date\" name=\"contest_start_date\" value=\"$start_date\" />";	
		echo "<p><strong>End Date</strong></p>";
		echo "<input type=\"date\" name=\"contest_end_date\" value=\"$end_date\" />";

		echo "<p><strong>Start Time</strong></p>";
		echo "<input type=\"time\" name=\"contest_start_time\" value=\"$start_time\" />";
		echo "<p><strong>End Time</strong></p>";
		echo "<input type=\"time\" name=\"contest_end_time\" value=\"$end_time\" />";
	}

	function mdb_contest_entry_fee_box( $post ) {

		$require_entry = get_post_meta( $post->ID, 'entry_fee_required', true );
		$entry_amount = get_post_meta( $post->ID, 'entry_fee_amount', true );
		$stripe_private_key = get_post_meta( $post->ID, 'stripe_private_key', true );
		$stripe_public_key = get_post_meta( $post->ID, 'stripe_public_key', true );
		$button_label = get_post_meta( $post->ID, 'button_label', true );

		echo "<p><strong>Require Entry Fee?</strong></p>";
		echo "<select name=\"entry_fee_required\" data-id=\"$require_entry\"><option>--</option><option value=\"1\">Yes</option><option value=\"0\">No</option></select>";

		echo "<p><strong>Entry Fee Amount</strong></p>";
		echo "<input name=\"entry_fee_amount\" value=\"$entry_amount\" />";

		echo "<p><strong>Stripe Secret Key</strong></p>";
		echo "<input name=\"stripe_private_key\" value=\"$stripe_private_key\" />";

		echo "<p><strong>Stripe Public Key</strong></p>";
		echo "<input name=\"stripe_public_key\" value=\"$stripe_public_key\" />";

		echo "<p><strong>Payment Button Label</strong></p>";
		echo "<input name=\"button_label\" value=\"$button_label\" />";
	}

	function mdb_contestant_attributes_box( $post ) {

		$contest_id = get_post_meta( $post->ID, 'contest_id', true );
		$fields = get_post_meta( $post->ID, 'fields', true);

		$args = array( 'post_type' => 'contest' );
		$query = new WP_Query( $args );

		echo "<p><strong>Contest</strong></p>";
		echo "<select name=\"contest_id\" data-id=\"$contest_id\"><option>--</option>";

		foreach ( $query->posts as $contest ) {
			echo "<option value=\"" . $contest->ID . "\">" . $contest->post_title . "</option>";
		}

		echo "</select>";

		echo "<p><strong>Entry</strong></p>";
		echo "<ul>";
		foreach ( $fields as $field => $value ) {
			echo "<li><strong>" . ucwords(str_replace('_',' ',$field)) . "</strong>: $value</li>";
		}
		echo "</ul>";

	}

	function mdb_save_contest( $post_id ) {

		if ( isset($_REQUEST['fields']) ) {
			update_post_meta( $post_id, 'fields', $_REQUEST['fields'] );
		}

		if ( isset($_REQUEST['confirmation_message']) ) {
			update_post_meta( $post_id, 'confirmation_message', $_REQUEST['confirmation_message'] );
		}

		if ( isset($_REQUEST['form_html']) ) {
			update_post_meta( $post_id, 'form_html', $_REQUEST['form_html'] );
		}

		if ( isset($_REQUEST['contest_start_date']) ) {
			update_post_meta( $post_id, 'contest_start_date', $_REQUEST['contest_start_date'] );
		}

		if ( isset($_REQUEST['contest_end_date']) ) {
			update_post_meta( $post_id, 'contest_end_date', $_REQUEST['contest_end_date'] );
		}

		if ( isset($_REQUEST['contest_start_time']) ) {
			update_post_meta( $post_id, 'contest_start_time', $_REQUEST['contest_start_time'] );
		}

		if ( isset($_REQUEST['contest_end_time']) ) {
			update_post_meta( $post_id, 'contest_end_time', $_REQUEST['contest_end_time'] );
		}

		if ( isset($_REQUEST['entry_fee_required']) ) {
			update_post_meta( $post_id, 'entry_fee_required', $_REQUEST['entry_fee_required'] );
		}

		if ( isset($_REQUEST['entry_fee_amount']) ) {
			update_post_meta( $post_id, 'entry_fee_amount', $_REQUEST['entry_fee_amount'] );
		}

		if ( isset($_REQUEST['stripe_private_key']) ) {
			update_post_meta( $post_id, 'stripe_private_key', $_REQUEST['stripe_private_key'] );
		}

		if ( isset($_REQUEST['stripe_public_key']) ) {
			update_post_meta( $post_id, 'stripe_public_key', $_REQUEST['stripe_public_key'] );
		}

		if ( isset($_REQUEST['button_label']) ) {
			update_post_meta( $post_id, 'button_label', $_REQUEST['button_label'] );
		}

	}

	function mdb_save_contestant( $post_id ) {

		if ( isset($_REQUEST['contest_id']) ) {
			update_post_meta( $post_id, 'contest_id', $_REQUEST['contest_id'] );
		}

	}

	function mdb_contest_columns( $columns ) {

		unset(
			$columns['title'],
			$columns['date']
		);

		$new_columns = array(
			'title' => __('Contest'),
			'contestants' => __('Contestants'),
		);

	    return array_merge( $columns, $new_columns );
	}

	function mdb_custom_contest_column( $column, $post_id ) {

		$contestant_args = array( 
			'post_type' => 'contestant',
			'posts_per_page' => -1,
			'meta_key' => 'contest_id',
			'meta_value' => $post_id
		);

		$contestants = new WP_Query( $contestant_args );

	    switch ( $column ) {

	        case 'contestants':	   
	            echo $contestants->post_count;
	            break;

	    }

	}

	function mdb_contestant_columns( $columns ) {

		unset(
			$columns['title'],
			$columns['date']
		);

		$new_columns = array(
			'title' => __('Contestant'),
			'contest' => __('Contest'),
			'date' => __('Date')
		);

	    return array_merge( $columns, $new_columns );
	}

	function mdb_custom_contestant_column( $column, $post_id ) {

		$contest_id = get_post_meta( $post_id, 'contest_id', true );
		$contest = get_post( $contest_id, ARRAY_A );

	    switch ( $column ) {

	        case 'contest':	   
	            echo $contest['post_title'];
	            break;

	    }

	}

	function mdb_filter_contest_content( $content ) {

		global $post;	

		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

		if (( $post->post_type == 'contest' ) && ( $action == 'contest_confirm' )) {

			$message = get_post_meta( $post->ID, 'confirmation_message', true);
			return "<p>$message</p>";

		} else if ( $post->post_type == 'contest' ) {
			
			ob_start();
			include( plugin_dir_path( __FILE__ ) . 'templates/contest-signup.php' );
			$content = $content . ob_get_clean();
			ob_flush();
			
			return $content;

		} 

		return $content;
	}

	function mdb_redirect_submit() {

		global $post;

		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
		$entry_fee_required = get_post_meta( $post->ID, 'entry_fee_required', true);

		if (( $post->post_type == 'contest' ) && ( $action == 'contest_submit' )) {

			// Process payment if necessary
			if ( $entry_fee_required ) {

				$stripe_key = get_post_meta( $post->ID, 'stripe_private_key', true);
				$entry_amount = get_post_meta( $post->ID, 'entry_fee_amount', true);

				$payment = json_decode(mdb_submit_payment($stripe_key, $entry_amount));

				if ( isset($payment->error) ) {

					wp_redirect( '?error=' . urlencode($payment->error->message) );
					exit;

				}	

			}

			// Create post object
			$contestant = array(
				'post_type' => 'contestant',
				'post_title'    => $_POST['fields']['email'],
				'post_content'  => 'Entered successfully!',
				'post_status'   => 'publish',
				'post_author'   => 1
			);

			// Insert the post into the database
			$contestant_id = wp_insert_post( $contestant );

			update_post_meta( $contestant_id, 'contest_id', $post->ID );
			update_post_meta( $contestant_id, 'entry', $_POST['fields'] );

			if ( isset($payment) ) {

				update_post_meta( $contestant_id, 'transaction_id', $payment->id );

			}			

			wp_redirect( '?action=contest_confirm' );
		}

	}

	function mdb_submit_payment( $stripe_key = '', $amount = 0 ) {

		if ( isset( $_REQUEST['stripeToken']) ) {

			// Get cURL resource
			$curl = curl_init();
			$header[] = 'Content-type: application/x-www-form-urlencoded';
			$header[] = 'Authorization: Bearer ' . $stripe_key;

			// Set some options - we are passing in a useragent too here
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => 'https://api.stripe.com/v1/charges?card=' . $_REQUEST['stripeToken'] . '&amount=' . $amount * 100 . '&currency=usd' ,
				CURLOPT_HTTPHEADER => $header,
			    CURLOPT_POST => 1,
			    CURLOPT_POSTFIELDS => array()
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			// Close request to clear up some resources
			curl_close($curl);

			return $resp;

		}

	}

	add_action( 'admin_init', 'mdb_contests_admin_init' );
	add_action( 'init', 'mdb_contests_init' );
	add_action( 'add_meta_boxes', 'mdb_contest_meta_boxes' );
	add_action( 'save_post', 'mdb_save_contest' );
	add_action( 'save_post', 'mdb_save_contestant' );

	add_filter( 'manage_contest_posts_columns' , 'mdb_contest_columns');
	add_action( 'manage_contest_posts_custom_column' , 'mdb_custom_contest_column', 10, 2 );

	add_filter( 'manage_contestant_posts_columns' , 'mdb_contestant_columns');
	add_action( 'manage_contestant_posts_custom_column' , 'mdb_custom_contestant_column', 10, 2 );

	add_filter( 'the_content', 'mdb_filter_contest_content' );
	add_action( 'template_redirect', 'mdb_redirect_submit' );

?>