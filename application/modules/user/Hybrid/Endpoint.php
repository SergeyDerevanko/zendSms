<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

/**
 * User_Hybrid_Endpoint class
 * 
 * User_Hybrid_Endpoint class provides a simple way to handle the OpenID and OAuth endpoint.
 */
class User_Hybrid_Endpoint {
	public static $request = NULL;
	public static $initDone = FALSE;

	/**
	* Process the current request
	*
	* $request - The current request parameters. Leave as NULL to default to use $_REQUEST.
	*/
	public static function process( $request = NULL )
	{
		// Setup request variable
		User_Hybrid_Endpoint::$request = $request;

		if ( is_null(User_Hybrid_Endpoint::$request) ){
			// Fix a strange behavior when some provider call back ha endpoint
			// with /index.php?hauth.done={provider}?{args}... 
			// >here we need to recreate the $_REQUEST
			if ( strrpos( $_SERVER["QUERY_STRING"], '?' ) ) {
				$_SERVER["QUERY_STRING"] = str_replace( "?", "&", $_SERVER["QUERY_STRING"] );

				parse_str( $_SERVER["QUERY_STRING"], $_REQUEST );
			}

			User_Hybrid_Endpoint::$request = $_REQUEST;
		}

		// If openid_policy requested, we return our policy document
		if ( isset( User_Hybrid_Endpoint::$request["get"] ) && User_Hybrid_Endpoint::$request["get"] == "openid_policy" ) {
			User_Hybrid_Endpoint::processOpenidPolicy();
		}

		// If openid_xrds requested, we return our XRDS document
		if ( isset( User_Hybrid_Endpoint::$request["get"] ) && User_Hybrid_Endpoint::$request["get"] == "openid_xrds" ) {
			User_Hybrid_Endpoint::processOpenidXRDS();
		}

		// If we get a hauth.start
		if ( isset( User_Hybrid_Endpoint::$request["hauth_start"] ) && User_Hybrid_Endpoint::$request["hauth_start"] ) {
			User_Hybrid_Endpoint::processAuthStart();
		}
		// Else if hauth.done
		elseif ( isset( User_Hybrid_Endpoint::$request["hauth_done"] ) && User_Hybrid_Endpoint::$request["hauth_done"] ) {
			User_Hybrid_Endpoint::processAuthDone();
		}
		// Else we advertise our XRDS document, something supposed to be done from the Realm URL page
		else {
			User_Hybrid_Endpoint::processOpenidRealm();
		}
	}

	/**
	* Process OpenID policy request
	*/
	public static function processOpenidPolicy()
	{
		$output = file_get_contents( dirname(__FILE__) . "/resources/openid_policy.html" ); 
		print $output;
		die();
	}

	/**
	* Process OpenID XRDS request
	*/
	public static function processOpenidXRDS()
	{
		header("Content-Type: application/xrds+xml");

		$output = str_replace
		(
			"{RETURN_TO_URL}",
			str_replace(
				array("<", ">", "\"", "'", "&"), array("&lt;", "&gt;", "&quot;", "&apos;", "&amp;"), 
				User_Hybrid_Auth::getCurrentUrl( false )
			),
			file_get_contents( dirname(__FILE__) . "/resources/openid_xrds.xml" )
		);
		print $output;
		die();
	}

	/**
	* Process OpenID realm request
	*/
	public static function processOpenidRealm()
	{
		$output = str_replace
		(
			"{X_XRDS_LOCATION}",
			htmlentities( User_Hybrid_Auth::getCurrentUrl( false ), ENT_QUOTES, 'UTF-8' ) . "?get=openid_xrds&v=" . User_Hybrid_Auth::$version,
			file_get_contents( dirname(__FILE__) . "/resources/openid_realm.html" )
		); 
		print $output;
		die();
	}

	/**
	* define:endpoint step 3.
	*/
	public static function processAuthStart()
	{
		User_Hybrid_Endpoint::authInit();

		$provider_id = trim( strip_tags( User_Hybrid_Endpoint::$request["hauth_start"] ) );

		# check if page accessed directly
		if( ! User_Hybrid_Auth::storage()->get( "hauth_session.$provider_id.hauth_endpoint" ) ) {
			User_Hybrid_Logger::error( "Endpoint: hauth_endpoint parameter is not defined on hauth_start, halt login process!" );

			header( "HTTP/1.0 404 Not Found" );
			die( "You cannot access this page directly." );
		}

		# define:hybrid.endpoint.php step 2.
		$hauth = User_Hybrid_Auth::setup( $provider_id );

		# if REQUESTed hauth_idprovider is wrong, session not created, etc. 
		if( ! $hauth ) {
			User_Hybrid_Logger::error( "Endpoint: Invalid parameter on hauth_start!" );

			header( "HTTP/1.0 404 Not Found" );
			die( "Invalid parameter! Please return to the login page and try again." );
		}

		try {
			User_Hybrid_Logger::info( "Endpoint: call adapter [{$provider_id}] loginBegin()" );

			$hauth->adapter->loginBegin();
		}
		catch ( Exception $e ) {
			User_Hybrid_Logger::error( "Exception:" . $e->getMessage(), $e );
			User_Hybrid_Error::setError( $e->getMessage(), $e->getCode(), $e->getTraceAsString(), $e );

			$hauth->returnToCallbackUrl();
		}

		die();
	}

	/**
	* define:endpoint step 3.1 and 3.2
	*/
	public static function processAuthDone()
	{
		User_Hybrid_Endpoint::authInit();

		$provider_id = trim( strip_tags( User_Hybrid_Endpoint::$request["hauth_done"] ) );

		$hauth = User_Hybrid_Auth::setup( $provider_id );

		if( ! $hauth ) {
			User_Hybrid_Logger::error( "Endpoint: Invalid parameter on hauth_done!" );

			$hauth->adapter->setUserUnconnected();

			header("HTTP/1.0 404 Not Found"); 
			die( "Invalid parameter! Please return to the login page and try again." );
		}

		try {
			User_Hybrid_Logger::info( "Endpoint: call adapter [{$provider_id}] loginFinish() " );

			$hauth->adapter->loginFinish(); 
		}
		catch( Exception $e ){
			User_Hybrid_Logger::error( "Exception:" . $e->getMessage(), $e );
			User_Hybrid_Error::setError( $e->getMessage(), $e->getCode(), $e->getTraceAsString(), $e );

			$hauth->adapter->setUserUnconnected(); 
		}

		User_Hybrid_Logger::info( "Endpoint: job done. retrun to callback url." );

		$hauth->returnToCallbackUrl();
		die();
	}

	public static function authInit()
	{
		if ( ! User_Hybrid_Endpoint::$initDone) {
			User_Hybrid_Endpoint::$initDone = TRUE;

			# Init User_Hybrid_Auth
			try {
				require_once realpath( dirname( __FILE__ ) )  . "/Storage.php";
				
				$storage = new User_Hybrid_Storage();

				// Check if User_Hybrid_Auth session already exist
				if ( ! $storage->config( "CONFIG" ) ) { 
					header( "HTTP/1.0 404 Not Found" );
					die( "You cannot access this page directly." );
				}

				User_Hybrid_Auth::initialize( $storage->config( "CONFIG" ) );
			}
			catch ( Exception $e ){
				User_Hybrid_Logger::error( "Endpoint: Error while trying to init User_Hybrid_Auth" );

				header( "HTTP/1.0 404 Not Found" );
				die( "Oophs. Error!" );
			}
		}
	}
}
