<?php

$string = file_get_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/array.txt' );
//$string = json_decode( $string );
$string = unserialize( $string );
print_r( $string );


$string = file_get_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/array.txt' );
$string = json_decode( $string );
print_r( $string );

die;
echo $string;
