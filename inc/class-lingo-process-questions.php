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
//			add_action( 'template_redirect', array( $this, '_update_question_difficulty' ), $question_id );
			$this->_update_question_difficulty( $user_id, $question_id, $answer );
			$this->_update_user_ranking( $user_id, $question_id, $answer );
			$lingo_answer->update_row( $user_id, $question_id, $answer );
		}
	}
	
	private function _get_user_ranking( $user_id ) {
		return 0.3;
	}
	
	/*
	 * Update the question difficulty
	 *
	 * This does not pull the raw data from the database and instead stores a simplified
	 * array of results via post meta. This is done to avoid killing the database via
	 * extensive querying.
	 *
	 * Results are stored for each level of user who has attempted.
	 * Difficulty level is used by checking the ratios of correct to incorrect for each
	 * user level and assigning the highest rank possible based on those results.
	 */
	private function _update_question_difficulty( $user_id, $question_id, $answer ) {
		
		/*
		 * Grab and sanitise previous results
		 */
		$results = get_post_meta( $question_id, '_previous_results', true );
		$count = 1;
		
		/*
		 * Grab, modify and store results
		 */
		$user_ranking = $this->_get_user_ranking( $user_id );
		$user_ranking = (int) ( $user_ranking * 10 );
		if ( ! isset( $results[$user_ranking] ) )
			$results[$user_ranking] = array( 'correct' => 0, 'incorrect' => 0, );
		$new_result = $results[$user_ranking];
		if ( true == $answer ) {
			$new_result['correct']++;
		} else {
			$new_result['incorrect']++;
		}
		
		// If numbers get high, then shrink them (makes sure that only current data is used)
		$sum = $new_result['correct'] + $new_result['incorrect'];
		if ( $sum > 10 ) {
			$new_result['correct']   = (int) $new_result['correct']   / 2;
			$new_result['incorrect'] = (int) $new_result['incorrect'] / 2;
		}
		
		// Store the result
		$results[$user_ranking] = $new_result;
		update_post_meta( $question_id, '_previous_results', $results );
		
		/*
		 * Calculate new difficulty level for question
		 * Iterates through each level and checks historic records.
		 * Difficulty level is set based on the highest level of users who have answered it correctly
		 */
		
		// In case no one has managed to answer this question, we'll set it's default to the current users level + 1
		$new_difficulty = $user_ranking + 1;
		if ( $new_difficulty > 10 ) { // Maximum difficulty level is 10
			$new_difficulty = 10;
		}
		
		// Iterate through and grab historic records
		$counter = 10;
		while( $counter > 0 ) {
			$counter--;
			
			if ( isset( $results[$counter] ) ) {
				$user_difficulty = $results[$counter];
				
				// Calculate the proportion of times question answered correctly by users at this level
				$sum = $user_difficulty['correct'] + $user_difficulty['incorrect'];
				
				// Only set difficulty if someone has answered at that level
				if ( $sum != 0 )
					$difficulty = $user_difficulty['correct'] / $sum;
				
				// If over half the people answered it, then set this as the new difficulty level
				if ( $difficulty > 0.6 ) {
					$new_difficulty = $counter;
				}
			}
		}
		
		/*
		$term_list = wp_get_post_terms( $question_id, 'difficulty', array( 'fields' => 'all' ) );
		if ( isset( $term_list[0] ) ) {
			$term_list = $term_list[0];
			$current_difficulty = $term_list->term_id;
		} else {
			$new_difficulty = 5; // If no difficulty set, then default to 5
		}
		*/
		$new_difficulty = (string) $new_difficulty; // Needs cast as string before becoming taxonomy
		wp_set_object_terms( $question_id, $new_difficulty, 'difficulty' );
	}
	
	/*
	 * Update the users ranking
	 *
	 * NOTE: CURRENTLY VERY CRUDE AND HAS POOR LOGIC
	 */
	private function _update_user_ranking( $user_id, $question_id, $answer ) {
		
		$term_list = wp_get_post_terms( $question_id, 'difficulty', array( 'fields' => 'all' ) );
		if ( isset( $term_list[0] ) ) {
			$term_list = $term_list[0];
			$current_difficulty = $term_list->term_id;
		} else {
			$current_difficulty = 5;
		}
		
		
		
		
		
		
		
		
		
		$ranking = get_user_meta( $user_id, 'ranking', true );
		if ( true == $answer ) {
			$ranking++;
		} else {
			$ranking = $ranking - 1;
		}
		$ranking = (string) $ranking;
		
		update_user_meta( $user_id, 'ranking', $ranking );
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
