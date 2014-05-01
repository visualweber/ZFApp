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

class App_Auth extends Zend_Auth {
	
	/**
	 * Specific platform
	 * @var Integer
	 */
	protected $_client = 0;
	protected static $_session_id = '';
	
	/**
	 * recognizes a valid session by checking certain additional information stored in the session
	 * often recommended as protection against session fixation/hijacking - but doesnt make much sense
	 * Zend-Framework supports session validators to validate sessions
	 * @return unknown_type
	 */
	public function __construct() {
		try {
			if (! Zend_Session::isStarted ())
				Zend_Session::start ();
		} catch ( Zend_Session_Exception $e ) {
			Zend_Session::destroy ();
			Zend_Session::start ();
			Zend_Session::regenerateId ();
		}
		Zend_Session::registerValidator ( new Zend_Session_Validator_HttpUserAgent ( ) );
	}
	
	public function setClient($client = 0) {
		if ($client) {
			$this->_client = $client;
		}
	}
	/**
	 * Returns an instance of Zend_Auth
	 *
	 * Singleton pattern implementation
	 *
	 * @return Zend_Auth Provides a fluent interface
	 */
	public static function getInstance() {
		if (null === self::$_instance) {
			self::$_instance = new self ( );
		}
		try {
			if (! Zend_Session::isStarted ())
				Zend_Session::start ();
		} catch ( Zend_Session_Exception $e ) {
			echo "Cannot instantiate this namespace since \$lsession was created\n";
		}
		self::$_session_id = session_id ();
		return self::$_instance;
	}
	/**
	 * Returns true if and only if an identity is available from storage
	 *
	 * @return boolean
	 */
	public function hasIdentity() {
		
		return ! $this->getStorage ()->isEmpty ( $this->_client );
	}
	
	/**
	 * Returns the identity from storage or null if no identity is available
	 *
	 * @return mixed|null
	 */
	public function getIdentity() {
		$storage = $this->getStorage ();
		
		if ($storage->isEmpty ( $this->_client )) {
			return null;
		}
		
		return $storage->read ( $this->_client );
	}
	
	/**
	 * Clears the identity from persistent storage
	 *
	 * @return void
	 */
	public function clearIdentity() {
		$this->getStorage ()->clear ( $this->_client );
	}
}
