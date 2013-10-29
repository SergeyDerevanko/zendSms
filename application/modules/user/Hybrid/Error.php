<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

/**
 * Errors manager
 * 
 * HybridAuth errors are stored in Hybrid::storage() and not displayed directly to the end user 
 */
class User_Hybrid_Error
{
	/**
	* store error in session
	*/
	public static function setError( $message, $code = NULL, $trace = NULL, $previous = NULL )
	{
		User_Hybrid_Logger::info( "Enter User_Hybrid_Error::setError( $message )" );

		User_Hybrid_Auth::storage()->set( "hauth_session.error.status"  , 1         );
		User_Hybrid_Auth::storage()->set( "hauth_session.error.message" , $message  );
		User_Hybrid_Auth::storage()->set( "hauth_session.error.code"    , $code     );
		User_Hybrid_Auth::storage()->set( "hauth_session.error.trace"   , $trace    );
		User_Hybrid_Auth::storage()->set( "hauth_session.error.previous", $previous );
	}

	/**
	* clear the last error
	*/
	public static function clearError()
	{ 
		User_Hybrid_Logger::info( "Enter User_Hybrid_Error::clearError()" );

		User_Hybrid_Auth::storage()->delete( "hauth_session.error.status"   );
		User_Hybrid_Auth::storage()->delete( "hauth_session.error.message"  );
		User_Hybrid_Auth::storage()->delete( "hauth_session.error.code"     );
		User_Hybrid_Auth::storage()->delete( "hauth_session.error.trace"    );
		User_Hybrid_Auth::storage()->delete( "hauth_session.error.previous" );
	}

	/**
	* Checks to see if there is a an error. 
	* 
	* @return boolean True if there is an error.
	*/
	public static function hasError()
	{ 
		return (bool) User_Hybrid_Auth::storage()->get( "hauth_session.error.status" );
	}

	/**
	* return error message 
	*/
	public static function getErrorMessage()
	{ 
		return User_Hybrid_Auth::storage()->get( "hauth_session.error.message" );
	}

	/**
	* return error code  
	*/
	public static function getErrorCode()
	{ 
		return User_Hybrid_Auth::storage()->get( "hauth_session.error.code" );
	}

	/**
	* return string detailled error backtrace as string.
	*/
	public static function getErrorTrace()
	{ 
		return User_Hybrid_Auth::storage()->get( "hauth_session.error.trace" );
	}

	/**
	* @return string detailled error backtrace as string.
	*/
	public static function getErrorPrevious()
	{ 
		return User_Hybrid_Auth::storage()->get( "hauth_session.error.previous" );
	}
}
