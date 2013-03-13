<?php
return;
$results = array(
	1  => 2,
	2  => 3,
	3  => 5,
	4  => 7,
	5  => 9,
	6  => 15,
	7  => 25,
	8  => 35,
	9  => 45,
	10 => 55,
);
print_r( $results );

$user_ranking = '0.3';
$user_ranking = (int) ( $user_ranking * 10 );
$correct = true;

if ( $correct == true ) {
	$results[$user_ranking] = $results[$user_ranking] + 1;
} else {
	$results[$user_ranking]--;
}

echo $user_ranking . '<hr />';

print_r( $results );

echo $user_ranking . '<hr />';
die;

