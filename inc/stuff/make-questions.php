<?php

add_action( 'init', 'make_questions' );
//add_action( 'init', 'delete_all_posts' );


function delete_all_questions() {
	$count = 0;
	$args = array( 'numberposts' => -1, 'post_type' => 'words' );
	$myposts = get_posts( $args );
	foreach( $myposts as $post ) {
		setup_postdata( $post );
		wp_delete_post( $post->ID, true );
	}
	
	die('done2');
}



/*
 * Make the questions
 */
function make_questions() {
	$args = array( 'numberposts' => -1, 'post_type' => 'words' );
	$words = get_posts( $args );
	foreach( $words as $post ) {
		setup_postdata( $post );
		$id = wp_insert_post(
			array(
				'post_title'    => $post->post_title,
				'post_status'   => 'publish',
				'post_type'     => 'questions',
				'post_author'   => 1,
			)
		);
		add_post_meta( $id, '_correct_answer', $post->ID );
		
		$wrong_words = get_posts(
			array(
				'numberposts' => 10,
				'post_type'   => 'words',
				'orderby'     => 'rand',
				'exclude'     => $post->ID,
			)
		);
		foreach( $wrong_words as $wrong_post ) {
			setup_postdata( $wrong_post );
			add_post_meta( $id, '_wrong_answers', $wrong_post->ID );
		}
	}
	
	die( 'done' );
}
