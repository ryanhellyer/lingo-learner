<?php

add_action( 'init', 'add_words' );
//add_action( 'init', 'delete_all_posts' );


function delete_all_posts() {
	$count = 0;
	$args = array( 'numberposts' => -1, 'post_type' => 'words' );
	$myposts = get_posts( $args );
	foreach( $myposts as $post ) {
		setup_postdata( $post );
		wp_delete_post( $post->ID, true );
	}
	
	die('done2');
}



function add_words() {
	$url = 'http://ryanhellyer.net/stuff/array.txt';
	$content = wp_remote_retrieve_body( wp_remote_get( $url ) );
	$content = unserialize( $content );
//print_r( $content );
//die;
	foreach( $content as $word => $translation ) {
		if ( $word == 'aa' )
			continue;

		if ( ! is_array( $translation ) )
			continue;
		foreach( $translation as $key => $value ) {
			if ( $value == '' )
				$skip = true;
		}
		if ( isset( $skip ) ) {
			unset( $skip );
			continue;
		}
//die( $skip );
		$id = wp_insert_post(
			array(
				'post_title'    => utf8_encode( $word ),
				'post_status'   => 'publish',
				'post_type'     => 'words',
				'post_author'   => 1,
			)
		);

		if ( is_int( $id ) ) {
			$trans = '';
			foreach( $translation as $key => $value ) {
				add_post_meta( $id, '_translation', $value );
			}
		}
	
	}
	
	die( 'done' );
}
