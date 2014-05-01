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
class App_Auth_Storage_Cookie implements App_Auth_Storage_Interface {
	
	/**
	 * @var string
	 */
	protected $_basedir = '/';
	/**
	 * @var string
	 */
	protected $_cookieName = 'ASCORE_APP_AUTH';
	/**
	 * @var string
	 */
	protected $_domain;
	/**
	 * @var string
	 */
	protected $_secret = '@d0d!!!';
	/**
	 * @var mixed
	 */
	protected $_cached;
	/**
	 * 
	 * @var mixed
	 */
	protected $_remember = 0; //to end session
	/**
	 * Constructor
	 *
	 * @param string $cookieName The name of the cookie where the identity is stored
	 * @param string $secret The salt used in the hashing of the data
	 */
	public function __construct($cookieName = null, $secret = null, $remember = null) {
		if (null !== $cookieName) {
			$this->setCookieName ( $cookieName );
		}
		if (null !== $secret) {
			$this->setSecret ( $secret );
		}
		
		if (null !== $remember) {
		}
	}
	/**
	 * The basedir of the cookie
	 *
	 * @return string
	 */
	public function getBasedir() {
		return $this->_basedir;
	}
	/**
	 * Set the basedir of the cookie
	 *
	 * @param string $basedir
	 * @return App_Auth_Storage_Cookie
	 */
	public function setBasedir($basedir) {
		$this->_basedir = $basedir;
		return $this;
	}
	/**
	 * Get the name of the cookie
	 *
	 * @return string
	 */
	public function getCookieName() {
		return $this->_cookieName;
	}
	/**
	 * Set the name of the cookie
	 *
	 * @param string $cookieName
	 * @return App_Auth_Storage_Cookie
	 */
	public function setCookieName($cookieName) {
		$this->_cookieName = $cookieName;
		return $this;
	}
	/**
	 * Get the (sub)domain the cookie is set for
	 *
	 * @return string
	 */
	public function getDomain() {
		return $this->_domain;
	}
	/**
	 * Set the (sub)domain the cookie is set for
	 *
	 * @param string $domain
	 * @return App_Auth_Storage_Cookie
	 */
	public function setDomain($domain) {
		$this->_domain = $domain;
		return $this;
	}
	/**
	 * Get the expiration time the cookie is set for
	 *
	 * @return string
	 */
	public function getExpiration() {
		return $this->_remember;
	}
	/**
	 * Set the expiration time the cookie is set for
	 *
	 * @param string $domain
	 * @return App_Auth_Storage_Cookie
	 */
	public function setExpiration($expiration) {
		$this->_remember = $expiration;
		return $this;
	}
	/**
	 * Get the Secret (or salt) used to store the data
	 *
	 * @return string
	 */
	public function getSecret() {
		return $this->_secret;
	}
	/**
	 * Set Secret (or salt) used to store the data
	 *
	 * @param string $secret
	 * @return App_Auth_Storage_Cookie
	 */
	public function setSecret($secret) {
		$this->_secret = $secret;
		return $this;
	}
	/**
	 * Returns true if and only if storage is empty
	 *
	 * @throws Zend_Auth_Storage_Exception If it is impossible to determine whether storage is empty
	 * @return boolean
	 */
	public function isEmpty() {
		return $this->read () == null;
	}
	/**
	 * Returns the contents of storage
	 *
	 * Behavior is undefined when storage is empty
	 *
	 * @throws Zend_Auth_Storage_Exception If reading contents from storage is impossible
	 * @return mixed
	 */
	public function read() {
		
		if (! $this->_cached) {
			if (array_key_exists ( $this->getCookieName (), $_COOKIE )) {
				$value = $_COOKIE [$this->getCookieName ()];
				list ( $contents, $now, $checksum ) = explode ( '|', $value );
				$contents = base64_decode ( $contents );
				if (md5 ( $contents . $now . $this->getSecret () ) == $checksum) {
					$this->_cached = $contents;
				}
			}
		}
		return json_decode ( $this->_cached );
	}
	/**
	 * Writes $contents to storage
	 *
	 * @param  mixed $contents
	 * @throws Zend_Auth_Storage_Exception If writing $contents to storage is impossible
	 * @return void
	 */
	public function write($contents) {
		$contents = json_encode ( $contents );
		
		$this->_cached = $contents;
		$now = time ();
		$expiration = 0;
		if ($this->getExpiration ()) {
			$expiration = $now + $this->getExpiration ();
		}
		
		$checksum = md5 ( $contents . $now . $this->getSecret () );
		$value = base64_encode ( $contents ) . '|' . $now . '|' . $checksum;
		if (! setcookie ( $this->getCookieName (), $value, $expiration, $this->getBasedir (), $this->getDomain (), null, null )) {
			throw new Zend_Auth_Storage_Exception ( 'Failed to set cookie' );
		}
	}
	/**
	 * Clears contents from storage
	 *
	 * @throws Zend_Auth_Storage_Exception If clearing contents from storage is impossible
	 * @return void
	 * setcookie ( 'authSSO', '', time () - 3600, '/', '.like.vn', 1 );
	 */
	public function clear() {
		if (! setcookie ( $this->getCookieName (), '', time () - $this->getExpiration () - 60 * 60, $this->getBasedir (), $this->getDomain (), null, null )) {
			throw new Zend_Auth_Storage_Exception ( 'Failed to clear cookie' );
		}
	}
}
