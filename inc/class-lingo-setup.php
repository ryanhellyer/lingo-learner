<?php

/**
 * Lingo Learn theme setup
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Lingo_Setup {
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'widgets_init', array( $this, 'add_widget_area' ) );
	}
	
	/*
	 * Add widget area
	 */
	public function add_widget_area() {
		register_sidebar(
			array(
				'name' => 'Sidebar',
//				'label' => 'sidebar',
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>',
			)
		);
	}

}
new Lingo_Setup;
