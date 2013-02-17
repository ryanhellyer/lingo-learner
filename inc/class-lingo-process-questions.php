<?php

/**
 * Handle words
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Lingo_Process_Questions {
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		if ( isset( $_POST['question_id'] ) )
			add_action( 'init', array( $this, 'process_question' ) );
	} //end constructor
	
	/*
	 * Process the questions form data
	 */
	public function process_question() {
		$question_id = (int) $_POST['question_id'];
		$question = get_post( $question_id );
		
		$_correct_answer = get_post_meta( $question->ID, '_correct_answer', true );

		// If answer is correct, the congratulate
		if ( ! isset( $_POST['answer'] ) ) {
			add_action( 'result_message', array( $this, 'no_answer' ) );
		} elseif ( $_correct_answer != $_POST['answer'] ) {
			add_action( 'result_message', array( $this, 'incorrect_answer' ) );
		} else {
			add_action( 'result_message', array( $this, 'correct_answer' ) );
		}
//		print_r( $_POST );die;
	}
	
	/*
	 * Message when incorrect answer
	 */
	public function incorrect_answer() {
		$question_id = (int) $_POST['question_id'];
		$_correct_answer = get_post_meta( $question_id, '_correct_answer', true );
		$_translation = get_post_meta( $_correct_answer, '_translation' );
		
		$the_translation = '';
		foreach( $_translation as $key => $trans ) {
			$the_translation .= $trans . ', ';
		}
		$the_translation = substr( $the_translation, 0, -2 ); // Removing the last comma
		
		echo '<div class="message incorrect-answer">Doh! The answer was actually "<strong>' . $the_translation . '</strong>"</div>';
	}
	
	/*
	 * Message when correct answer
	 */
	public function correct_answer() {
		echo '<div class="message correct-answer">w00p w00p! "' . $_POST['question_id'] . '" was indeed the correct answer :)</div>';
	}
	
	/*
	 * Message when correct answer
	 */
	public function no_answer() {
		echo '<div class="message no-answer">Lame! At least <strong>try</strong> to answer :P</div>';
	}
	
}
