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
			'incorrect' => 0,
			'correct'   => 0,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 0,
			'correct'   => 100,
		),
	),
	5 => array( // Batch 1
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
			'correct'   => 100,
		),
	),
	6 => array( // Batch 2
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
			'correct'   => 100,
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
			'incorrect' => 95,
			'correct'   => 5,
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
			'incorrect' => 70,
			'correct'   => 20,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 10,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 0,
			'correct'   => 0,
		),
	),
	4 => array( // Batch 1
		1 => array( // Difficulty level 1
			'incorrect' => 35,
			'correct'   => 5,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 30,
			'correct'   => 30,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 0,
			'correct'   => 0,
		),
	),
	5 => array( // Batch 1
		1 => array( // Difficulty level 1
			'incorrect' => 0,
			'correct'   => 0,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 5,
			'correct'   => 35,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 50,
			'correct'   => 10,
		),
	),
	6 => array( // Batch 2
		1 => array( // Difficulty level 1
			'incorrect' => 0,
			'correct'   => 0,
		),
		2 => array( // Difficulty level 2
			'incorrect' => 0,
			'correct'   => 0,
		),
		3 => array( // Difficulty level 3
			'incorrect' => 2,
			'correct'   => 98,
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



define( 'NUMBER_DIFFICULTY_LEVELS', '3' );
define( 'NUMBER_BATCHES_TO_PROCESS', '3' );
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
		if ( 0 == $summed_results ) {
			$level_ranking[$difficulty] = 0;
		} else {
			$level_ranking[$difficulty] = ( $result['correct'] / $summed_results );
		}
		
	}
	
	/*
	 * Calculate the ranking
	 * Ranking based on answering higher levels having greater effect on ranking
	 */
	$level = 0;
	$batch_ranking = 0;
	while( $level < NUMBER_DIFFICULTY_LEVELS ) {
		$level++;
		if ( isset( $level_ranking[$level] ) ) {
			$batch_ranking = $batch_ranking + $level * $level_ranking[$level];
		}
	}
	$batch_ranking = $batch_ranking / $level;
	
	return $batch_ranking;
}


die( '<br /><br />DONE');
