<?php
get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		
		// Hook for adding messages regarding results of previous question
		do_action( 'result_message' );

		?>
		<h1>
			<?php _e( 'What is the definition of ', 'lingo' ); ?>
			"<?php the_title(); ?>"
			<?php _e( '?', 'lingo' ); ?>
		</h1>
		<?php the_content(); ?>
		<form action="" method="post">
			<input type="hidden" name="question_id" value="<?php the_ID(); ?>" /><?php
			$_answers = get_post_meta( get_the_ID(), '_wrong_answers' );
			$_correct_answer = get_post_meta( get_the_ID(), '_correct_answer', true );
			$_answers[] = $_correct_answer;
			shuffle( $_answers );
			$key = 0;
			if ( is_array( $_answers ) ) {
				foreach( $_answers as $key => $answer ) {
					$the_translation = '';
					$result = get_post( $answer );
					$_translation = get_post_meta( $result->ID, '_translation' );
					foreach( $_translation as $key => $trans ) {
						$the_translation .= $trans . ', ';
					}
					$the_translation = substr( $the_translation, 0, -2 ); // Removing the last comma
					echo '
					<p>
						<input name="answer" type="radio" value="' . $result->ID . '" />
						<label for="answer">' . esc_html( $the_translation ) . '</label>
					</p>';
				}
			} else {
				_e( 'Woops! It seems we have a problem. Some silly Billy forgot to add wrong answers to this question.', 'lingo' );
			}
			?>
			<p>
				<input name="submit" value="Submit" type="submit" />
			</p>
		</form>
		<?php
		echo get_the_term_list( get_the_ID(), 'difficulty', '<p>' . __( 'Difficulty level: ', 'lingo' ), ', ', '</p>' );
	}
}

get_footer();

/*
$terms = get_the_terms( get_the_ID(), 'question_type' );
foreach ( $terms as $question_type ) {
	$question_type = $question_type->name;
}
*/