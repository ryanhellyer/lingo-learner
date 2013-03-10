<?php

/*
 * Load all includes and features
 */
foreach ( glob( __DIR__ . '/inc/*.php' ) as $feature )
	require( $feature );

//register_activation_hook( __FILE__, 'Lingo_Answers::create_table' );
//$lingo_answer->create_table();

//$random_user_info = $lingo_answer->get_row_info( 8, 425 );
//print_r( $random_user_info );die;

//$lingo_answer->update_row( 8, 425, false );

/*
 * Task #2 --------------
 * Build get_unanswered_question()
 *
 * Task #3 --------------
 * Calculate question difficulty
 * 
 * $user_ranking = User Ranking (1 to 3) - user selects own rank at beginning
 * $qd = Question difficulty (1 to 3) - defaults to 2
 * $c1 = array( $days_since_made => $number_correct ) Number of times question has been answered correctly by user rank 1
 * $c2 = array( $days_since_made => $number_correct ) Number of times question has been answered correctly by user rank 2
 * $c3 = array( $days_since_made => $number_correct ) Number of times question has been answered correctly by user rank 3
 * $w1 = array( $days_since_made => $number_correct ) Number of times question has been answered incorrectly by user rank 1
 * $w2 = array( $days_since_made => $number_correct ) Number of times question has been answered incorrectly by user rank 2
 * $w3 = array( $days_since_made => $number_correct ) Number of times question has been answered incorrectly by user rank 3
 * 
 * $correct = ( 1c1 + 2c2 + 3c3 ) + 1
 * $wrong = ( 3w1 + 2w2 + 1w3 ) + 1
 * $qd = 2 * ( $wrong / $correct )
 *
 * It would pay to factor into account when the questions were answered. More recent answers
 * should have more effect. Plus there should be a way to toggle how much effect time has on
 * the rankings.
 *
 * foreach( $c1 as $key => $value ) {
 * 	
 * }
 * $correct = ( ( 1c1t1 +  2c2t1 + 3c3t1 ) + ( 1c1t2 +  2c2t2 + 3c3t2 ) ) / 2
 * $qd = 2 * ()
 *
 * 
 * Task #4 --------------
 * Calculate user ranking
 * 
 * $user_correct = Number of questions user has answered correctly
 * $user_wrong = Number of questions user has answered incorrectly
 *
 * $user_ranking = 
 * $uc_1 = Number of times question has been answered correctly by user rank 1
 * $uc_2 = Number of times question has been answered correctly by user rank 2
 * $uc_3 = Number of times question has been answered correctly by user rank 3
 * $uw_1 = Number of times question has been answered incorrectly by user rank 1
 * $uw_2 = Number of times question has been answered incorrectly by user rank 2
 * $uw_3 = Number of times question has been answered incorrectly by user rank 3
 * 
 */

// Need something which grabs an unanswered question from the questions post-type (preferably
// with specific taxonomy) which aren't in the answers table for that user.
//$lingo_answer->get_unanswered_question( $user_id, $taxonomy );
