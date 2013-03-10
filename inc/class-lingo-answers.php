<?php

/**
 * Handle answers
 * 
 * Stores link between users and questions
 * Stores number of times the user has answered a question
 * Stores whether the users most recent answer was correct or not
 * Uses it's own DB table for performance reasons
 * 
 * @copyright Copyright (c), Ryan Hellyer
 * @author Ryan Hellyer <ryanhellyer@gmail.com>
 * @since 1.0
 */
class Lingo_Answers {
	
	private $datetime;
	
	/**
	 * Class constructor
	 */
	public function __construct(){
		$this->datetime = current_time( 'mysql' );
	}
	
	/**
	 * Updates a row in the database
	 *
	 * @param int $user_id The user id
	 * @param int $post_id The post id
	 * @param bool $answer True or false (if answer is correct or not)
	 * @return bool false on failure, true if success.
	 **/
	public function update_row( $user_id, $post_id, $answer ) {
		global $wpdb;
		
		// Sanitise integers
		$user_id = (int) $user_id;
		$post_id = (int) $post_id;
		
		// If row doesn't exist, then add it
		$result = $this->get_row_info( $user_id, $post_id );
		if ( $result == false ) {
			$this->add_row( $user_id, $post_id, $answer );
			return;
		}
		
		$times = $result->times;
		$times++;
		
		// Perform the DB update
		$result = $wpdb->update(
			$this->get_table_name(), 
			array(
				'user_id' => $user_id,
				'post_id' => $post_id,
				'answer'  => $answer,
				'date'    => $this->datetime,
				'times'   => $times
			),
			array(
				'user_id' => $user_id,
				'post_id' => $post_id
			)
		);
		
		//Check result
		if ( ! $result )
			return false;
		
		return true;
	}
	
	/**
	 * Creates the table for the plugin logs
	 *
	 * @global array $wpdb The WordPress database global object
	 **/
	public function create_table() {
		global $wpdb;
		
		// Get collation - From /wp-admin/includes/schema.php
		$charset_collate = '';
		if ( ! empty( $wpdb->charset ) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty( $wpdb->collate ) )
			$charset_collate .= " COLLATE $wpdb->collate";
		
		// Create table
		$tablename = $this->get_table_name();
//		$tablename = $wpdb->prefix . 'answers';
		$sql = "CREATE TABLE {$tablename} (
			log_id BIGINT(20) NOT NULL AUTO_INCREMENT,
			user_id BIGINT(20) NOT NULL,
			post_id BIGINT(20) NOT NULL,
			answer BOOLEAN NOT NULL,
			date DATETIME NOT NULL,
			times BIGINT(20) NOT NULL,

			PRIMARY KEY (log_id), 
			KEY user_id (user_id),
			KEY post_id (post_id)
		) {$charset_collate};";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
	
	/**
	 * Returns the tablename for the logs
	 *
	 * @global array $wpdb The WordPress database global object
	 * @return string $tablename - The tablename for the logs
	 **/
	public function get_table_name() {
		global $wpdb;
		$tablename = $wpdb->prefix . 'answers';
		return $tablename;
	}
	
	/**
	 * Retrieve ID for an unanswered question for a specific user
	 *
	 * @param int $user_id  The user id
	 * @param int $taxonomy The taxonomy being queried
	 * @return integer $post_id The ID of the unanswered question
	 **/
	public function get_unanswered_question( $user_id, $taxonomy = '' ) {
		if ( '' == $taxonomy ) {
			// No taxonomy set, therefore grab first random question from any taxonomy
		}
		
		// COMING SOON!
		return $post_id;
	}
	
	/**
	 * Retrieves row info
	 *
	 * @param int $user_id The user id
	 * @param int $post_id The post id
	 * @global array $wpdb The WordPress database global object
	 * @return array of objects
	 **/
	public function get_row_info( $user_id, $post_id ) {
		global $wpdb;
		
		// Sanitise integers
		$user_id = (int) $user_id;
		$post_id = (int) $post_id;
		
		// Process query
		$tablename = $this->get_table_name();
		$query = "SELECT * FROM {$tablename} WHERE user_id = %d AND post_id = %s";
		$query = $wpdb->prepare( $query, $user_id, $post_id );
		$result = $wpdb->get_results( $query, OBJECT );
		
		// Taking only the most recent result (should probably be done via the query if possible)
		$number = count( $result );
		if ( $number > 0 )
			$result = $result[$number-1];
		else
			$result = false;
		
		return $result;
	}
	
	/**
	 * Inserts a log item into the database
	 *
	 * @param int $user_id The user id
	 * @param int $post_id The post id
	 * @param bool $answer The answer
	 * @global array $wpdb The WordPress database global object
	 * @return bool false on failure, true if success.
	 **/
	private function add_row( $user_id, $post_id, $answer ) {
		global $wpdb;
		
		// Sanitise data
		$user_id  = (int) $user_id;
		$post_id  = (int) $post_id;
		$answer   = (bool) $answer;
		
		// Perform the DB insert
		$result = $wpdb->insert(
			$this->get_table_name(),
			array(
				'user_id' => $user_id,
				'post_id' => $post_id,
				'answer'  => $answer,
				'date'    => $this->datetime,
				'times'   => 1
			),
			array(
				'%d',
				'%s',
				'%s',
				'%s'
			)
		);
		
		//Check result
		if ( ! $result )
			return false;
		$log_id = (int) $wpdb->insert_id;
		
		return true;
	}

}
$lingo_answer = new Lingo_Answers;
