<?php

/**
 * Handle words
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Lingo_Words {
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'init',           array( $this, 'meta_boxes_save' ) );
	} //end constructor
	
	public function init() {
	    $args = array(
			'public' => true,
			'label'  => 'Words'
		);
	    register_post_type( 'words', $args );
	}

	/**
	 * Add admin metabox for thumbnail chooser
	 */
	public function add_metabox() {
		add_meta_box(
			'Translation',
			'Translation',
			array(
				$this,
				'meta_box',
			),
			'words',
			'side',
			'high'
		);
	}
	
	/**
	 * Output the thumbnail meta box
	 *
	 * @return string HTML output
	 */
	public function meta_box() {
		global $post;
		
		if ( isset( $_GET['post'] ) )
			$post_ID = (int) $_GET['post'];
		else
			$post_ID = '';

		echo '<input type="hidden" name="_lingo_hidden" id="_lingo_hidden" value="1" />
		<p>
			<label for="_translation">' . __( 'Translation', 'lingo' ) . '</label>
			<br />';

		$_translation = get_post_meta( $post_ID, '_translation' );
		foreach( $_translation as $key => $trans ) {
			echo '<input type="text" name="_translation[' . $key . ']" id="_translation' . $key . '" value="' . $trans . '" />';
		}
		echo '<input type="text" name="_translation[' . ( $key + 1 ) . ']" id="_translation' . ( $key + 1 ) . '" value="" />';
		
		echo '</p>';
	}
	
	/**
	 * Save opening times meta box data
	 */
	function meta_boxes_save() {

		// Bail out now if something not set
		if (
			isset( $_POST['_wpnonce'] ) &&
			isset( $_POST['post_ID'] ) &&
			isset( $_POST['_lingo_hidden'] ) // This is required to ensure that auto-saves are not processed
		) {
			// Do nonce security check
			wp_verify_nonce( '_wpnonce', $_POST['_wpnonce'] );

			// Grab post ID
			$post_ID = (int) $_POST['post_ID'];

//echo '<textarea style="width:800px;height:600px;">';print_r( $_POST['_translation'] );echo '</textarea>';
//die('dead');

			// Stash all the post meta
			if ( isset( $_POST['_translation'] ) ) {
				$_translation = $_POST['_translation'];
				delete_post_meta( $post_ID, '_translation' );
				foreach( $_translation as $key => $trans ) {
					$trans = wp_kses( $trans, '', '' );
					if ( $trans != '' )
						add_post_meta( $post_ID, '_translation', $trans );
				}
			}
		}
	}

}
new Lingo_Words;
