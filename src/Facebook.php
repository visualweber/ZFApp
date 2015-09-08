<?php
/**
 * @see Facebook
 */
include_once (PATH_PROJECT . '/library/Vendor/Facebook/facebook.php');
class App_Facebook {
	private $appId = null;
	private $secret = null;
	
	/**
	 * init Facebook data handler
	 * 
	 */
	public function __construct($appId, $secret) {
		$this->appId = $appId;
		$this->secret = $secret;
	}
	
	/**
	 * connect
	 */
	public function connect() {
		$facebook = new Facebook ( array ('appId' => $this->appId, 'secret' => $this->secret, 'cookie' => true ) );
		$session = $facebook->getSession ();
		
		if (! empty ( $session )) {
			try {
				$uid = $facebook->getUser ();
				$user = $facebook->api ( '/me' );
				if (! empty ( $user ['email'] )) {
					return $user;
				}
				return false;
			} catch ( Exception $e ) {
			}
		} else {
			# There's no active session, let's generate one
			$login_url = $facebook->getLoginUrl ();
			header ( "Location: " . $login_url );
		}
	}
}
?>
