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
require_once 'Zend/Auth/Storage/Interface.php';

/**
 * @category    App
 * @package    App.Platform
 * @subpackage 	Storage
 * @copyright  	Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    	http://framework.zend.com/license/new-bsd     New BSD License
 * @desc 		Session will be save to database
 */
class App_Auth_Storage_Db implements App_Auth_Storage_Interface {
	/**
	 * Storage object member / from base on model
	 *
	 * @var mixed
	 */
	protected $_storage;
	
	/**
	 * Specific session_id in database
	 * @var String
	 */
	protected $_session_id;
	
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
	
	// Check session in database
	public function isEmpty() {
		return $this->_storage->isEmpty ( $this->_session_id );
	}
	
	/**
	 * Read session from database
	 * @see library/Zend/Auth/Storage/Zend_Auth_Storage_Interface#read()
	 */
	public function read() {
		return $this->_storage->read ( $this->_session_id );
	}
	
	/**
	 * Write session to database
	 * @see library/Zend/Auth/Storage/Zend_Auth_Storage_Interface#write($contents)
	 */
	public function write() {
	}
	
	public function clear() {
		$this->_storage->clear ( $this->_session_id );
	}
}