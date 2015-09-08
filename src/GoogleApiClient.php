<?php
/**
 * @see Facebook
 */
include_once (PATH_PROJECT . '/library/Vendor/GoogleApiClient/src/apiClient.php');
include_once (PATH_PROJECT . '/library/Vendor/GoogleApiClient/src/contrib/apiPlusService.php');
class App_GoogleApiClient {
	private $clientId = null;
	private $clientSecret = null;
	private $redirectUri = null;
	
	/**
	 * init Facebook data handler
	 * 
	 */
	public function __construct($clientId, $clientSecret, $redirectUri) {
		$this->clientId = $clientId;
		$this->clientSecret = $clientSecret;
		$this->redirectUri = $redirectUri;
	}
	
	/**
	 * connect
	 */
	protected function connect() {
		$client = new apiClient ();
		$client->setApplicationName ( "Google Application" );
		//*********** Replace with Your API Credentials **************
		$client->setClientId ( $this->clientId );
		$client->setClientSecret ( $this->clientSecret );
		$client->setRedirectUri ( $this->redirectUri );
		//        $client->setDeveloperKey('AIzaSyBiUF9NmJKGwbJCDOQIoF2NxMgtYjwI1c8');
		//************************************************************
		$client->setScopes ( array ('https://www.googleapis.com/auth/userinfo.profile', 'https://www.googleapis.com/auth/userinfo.email' ) );
		return $client;
	}
	
	public function authenticated($code) {
		
		$client = $this->connect ();
		if (isset ( $_REQUEST ['logout'] )) {
			unset ( $_SESSION ['access_token'] );
		}
		if (isset ( $code )) {
			$client->authenticate ();
			$_SESSION ['access_token'] = $client->getAccessToken ();
		}
		if (isset ( $_SESSION ['access_token'] )) {
			$client->setAccessToken ( $_SESSION ['access_token'] );
		}
		if ($client->getAccessToken ()) {
			$token = json_decode ( $client->getAccessToken (), true );
			/**************/
			$serviceURI = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $token ['access_token'];
			global $apiConfig;
			$curl = new apiCurlIO ( new $apiConfig ['cacheClass'] (), new $apiConfig ['authClass'] () );
			$respone = $curl->makeRequest ( new apiHttpRequest ( $serviceURI ) );
			$auth = json_decode ( $respone->getResponseBody (), true );
			return $auth;
		}		
		return false;
	}
	
	public function authLink() {
		$client = $this->connect ();
		$authUrl = $client->createAuthUrl ();
	}
}
?>
