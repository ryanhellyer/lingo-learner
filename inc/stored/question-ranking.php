<?php
// BROKEN TEST VERSION IS AT /STORED/USER-RANKING.PHP


$user_ranking_data = array(
	0 => array( // Batch 1
		1 => array( // Difficulty level 1
			'incorrect' => 0,
			'correct'   => 0,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 0,
			'correct'   => 0,
		),
	),
	1 => array( // Batch 1
		1 => array( // Difficulty level 1
			'incorrect' => 0,
			'correct'   => 0,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 0,
			'correct'   => 0,
		),
	),
	2 => array( // Batch 1
		1 => array( // Difficulty level 1
			'incorrect' => 0,
			'correct'   => 0,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 0,
			'correct'   => 0,
		),
	),
	3 => array( // Batch 1
		1 => array( // Difficulty level 1
			'incorrect' => 0,
			'correct'   => 0,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 0,
			'correct'   => 0,
		),
	),
	4 => array( // Batch 1
		1 => array( // Difficulty level 1
			'incorrect' => 98,
			'correct'   => 2,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 0,
			'correct'   => 0,
		),
	),
	5 => array( // Batch 1
		1 => array( // Difficulty level 1
			'incorrect' => 90,
			'correct'   => 10,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 0,
			'correct'   => 0,
		),
	),
	6 => array( // Batch 2
		1 => array( // Difficulty level 1
			'incorrect' => 0,
			'correct'   => 35,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 35,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 1,
			'correct'   => 9,
		),
	),
	7 => array( // Batch 3
		1 => array( // Difficulty level 1
			'incorrect' => 0,
			'correct'   => 0,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 9,
			'correct'   => 17,
		),
	),
);


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
		if ( $batch_number < ( $number_of_batches - 3 ) )
			continue;
		
		// We don't care about the most recent batch
		if ( $batch_number > ( $number_of_batches - 2 ) )
			continue;
		
		// Process each specific batch of data
		$batch_ranking = process_specific_batch( $batch_data );
		
		// Ensuring older rankings have less effect
		$number = 1 / ( $number_of_batches - $batch_number - 1 );
		$user_ranking = $user_ranking + ( $number * $batch_ranking );
	}
	$user_ranking = $user_ranking / 1.5; // Adjustment for batch rankings
	
	return $user_ranking;
}

/*
 * Iterate through each difficulty level
 */
function process_specific_batch( $batch_data ) {
	$total_results = 0;
	
	/*
	 * Iterate through each difficulty level
	 */
	foreach( $batch_data as $difficulty => $result ) {
		
		// All results
		$summed_results = $result['incorrect'] + $result['correct'];
		$total_results = $summed_results + $total_results;
		
		// Proportion of correct answers
		if ( 0 == $summed_results ) {
			$level_ranking[$difficulty] = 0;
		} else {
			$level_ranking[$difficulty] = ( $result['correct'] / $summed_results );
		}
		
	}
	
	$batch_ranking = ( $level_ranking[1] + 2 * $level_ranking[2]  + 3 * $level_ranking[3] ) / 6;
	
	return $batch_ranking;
}


die( '<br /><br />DONE');
