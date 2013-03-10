<?php


function grab_url( $url ) {
	// INIT CURL
	$ch = curl_init();
	
	// SET URL FOR THE POST FORM LOGIN
	curl_setopt($ch, CURLOPT_URL, $url );
	
	// IMITATE CLASSIC BROWSER'S BEHAVIOUR : HANDLE COOKIES
	curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	
	# Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
	# not to print out the results of its query.
	# Instead, it will return the results as a string return value
	# from curl_exec() instead of the usual true/false.
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	
	curl_setopt($ch,CURLOPT_REFERER, 'http://www.tritrans.net/' ); 
	
	// EXECUTE 1st REQUEST (FORM LOGIN)
	$store = curl_exec ($ch);
	
	// SET FILE TO DOWNLOAD
	curl_setopt($ch, CURLOPT_URL, $url );
	
	// EXECUTE 2nd REQUEST (FILE DOWNLOAD)
	$content = curl_exec ($ch);
	
	// CLOSE CURL
	curl_close ($ch); 
	
	return $content;
}




function process_word( $word ) {
	//$content = grab_url( 'http://www.tritrans.net/cgibin/translate.cgi?spraak=Norsk&Fra=' . $word . '&button=Translate%21' );
	$content = grab_url( 'http://www.tritrans.net/cgibin/translate.cgi?spraak=Norsk&Fra=' . $word . '&button=Translate%21' );
	
	$content = explode( '<li>English:</li></td><td><font color="#000000">', $content );
	
	if ( !isset( $content[1] ) )
		return;
	
	
	$english = $content[1];
	
	$english = explode( '<img src="http://www.tritrans.net/oxford.jpg" alt="info" hspace="5" width="15" height="15"></a></font></td></tr>', $english );
	$english = explode( '">', $english[0] );
	
	foreach( $english as $key => $value ) {
		$value = explode( '</a>', $value );
		$value = trim( strip_tags( $value[0] ) );
	//	$english[$key] = $value;
		if ( $value != '' ) {

			$value = trim( $value );
			if (
				strpos( $value, "." ) !== false ||
				strpos( $value, ";" ) !== false ||
				strpos( $value, ":" ) !== false ||
				strpos( $value, '"' ) !== false ||
				strpos( $value, "'" ) !== false ||
				strpos( $value, "?" ) !== false ||
				strpos( $value, "/" ) !== false ||
				strpos( $value, "," ) !== false ||
				strpos( $value, "-" ) !== false ||
				strpos( $value, "+" ) !== false ||
				strpos( $value, "(" ) !== false ||
				strpos( $value, ")" ) !== false
			) {
			} else {
				$english_words[] = $value;
			}
		}
	}
	return $english_words;
}


$string = file_get_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/data2.txt' );
//$string = file_get_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/temp.txt' );
$string = explode( "\n", $string );
$string = array_unique( $string );
$total_count = count( $string );

$words = array();
$count = 0;
foreach( $string as $key => $value ) {
	$value = trim( $value );
	if ( strpos( $value,'&#248;' ) !== false ) {
		unset( $string[$key] );
	} else {
//		if ( $count < 10 ) {
			$words[$value] = process_word( $value );
			$count++;
			file_put_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/temp.txt', 'Total count: ' . $total_count . "\nCount: " . $count );
//		}
	}
}

$words = serialize( $words );
file_put_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/array.txt', $words );
//echo $words;

//$words = json_encode( $words );
//file_put_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/json.txt', $words );

die;
