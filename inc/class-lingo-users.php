<?php

/**
 * Handle users
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Lingo_Users {
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'show_user_profile',        array( $this, 'show_extra_profile_fields' ) );
		add_action( 'edit_user_profile',        array( $this, 'show_extra_profile_fields' ) );
		add_action( 'personal_options_update',  array( $this, 'save_extra_profile_fields' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_extra_profile_fields' ) );
	}
	
	function show_extra_profile_fields( $user ) {
		?>
		<h3>User ranking</h3>
		<table class="form-table">
			<tr>
				<th><label for="ranking">Ranking</label></th>
				<td>
					<input type="text" name="ranking" id="ranking" value="<?php echo esc_attr( get_the_author_meta( 'ranking', $user->ID ) ); ?>" class="regular-text" /><br />
					<span class="description">Enter the users ranking here</span>
				</td>
			</tr>
	
		</table><?php
	}
	
	function save_extra_profile_fields( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) )
			return false;
		
		$ranking = (int) $_POST['ranking'];
		update_usermeta( $user_id, 'ranking', $ranking );
	}
}
$lingo_users = new Lingo_Users;
