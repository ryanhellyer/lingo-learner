<?php

// Test data
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
			'correct'   => 50,
		),
	),
	5 => array( // Batch 1
		1 => array( // Difficulty level 1
			'incorrect' => 0,
			'correct'   => 50,
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
			'incorrect' => 0,
			'correct'   => 30,
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
			'incorrect' => 0,
			'correct'   => 17,
		),
	),
);


define( 'LINGO_NUMBER_USER_RANKINGS', 10 );
define( 'LINGO_NUMBER_BATCHES', 3 );
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
		$limit = ( $number_of_batches - LINGO_NUMBER_BATCHES );
		if ( ! isset( $counter ) )
			$counter = 1;
		if ( ! isset( $count ) )
			$count = 0;
		
		// We only care about the most recent three batches
		if (
			( $batch_number + 1 ) <  $limit ||
			( $batch_number + 1 ) == $limit
		) {
			continue;
		}
		
		// We don't care about the most recent batch
		if ( ( $batch_number + 1 ) == $number_of_batches )
			continue;
		
echo '
Batch number = ' . ( $batch_number + 1 ). '<br />
Number of batches = ' . $number_of_batches . '<br />
Limit = ' . $limit . '
<hr />';

		// Process each specific batch of data
		$batch_ranking = process_specific_batch( $batch_data );
		
		// Ensuring older rankings have less effect
		$number = 1 / ( $number_of_batches - $batch_number - 1 );
		$user_ranking = $user_ranking + ( $number * $batch_ranking );
		$counter = $counter + ( $number * $count );
		$count++;
	}
	
	$coeff = ( LINGO_NUMBER_BATCHES / $counter );
//	$coeff = 1+1/12;
	
	$user_ranking = $user_ranking / $coeff; // Adjustment for batch rankings
	
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
	
	/*
	 * Calculate batch ranking
	 */
	$ranking_number = 1;
	$batch_ranking = 0;
	$division = 0;
	while ( $ranking_number < LINGO_NUMBER_USER_RANKINGS ) {
		if ( isset( $level_ranking[$ranking_number] ) ) {
			$batch_ranking = $batch_ranking + $ranking_number * $level_ranking[$ranking_number];
			$division = $division + $ranking_number;
		}
		$ranking_number++;
	}
	$batch_ranking = $batch_ranking / $division;
	
	return $batch_ranking;
}


die( '<br /><br />DONE');
