<?php
//file_put_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/data2.txt', 'x', FILE_APPEND );

$alphabet = array(
	'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'å', 'ø', 'æ',
);

$count = 0;
foreach( $alphabet as $letter ) {
	if ( 'ø' == utf8_decode( $letter ) )
		$letter = '%F8';
	if ( 'å' == utf8_decode( $letter ) )
		$letter = '%E5';
	if ( 'æ' == utf8_decode( $letter ) )
		$letter = '%E6';

	foreach( $alphabet as $letter2 ) {
		if ( 'ø' == utf8_decode( $letter2 ) )
			$letter2 = '%F8';
		if ( 'å' == utf8_decode( $letter2 ) )
			$letter2 = '%E5';
		if ( 'æ' == utf8_decode( $letter2 ) )
			$letter2 = '%E6';
		$count++;
//		echo $letter . $letter2 . ' ';
		$new_words = grab_data( $letter . $letter2 );
//die('test');
		foreach( $new_words as $key => $word ) {
			$string = $word . "\n";
			file_put_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/data2.txt', $string, FILE_APPEND );
//die('test');
		}
//die;
//		array_push( $new_words, $words );
	}

}




function grab_data( $characters ) {
	$content = file_get_contents( 'http://no.thefreedictionary.com/' . $characters );

	$string = '<div class=prev><span class="img imgprev"></span></div>';
	$content = explode( $string, $content );
	$string = '</a><br><div class=next><span class="img imgnext"></span></div></td></tr></table></div>';
	$content = explode( $string, $content[1] );
	$words = $content[0];
	$words = explode( '">', $words );
	foreach( $words as $key => $word ) {
	//	echo $word . "\n";
		$word = explode( '</a', $word );
		$word = strip_tags( $word[0] );
		$word = utf8_decode( $word );
	
		if ( strpos( $word,'?' ) !== false ) {
			unset( $words[$key] );
		} elseif ( '' != $word ) {
			$words[$key] = $word;
		} else {
			unset( $words[$key] );
		}
	}
	return $words;
}
