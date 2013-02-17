<?php


$string = file_get_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/data2.txt' );
$string = explode( "\n", $string );
$string = array_unique( $string );

foreach( $string as $key => $value ) {
	$value = trim( $value );
	if ( strpos( $value,'&#248;' ) !== false ) {
		unset( $string[$key] );
	} else {
		$string[$key] = $value;
	}
}


echo count( $string );
print_r( $string );
die;


