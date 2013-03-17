<?php

/**
 * Handle questions
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Lingo_Questions {
	
	private $post_meta;
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		
		add_action( 'init',           array( $this, 'register_post_type' ) );
		add_action( 'init',           array( $this, 'add_difficulty' ) );
		add_action( 'init',           array( $this, 'add_question_type' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'init',           array( $this, 'meta_boxes_save' ) );
		
		$this->post_meta = array(
			'_imperative' => __( 'Imperative', 'lingo' ),
			'_infinitiv'  => __( 'Infinitive', 'lingo' ),
			'_presens'    => __( 'Presens',    'lingo' ),
			'_preteritum' => __( 'Preteritum', 'lingo' ),
			'_prefektum'  => __( 'Prefektum',  'lingo' ),
		);
		
	}
	
	/**
	 * Register the post-type
	 */
	public function register_post_type() {
	    $args = array(
			'public' => true,
			'label'  => 'Questions'
		);
	    register_post_type( 'questions', $args );
	}
	
	/**
	 * Add support for difficulty levels
	 */
	public function add_difficulty() {
		register_taxonomy(
			'difficulty',
			'questions',
			array(
				'hierarchical'  => false,
				'label'         => __( 'Difficulty', 'lingo' ),
				'singular_name' => __( 'Difficulty level', 'lingo' ),
			)
		);
	}
	
	/**
	 * Add support for question types
	 */
	public function add_question_type() {
		register_taxonomy(
			'question_type',
			'questions',
			array(
				'hierarchical'  => true,
				'label'         => __( 'Question type', 'lingo' ),
				'singular_name' => __( 'Question type level', 'lingo' ),
			)
		);

	}
	
	/**
	 * Add admin metabox for thumbnail chooser
	 */
	public function add_metabox() {
		add_meta_box(
			'Word linkages',
			'Word linkages',
			array(
				$this,
				'linkages_meta_box',
			),
			'questions',
			'side',
			'high'
		);
		
		add_meta_box(
			'Answers',
			'Answers',
			array(
				$this,
				'answers_meta_box',
			),
			'questions',
			'side',
			'high'
		);
	}
	
	/**
	 * Output the thumbnail meta box
	 *
	 * @return string HTML output
	 */
	public function linkages_meta_box() {
		global $post;
		
		if ( isset( $_GET['post'] ) )
			$post_ID = (int) $_GET['post'];
		else
			$post_ID = '';
		
		echo '<input type="hidden" name="_lingo_hidden" id="_lingo_hidden" value="1" />';
		
		foreach( $this->post_meta as $key => $value ) {
			echo '
		<p>
			<label for="' . $key . '">' . $value . '</label>
			<br />
			<input type="text" name="' . $key . '" id="' . $key . '" value="' . get_post_meta( $post_ID, $key, true ) . '" />
		</p>';
		}
	}
	
	/**
	 * Output the thumbnail meta box
	 *
	 * @return string HTML output
	 */
	public function answers_meta_box() {
		global $post;
		
		if ( isset( $_GET['post'] ) )
			$post_ID = (int) $_GET['post'];
		else
			$post_ID = '';

		echo '<input type="hidden" name="_lingo_hidden" id="_lingo_hidden" value="1" />
		<p>
			<label for="_wrong_answers">' . __( 'Wrong answers', 'lingo' ) . '</label>
			<br />';

		$_wrong_answers = get_post_meta( $post_ID, '_wrong_answers' );
		$key = 0;
		if ( is_array( $_wrong_answers ) ) {
			foreach( $_wrong_answers as $key => $answer ) {
				echo '<input type="text" name="_wrong_answers[' . $key . ']" id="_wrong_answers' . $key . '" value="' . $answer . '" />';
			}
		}

		echo '
			<input type="text" name="_wrong_answers[' . ( $key + 1 ) . ']" id="_wrong_answers' . ( $key + 1 ) . '" value="" />
		</p>
		<p>
			<label for="_correct_answer">' . __( 'Correct answer', 'lingo' ) . '</label>
			<br />
			<input type="text" name="_correct_answer" id="_correct_answer" value="' . get_post_meta( $post_ID, '_correct_answer', true ) . '" />
		</p>';
	
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
			
			// Set wrong answers
			delete_post_meta( $post_ID, '_wrong_answers' );
			foreach( $_POST['_wrong_answers'] as $key => $value ) {
				if ( $value != '' && $value != 0 ) {
					$value = (int) $value;
					add_post_meta( $post_ID, '_wrong_answers', $value );
				}
			}			
			
			// Stash all the post meta
			foreach( $this->post_meta as $key => $x ) {
				if ( isset( $_POST[$key] ) ) {
					$value = (int) $_POST[$key];
					if ( $value != 0 )
						update_post_meta( $post_ID, $key, $value );
				}
			}
		}
	}

}
new Lingo_Questions;
