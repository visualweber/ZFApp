<?php
/**
 * @see Yahoo
 */
include_once PATH_PROJECT . '/library/Vendor/Yahoo/lib/OAuth/OAuth.php';
include_once PATH_PROJECT . '/library/Vendor/Yahoo/lib/Yahoo/YahooOAuthApplication.class.php';
class App_Yahoo {
	private $key = null;
	private $secret = null;
	private $appId = null;
	private $domain = null;
	private $redirect_url = null;
	
	/**
	 * init Yahoo data handler
	 * 
	 */
	public function __construct($key, $secret, $appId, $domain, $redirect_url) {
		$this->key = $key;
		$this->secret = $secret;
		$this->appId = $appId;
		$this->domain = $domain;
		$this->redirect_url = $redirect_url;
	}
	
	/**
	 * connect
	 */
	public function connect() {
		$oauthapp = new YahooOAuthApplication ( $this->key, $this->secret, $this->appId, $this->domain );
		# Fetch request token
		$request_token = $oauthapp->getRequestToken ( $this->redirect_url );
		$_SESSION ['request_token'] = get_object_vars ( $request_token );
		# Redirect user to authorization url
		$redirect_url = $oauthapp->getAuthorizationUrl ( $request_token );
		header ( 'Location: ' . $redirect_url );
		exit ();
	
	}
	
	public function authencation() {
		$obj_token = new stdClass ();
		$request_token = $_SESSION ['request_token'];
		if ($request_token) {
			foreach ( $request_token as $key => $value ) {
				$obj_token->$key = $value;
			}
			$oauthapp = new YahooOAuthApplication ( $this->key, $this->secret, $this->appId, $this->domain );
			# Exchange request token for authorized access token
			$access_token = $oauthapp->getAccessToken ( $obj_token, $_REQUEST ['oauth_verifier'] );
			$profile = $oauthapp->getProfile ();
			return $profile;
		}
	}
}
?>
