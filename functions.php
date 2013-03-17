<?php


/*
 * Definitions for user ranking system
 */
define( 'NUMBER_DIFFICULTY_LEVELS', '3' );
define( 'NUMBER_BATCHES_TO_PROCESS', '3' );
define( 'MINIMUM_LEVEL_COUNT_PER_BATCH', '5' );



/*
 * Load all includes
 */
foreach ( glob( __DIR__ . '/inc/*.php' ) as $include )
	require( $include );






//register_activation_hook( __FILE__, 'Lingo_Answers::create_table' );
//$lingo_answer->create_table();

//$random_user_info = $lingo_answer->get_row_info( 8, 425 );
//print_r( $random_user_info );die;

//$lingo_answer->update_row( 8, 425, false );

// Need something which grabs an unanswered question from the questions post-type (preferably
// with specific taxonomy) which aren't in the answers table for that user.
//$lingo_answer->get_unanswered_question( $user_id, $taxonomy );
