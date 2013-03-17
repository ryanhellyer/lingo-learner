<?php

add_action( 'init', 'register_my_taxonomies', 0 );
function register_my_taxonomies() {
	register_taxonomy(
		'word_type',
		array( 'words' ),
		array(
			'public' => true,
			'labels' => array(
				'name' => __( 'type', 'lingo' ),
				'singular_name' => __( 'Type', 'lingo' )
			),
		)
	);
}


function delete_all_questions() {
	$count = 0;
	$args = array( 'numberposts' => -1, 'post_type' => 'questions' );
	$myposts = get_posts( $args );
	foreach( $myposts as $post ) {
		setup_postdata( $post );
		wp_delete_post( $post->ID, true );
	}
	
//	die('done2');
}
function delete_all_words() {
	$count = 0;
	$args = array( 'numberposts' => -1, 'post_type' => 'words' );
	$myposts = get_posts( $args );
	foreach( $myposts as $post ) {
		setup_postdata( $post );
		wp_delete_post( $post->ID, true );
	}
	
//	die('done2');
}


//add_action( 'init', 'delete_all_questions' );
//add_action( 'init', 'delete_all_words' );
//add_action( 'init', 'add_pronomen_from_csv' );

function add_pronomen_from_csv() {
	if ( is_admin() )
		return;

	$pronomen_data = file_get_contents( '/var/www/wordpress/wp-content/themes/lingo-learner/inc/pronomen2.csv' );
	$pronomen_data = explode( "\n", $pronomen_data );
	$pronomen_type = array(
		0 => 'infinitiv',
		1 => 'presens',
		2 => 'preteritum',
		3 => 'perfektum',
	);
	
	foreach( $pronomen_data as $key => $line ) {
		$line = utf8_decode( $line );
		$line = wp_kses( $line, '', '' );
		$words = explode( ',', $line );
		
		// Add new question
		$question_title = 'Pronomen of ' . utf8_encode( $words[0] );
		
		// Check if question exists. Bail out if it does.
		$existing_question = lingo_get_post_by_title( $question_title );
		if ( $existing_question )
			continue;

		// Create the new question
		$question_id = wp_insert_post(
			array(
				'post_title'    => $question_title,
				'post_status'   => 'publish',
				'post_type'     => 'questions',
				'post_author'   => 1,
			)
		);
		
		// Add new words
		foreach( $pronomen_type as $type_key => $type ) {
			$new_word_ids[$type_key] = wp_insert_post(
				array(
					'post_title'    => utf8_encode( $words[$type_key] ),
					'post_status'   => 'publish',
					'post_type'     => 'words',
					'post_author'   => 1,
				)
			);
			if ( is_int( $new_word_ids[$type_key] ) ) {
				add_post_meta( $new_word_ids[$type_key], '_translation', $words[4] );
				wp_set_object_terms( $new_word_ids[$type_key], $pronomen_type[$type_key], 'word_type' );
			}
			add_post_meta( $question_id, '_' . $type, $new_word_ids[$type_key] );
		}
		wp_set_object_terms( $question_id, 'Pronomen', 'question_type' );
		
	}
	die ( 'DONE' );
}



function lingo_get_post_by_title($page_title, $output = OBJECT) {
    global $wpdb;
        $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='questions'", $page_title ));
        if ( $post )
            return get_post($post, $output);

    return null;
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
//add_action( 'init', 'make_questions' );
