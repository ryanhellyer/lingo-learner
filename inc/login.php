<?php

class Eigen_Huis_Login {

	/**
	 * Constructor
	 *
	 * Initialize all the actions for authentication
	 * 
	 * @since 1.0
	 */
	function __construct() {
		remove_filter( 'authenticate', 'wp_authenticate_username_password', 20, 3 );
		add_filter( 'authenticate', array( &$this, 'email_login_authenticate' ), 20, 3 );

		add_action( 'template_redirect', array( &$this, 'redirect_locations' ), 900 );
	}

	/**
	 * Be able to authenticate with email instead of username
	 * 
	 * @since 1.0
	 * 
	 * @param object $user The user object
	 * @param string $username The username
	 * @param string $password The password
	 * 
	 * @return object a new user object
	 */
	function email_login_authenticate( $user, $username, $password ) {
		if ( ! empty( $username ) )
			$user = get_user_by( 'email', $username );

		if ( isset( $user, $user->user_login ) )
			$username = $user->user_login;

		return wp_authenticate_username_password( null, $username, $password );
	}

	function redirect_locations() {
		global $wp_rewrite, $wpdb;

		if ( ! ( is_404() && $wp_rewrite->using_permalinks() ) )
			return;

		if ( is_user_logged_in() ) {
			$logins = array(
				home_url( 'wp-login.php', 'relative' ),
				home_url( 'login', 'relative' ),
				site_url( 'login', 'relative' ),
			);

			if ( in_array( untrailingslashit( $_SERVER['REQUEST_URI'] ), $logins ) ) {
				wp_redirect( site_url( 'voor-makelaars', 'login' ) );
				exit;
			}
		}


		if ( untrailingslashit( parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ) === site_url( 'logout/facebook', 'relative' ) ) {
			delete_user_meta( get_current_user_id(), '_facebook_id' );
			delete_user_meta( get_current_user_id(), '_facebook_username' );

			wp_safe_redirect( site_url('/mijn-account/') );
		}


		if ( untrailingslashit( parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ) === site_url( 'logout/twitter', 'relative' ) ) {
			delete_user_meta( get_current_user_id(), '_twitter_id' );
			delete_user_meta( get_current_user_id(), '_twitter_username' );

			wp_safe_redirect( site_url('/mijn-account/') );
		}


		if ( untrailingslashit( parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ) === site_url( 'login/facebook', 'relative' ) ) {
			if( defined('FACEBOOK_APP_ID') && defined('FACEBOOK_APP_SECRET') ) {
				require 'facebook/facebook.php';

				$facebook = new Facebook( array(
					'appId'  => FACEBOOK_APP_ID,
					'secret' => FACEBOOK_APP_SECRET,
					'cookie' => true
				) );

				$session = $facebook->getUser();

				if ( ! empty( $session ) ) {
					$uid  = $facebook->getUser();
					$user = $facebook->api('/me');

					$args  = array(
						//'role' => 'Author',
						'meta_query' => array(
							array(
								'key' => '_facebook_id',
								'value' => $uid
							),
							array(
								'key' => '_facebook_username',
								'value' => $user['username'],
							)
					));

					$users = get_users( $args );

					if( isset( $users[0] ) && ! is_user_logged_in() ) {
						wp_set_auth_cookie( $users[0]->ID );
					}
					else if ( ! isset( $users[0] ) && is_user_logged_in() ) {
						update_user_meta( get_current_user_id(), '_facebook_id', $uid );
						update_user_meta( get_current_user_id(), '_facebook_username', $user['username'] );

						$current_step = get_user_meta( get_current_user_id(), 'current_step', true );
						if( ! $current_step || 1 == $current_step ) {
							$wpdb->update( $wpdb->users, array( 'user_activation_key' => '' ), array( 'ID' => get_current_user_id() ) );
							update_user_meta( get_current_user_id(), 'current_step', 2 );
						}
					}

					wp_safe_redirect( site_url() );
					exit;
				}
				else {
					$login_url = $facebook->getLoginUrl();
					header( "Location: " . $login_url );
				}

				exit;
			}
		}

		if ( untrailingslashit( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) ) === site_url( 'login/twitter', 'relative' ) ) {
			if( defined('TWITTER_CONSUMER_KEY') && defined('TWITTER_CONSUMER_SECRET') ) {
				require 'twitter/twitteroauth.php';

				if ( ! empty( $_GET['oauth_verifier'] ) && ! empty( $_COOKIE['oauth_token'] ) && ! empty( $_COOKIE['oauth_token_secret'] ) ) {
					$twitteroauth = new TwitterOAuth( TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_COOKIE['oauth_token'], $_COOKIE['oauth_token_secret'] );

					$access_token = $twitteroauth->getAccessToken( $_GET['oauth_verifier'] );
					$user_info    = $twitteroauth->get('account/verify_credentials');

					if ( ! isset( $user_info->error ) ) {
						$uid      = $user_info->id;
						$username = $user_info->name;

						$args  = array(
							//'role' => 'Author',
							'meta_query' => array(
								array(
									'key' => '_twitter_id',
									'value' => $uid
								)
						));

						$users = get_users( $args );

						if( isset( $users[0] ) && ! is_user_logged_in() ) {
							wp_set_auth_cookie( $users[0]->ID );
						}
						else if ( ! isset( $users[0] ) && is_user_logged_in() ) {
							update_user_meta( get_current_user_id(), '_twitter_id', $uid );
							update_user_meta( get_current_user_id(), '_twitter_username', $username );

							$current_step = get_user_meta( get_current_user_id(), 'current_step', true );
							if( ! $current_step || 1 == $current_step ) {
								$wpdb->update( $wpdb->users, array( 'user_activation_key' => '' ), array( 'ID' => get_current_user_id() ) );
								update_user_meta( get_current_user_id(), 'current_step', 2 );
							}
						}

						wp_safe_redirect( site_url() );
						exit;
					}

				}
				else {
					$twitteroauth = new TwitterOAuth( TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET );
					$request_token = $twitteroauth->getRequestToken( site_url() . '/login/twitter/' );

					setcookie( "oauth_token", $request_token['oauth_token'], time() + 120, '/', COOKIE_DOMAIN, is_ssl(), true );
					setcookie( "oauth_token_secret", $request_token['oauth_token_secret'], time() + 120, '/', COOKIE_DOMAIN, is_ssl(), true );

					// If everything goes well..
					if ( $twitteroauth->http_code == 200 ) {
						// Let's generate the URL and redirect
						$url = $twitteroauth->getAuthorizeURL( $request_token['oauth_token'] );
						header('Location: ' . $url);
					}
					else {
						// It's a bad idea to kill the script, but we've got to know when there's an error.
						die('Something wrong happened.');
					}

					exit;
				}
			}
		}
	}
}