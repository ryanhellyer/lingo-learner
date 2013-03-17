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
			add_action( 'template_redirect', array( $this, 'process_question' ) );
	}
	
	/*
	 * Process the questions form data
	 * Serves message to user and stores their result if logged in
	 *
	 * @global $lingo_answer Object used for storing answers
	 */
	public function process_question() {
		global $lingo_answer;
		$question_id = (int) $_POST['question_id'];
		$question = get_post( $question_id );
		
		$_correct_answer = get_post_meta( $question->ID, '_correct_answer', true );
		
		// If answer is correct, then congratulate
		if ( ! isset( $_POST['answer'] ) ) {
			$answer = false;
			add_action( 'result_message', array( $this, 'no_answer' ) );
		} elseif ( $_correct_answer != $_POST['answer'] ) {
			$answer = false;
			add_action( 'result_message', array( $this, 'incorrect_answer' ) );
		} else {
			$answer = true;
			add_action( 'result_message', array( $this, 'correct_answer' ) );
		}
		
		// Stash result for user if logged in
		$user_id = get_current_user_id();
		if ( $user_id != 0 ) {
			$lingo_answer->update_row( $user_id, $question_id, $answer );
		}
	}
	
	/*
	 * Message when incorrect answer
	 */
	public function incorrect_answer() {
		$the_answer = $this->_get_answer( $_POST['question_id'] );
		$the_question = $this->_get_question( $_POST['question_id'] );
		
		echo '<div class="message incorrect-answer">Doh! <strong>' . $the_question . '</strong> actually means <strong>"' . $the_answer . '"</strong></div>';
	}
	
	/*
	 * Message when correct answer
	 */
	public function correct_answer() {
		$the_answer = $this->_get_answer( $_POST['question_id'] );
		$the_question = $this->_get_question( $_POST['question_id'] );
		
		echo '<div class="message correct-answer">Correct! <strong>' . $the_question . '</strong> does indeed mean "<strong>' . $the_answer . '</strong>"</div>';
	}
	
	/*
	 * Message when no answer given
	 */
	public function no_answer() {
		echo '<div class="message no-answer">Lame! At least <strong>try</strong> to answer :P</div>';
	}
	
	/*
	 * Get translation of question answer
	 */
	private function _get_question( $question_id ) {
		$question_id = (int) $question_id;
		$question = get_post( $question_id );
		return $question->post_title;
	}
	
	/*
	 * Get translation of word
	 */
	private function _get_answer( $question_id ) {
		$question_id = (int) $question_id;
		$_correct_answer = get_post_meta( $question_id, '_correct_answer', true );
		$_translation = get_post_meta( $_correct_answer, '_translation' );
		
		$the_translation = '';
		foreach( $_translation as $key => $trans ) {
			$the_translation .= $trans . ', ';
		}
		$the_translation = substr( $the_translation, 0, -2 ); // Removing the last comma
		
		return $the_translation;
	}
	
}
new Lingo_Process_Questions;
