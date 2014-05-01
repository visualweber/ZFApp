<?php
/**
 * XGOON MEDIA COMPANY LIMITED
 *
 * Object Role Modeling (ORM) is a powerful method for designing and querying
 * database models at the conceptual level, where the application is described in
 * terms easily understood by non-technical users. In practice, ORM data models
 * often capture more business rules, and are easier to validate and evolve than
 * data models in other approaches.
 *
 * XGOON MEDIA VIETNAM is a software development company
 * specializing in Web Application, Mobile Application and Multimedia. xgoon's combination of experience
 * and specialization on Internet technologies extends our customers' competitive
 * advantage and helps them maximize their return on investment. We aim to realize
 * your company's goals and vision though ongoing communication and our commitment
 * to quality.
 *
 * @category 	App
 * @package 	App.Platform
 * @copyright 	Copyright (c) 2010-2014 XGOON MEDIA VIETNAM.
 * @license 	http://www.xgoon.com
 * @version 	App version 1.0.0
 * @author 	toan@xgoon.com <vnnfree@gmail.com>
 * @implement 	All XGOON's members
 */
class App_Service_Rest_Auth {
	
	private $responseVault = null;
	
	/**
	 * Get all shelf related data.
	 * @param mixed The shelf identifier, either an id or the name of the shelf.
	 * @return mixed A collection containing the shelf details and the assigned
	 * records or a custom error status on failure.
	 */
	public function authRequest($redirect_url = '') {
		$session = App_Util::getSession ();
		$result = array ();
		$userModel = new Model_Users ();
		if ($userModel->isAuthenticated ()) {
			$token = $session->__get ( 'token' );
			if (empty ( $token )) {
				$token = App_Util::getToken ( true );
			}
			$result ['auth_token'] = $token;
		} else {
			App_Util::clearToken ();
			$redirect = new Zend_Controller_Action_Helper_Redirector ();
			$redirect->gotoUrl ( '/users/auth/login?redirect_url=' . $redirect_url );
		}
		return $result;
	}
	/**
	 * 
	 * Check token 
	 * @param $token
	 */
	public function getProfile($token = '') {
		if (Zend_Registry::isRegistered ( 'usrInfo' ))
			return Zend_Registry::get ( 'usrInfo' );
		
		return array ('status' => 'false' );
	}
}