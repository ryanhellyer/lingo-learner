<?php


/**
 * Simplified admin panel
 * 
 * Based on Simple CMS ... http://geek.ryanhellyer.net/simplecms/
 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.8
 */
class Lingo_Simple_Admin {

	/**
	 * Class constructor
	 * Adds all the methods to appropriate hooks
	 */
	public function __construct() {
		add_action( 'admin_menu',                 array( $this, 'remove_menus' ) );
		add_action( 'wp_before_admin_bar_render', array( $this, 'remove_admin_bar_links' ) );
		add_action( 'admin_menu',                 array( $this, 'remove_meta_boxes' ) );
	}

	/**
	 * Remove admin bar menus
	 * @global array $wp_admin_bar
	 */
	function remove_admin_bar_links() {
	
		// Bail out now if not in admin or user can't activate plugins
		if ( current_user_can( 'manage_options' ) || ! is_admin() )
			return;
	
		global $wp_admin_bar;
	
		$wp_admin_bar->remove_menu( 'comments' );
		$wp_admin_bar->remove_menu( 'new-content' );
		$wp_admin_bar->remove_menu( 'blog-6-n' );
		$wp_admin_bar->remove_menu( 'blog-6-c' );
	
	}
	
	/**
	 * Remove meta boxes
	 */
	function remove_meta_boxes() {
	
		// List of meta boxes
		$meta_boxes = array(
			'commentsdiv',
			'trackbacksdiv',
			'postcustom',
//			'postexcerpt',
			'commentstatusdiv',
			'commentsdiv',
		);
	
		// Removing the meta boxes
		foreach( $meta_boxes as $box ) {
			remove_meta_box(
				$box, // ID of meta box to remove
				'page', // Post type
				'normal' // Context
			);
		}
	
	}
	
	/**
	 * Remove menus
	 * Redirect dashboard
	 */
	function remove_menus () {
	
		// Bail out now if not in admin
		if ( ! is_admin() )
			return;
		
		// List of items to remove
		$restricted_sub_level = array(
			'edit-tags.php?taxonomy=category' =>'edit.php', // This doesn't actually do anything since posts aren't present, but left here so that you can see how to remove sub menus if needed in your own projects
			'edit.php'                        => 'TOP',
			'edit-comments.php'               => 'TOP',
			'tools.php'                       => 'TOP',
			'link-manager.php'                => 'TOP',
			'upload.php'                      => 'TOP',
//			'edit.php?post_type=page'         => 'TOP',
		);
		foreach( $restricted_sub_level as $page => $top ) {
	
			// If a top leve page, then remove whole block
			if ( 'TOP' == $top )
				remove_menu_page( $page );
			else
				remove_submenu_page( $top, $page );
	
		}
	
		// Redirect from dashboard to edit pages - Thanks to WP Engineer for this code snippet ... http://wpengineer.com/redirects-to-another-page-in-wordpress-backend/
		if ( preg_match( '#wp-admin/?(index.php)?$#', esc_url( $_SERVER['REQUEST_URI'] ) ) )
			wp_redirect( admin_url( 'edit.php?post_type=page' ) );
	
	}

}
new Lingo_Simple_Admin;

