<?php
get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		
		// Hook for adding messages regarding results of previous question
		do_action( 'result_message' );
		
		$user_id = get_current_user_id();
		if ( $user_id != 0 ) {
			$answer = $lingo_answer->get_row_info( $user_id, get_the_ID() );
			if ( isset( $answer->times ) ) {
				echo '<div class="message previous-answer">';
				
				if ( $answer->answer == true ) {
					$result = 'correctly';
				} else {
					$result = 'incorrectly';
				}
				
				$times = (int) $answer->times;
				
				$date = $answer->date;
				$date = date( 'l jS \of F Y', strtotime( $date ) );
				echo 'You answered this question ' . $result . ' previously on ' . $date . '. You have attempted this question ' . $times . ' times.';
				echo '</div>';
			}
		}
		
		?>
		<h1>
			<?php _e( 'What is the definition of ', 'lingo' ); ?>
			"<?php the_title(); ?>"
			<?php _e( '?', 'lingo' ); ?>
		</h1>
		<?php the_content(); ?>
		<form action="<?php
			$rand_post = get_posts(
				array(
					'numberposts' => 1,
					'orderby'     => 'rand',
					'post_type'   => 'questions',
				)
			);
			$rand_post = $rand_post[0];
			echo get_permalink( $rand_post );
		?>" method="post">
			<input type="hidden" name="question_id" value="<?php the_ID(); ?>" /><?php
			
			// Grab the fake answers and limit how many are used
			$_answers = get_post_meta( get_the_ID(), '_wrong_answers' );
			$_answers = array_chunk( $_answers, 4 );
			$_answers = $_answers[1];
			
			// Add the correct answer as an option
			$_answers[] = get_post_meta( get_the_ID(), '_correct_answer', true );
			
			// Randomising the answer order
			shuffle( $_answers );
			
			if ( is_array( $_answers ) ) {
				foreach( $_answers as $key => $answer ) {
					$the_translation = '';
					$result = get_post( $answer );
					$_translation = get_post_meta( $result->ID, '_translation' );
					foreach( $_translation as $key2 => $trans ) {
						$the_translation .= $trans . ', ';
					}
					$the_translation = substr( $the_translation, 0, -2 ); // Removing the last comma
					echo '
			<p>
				<input id="answer-' . $key . '" name="answer" type="radio" value="' . $result->ID . '"  onClick="this.form.submit()" />
				<label for="answer-' . $key . '">' . esc_html( $the_translation ) . '</label>
			</p>';
				}
			} else {
				_e( 'Woops! It seems we have a problem. Some silly Billy forgot to add wrong answers to this question.', 'lingo' );
			}
			
			?>
			<noscript>
				<p>
					<input name="submit" value="Submit" type="submit" />
				</p>
			</noscript>
		</form><?php
		
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