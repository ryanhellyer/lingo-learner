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
		
		// Figuring out the question type
		$question_type = wp_get_post_terms( get_the_ID(), 'question_type', array("fields" => "names") );
		if ( in_array( 'Pronomen', $question_type ) ) {
			$question_type = 'Pronomen';
		} else {
			echo 'WRONG TYPE';
			$question_type = 'Translation';
		}

		?>
		<h1>
			<?php _e( 'Match the pronomen to their corresponding words', 'lingo' ); ?>
		</h1>
		<?php the_content(); ?>

		<form action="<?php
			$rand_post = get_posts(
				array(
					'numberposts'   => 1,
					'orderby'       => 'rand',
					'post_type'     => 'questions',
					'question_type' => $question_type,
				)
			);
			$rand_post = $rand_post[0];
			echo get_permalink( $rand_post );
		?>" method="post">
			<input type="hidden" name="question_id" value="<?php the_ID(); ?>" /><?php

				$pronomen_types = array(
					'_infinitiv'  => __( 'Infinitive', 'lingo' ),
					'_presens'    => __( 'Presens', 'lingo' ),
					'_preteritum' => __( 'Preteritum', 'lingo' ),
					'_prefektum'  => __( 'Prefektum', 'lingo' ),
				);
				$pronomen_types = array(
					'_infinitiv',
					'_presens',
					'_preteritum',
					'_prefektum',
				);
				shuffle( $pronomen_types );
//				print_r( $pronomen_types );
//				die;
				foreach( $pronomen_types as $key => $value ) {
					$pronomen[$key]  = get_post( get_post_meta( get_the_ID(), $key, true ) );
				}
				shuffle( $pronomen );
				print_r( $pronomen );
die;
				echo '
				<p>
					<label>' . $value . '</label>
					<select>';
					foreach( $pronomen as $key2 => $value2 ) {
						echo '<option value="' . $pronomen[$key2]->post_title . '">' . $pronomen[$key2]->post_title . '</option>';
					}
					echo '</select>
				</p>';
//					<?php
//					echo '<br />'.$value . ' : ' . $pronomen[$key]->post_title . '<br />';
				
				
				echo '
				<p>
					<input id="answer-' . $key . '" name="answer" type="radio" value="' . $result->ID . '"  onClick="this.form.submit()" />
					<label for="answer-' . $key . '">' . esc_html( $the_translation ) . '</label>
				</p>';
			
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
