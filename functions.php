<?php
/*
Plugin Name: Learn Norwegian
Plugin URI: http://geek.ryanhellyer.net/
Description: Learn Norwegian
Author: ryanhellyer
Version: 1.0
Requires at least: 3.6
Author URI: http://geek.ryanhellyer.net/
Contributors: ryanhellyer
*/

define( 'FACEBOOK_APP_ID', '450812854992607' );
define( 'FACEBOOK_APP_SECRET', '31e259e9e33a182ce0a93e9906708226' );

define( 'TWITTER_CONSUMER_KEY', 'uP3Qan5QgVkLqXuvIf6g' );
define( 'TWITTER_CONSUMER_SECRET', 'TyFDzZPVnZBfiCHFonDTIfWzqZmCmajZ1OHiwBx2Y' );

require( 'inc/class-lingo-answers.php' );
register_activation_hook( __FILE__, 'Lingo_Answers::create_table' );
$lingo_answer = new Lingo_Answers;
//$random_user_info = $lingo_answer->get_row_info( 1, 325 );
$lingo_answer->update_row( 8, 425, false );
//$lingo_answer->create_table();
//$lingo_answer->add_row( 7, 125, false );


require( 'inc/class-lingo-questions.php' );
new Lingo_Questions;

require( 'inc/class-lingo-words.php' );
new Lingo_Words;

require( 'inc/class-lingo-simple-admin.php' );
new Lingo_Simple_Admin;

require( 'inc/class-lingo-process-questions.php' );
new Lingo_Process_Questions;

//require( 'inc/login.php' );
//new Eigen_Huis_Login;

if ( isset( $_GET['scrape'] ) )
	require( 'inc/scraper.php' );
if ( isset( $_GET['scrape2'] ) )
	require( 'inc/scraper2.php' );
if ( isset( $_GET['joiner'] ) )
	require( 'inc/joiner.php' );
if ( isset( $_GET['processor'] ) )
	require( 'inc/processor.php' );
if ( isset( $_GET['temp'] ) )
	require( 'inc/temp.php' );
if ( isset( $_GET['make-questions'] ) )
	require( 'inc/make-questions.php' );

