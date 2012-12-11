<?php
/**
 * Base authentication 
 */

abstract class App_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
	/**
	 * @var Zend_Auth
	 */
	protected $_auth;	
 
	public function __construct(Zend_Auth $auth)
	{
		$this->_auth = $auth;
	}

}