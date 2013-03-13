<?php
return;
// BROKEN TEST VERSION IS AT /STORED/USER-RANKING.PHP

require( 'ranking-data.php' );
define( 'NUMBER_DIFFICULTY_LEVELS', '3' );
define( 'NUMBER_BATCHES_TO_PROCESS', '5' );
define( 'MINIMUM_LEVEL_COUNT_PER_BATCH', '5' );
$user_ranking = get_user_ranking( $user_ranking_data );
echo 'User ranking: ' . $user_ranking . '<br />';


/*
 * Get the users ranking
 *
 * @param array $user_ranking_data Contains complete stored information on users previous results
 * @return numerical $user_ranking The users ranking value from 0 to 1
 */
function get_user_ranking( $user_ranking_data ) {
	$number_of_batches = count( $user_ranking_data );
	$user_ranking = 0;
	
	/*
	 * Iterate through each batch of data
	 * Older batches can be ignored, newer ones are more important
	 */
	foreach( $user_ranking_data as $batch_number => $batch_data ) {
		
		// We only care about the most recent three batches
		if ( $batch_number < ( $number_of_batches - NUMBER_BATCHES_TO_PROCESS ) )
			continue;
		
		// We don't care about the most recent batch
		if ( $batch_number == ( $number_of_batches - 1 ) )
			continue;
		
		// Process each specific batch of data
		$batch_ranking = process_specific_batch( $batch_data );
		
		// Ensuring older rankings have less effect
		$number = 1 / ( $number_of_batches - $batch_number - 1 );
		$user_ranking = $user_ranking + ( $number * $batch_ranking );
	}
	$user_ranking = $user_ranking / ( NUMBER_BATCHES_TO_PROCESS / 2 ); // Adjustment for batch rankings
	
	return $user_ranking;
}

/*
 * Iterate through each difficulty level
 */
function process_specific_batch( $batch_data ) {
	
	/*
	 * Iterate through each difficulty level
	 */
	foreach( $batch_data as $difficulty => $result ) {
		
		// All results
		$summed_results = $result['incorrect'] + $result['correct'];
		
		// Proportion of correct answers
		if ( MINIMUM_LEVEL_COUNT_PER_BATCH < $summed_results ) {
			$level_ranking[$difficulty] = ( $result['correct'] / $summed_results );
		}
		
	}
	
	/*
	 * If difficulty level is empty, then fill it with result from a higher level
	 */
	$difficulty = NUMBER_DIFFICULTY_LEVELS;
	while( $difficulty > 0 ) {
		if ( isset( $level_ranking[$difficulty] ) ) {
			$latest_ranking = $level_ranking[$difficulty];
		} elseif ( isset( $latest_ranking ) ) {
			$level_ranking[$difficulty] = $latest_ranking;
		}
		$difficulty = $difficulty - 1;
	}

	/*
	 * Calculate the ranking
	 * Ranking based on answering higher levels having greater effect on ranking
	 */
	$difficulty = 0;
	$division = 0;
	$batch_ranking = 0;
	while( $difficulty < NUMBER_DIFFICULTY_LEVELS ) {
		$difficulty++;
		if ( isset( $level_ranking[$difficulty] ) ) {
			$batch_ranking = $batch_ranking + $difficulty * $level_ranking[$difficulty];
		} else {
			
		}
		$division = $division + $difficulty;
	}
	$batch_ranking = $batch_ranking / ( $division );
	
	return $batch_ranking;
}


die( '<br /><br />DONE');
