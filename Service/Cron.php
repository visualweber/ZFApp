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
 * Asian opensource solutions [xgoon) is a software development company
 * specializing in Web Application and Media. xgoon's combination of experience
 * and specialization on Internet technologies extends our customers' competitive
 * advantage and helps them maximize their return on investment. We aim to realize
 * your company's goals and vision though ongoing communication and our commitment
 * to quality.
 *
 * @category 	App
 * @package 	App.Platform
 * @copyright 	Copyright (c) 2005-2011 XGOON MEDIA.
 * @license 	http://www.xgoon.com
 * @version 	App version 1.0.0
 * @author 		toan@xgoon.com
 * @implement 	Name of developer
 */
class App_Service_Cron {
	protected $_loader;
	protected $_actions = array ();
	protected $_actionsArgs = array ();
	protected $_errors = array ();
	
	/**
	 * construtor
	 * 
	 * @param mixed $pluginPaths
	 * @return App_Service_Cron
	 */
	public function __construct(array $pluginPaths) {
		$this->_loader = new Zend_Loader_PluginLoader ( $pluginPaths );
	}
	
	/**
	 * Get loader
	 *
	 * @return Zend_Loader_PluginLoader
	 */
	public function getLoader() {
		return $this->_loader;
	}
	/**
	 * Runs all registered cron actions.
	 *
	 * @return string any errors that may have occurred
	 */
	public function run() {
		foreach ( $this->_actions as $key => $action ) {
			$class = $this->getLoader ()->load ( $action );
			if (null !== $this->_actionsArgs [$key]) {
				$action = new $class ( $this->_actionsArgs [$key] );
			} else {
				$action = new $class ();
			}
			
			if (! ($action instanceof App_Service_Cron_Interface)) {
				throw new App_Service_Cron_Exception ( 'One of the specified actions is not the right kind of class.' );
			}
			
			try {
				$action->run ();
			} catch ( App_Service_Cron_Exception $e ) {
				$this->addError ( $e->getMessage () );
			} catch ( Exception $e ) {
				if (APPLICATION_ENV == 'development') {
					$this->addError ( '[DEV]: ' . $e->getMessage () );
				} else {
					$this->addError ( 'An undefined error occurred. ' . $e->getMessage () );
				}
			}
		}
		
		$errors = $this->getErrors ();
		if (count ( $errors ) > 0) {
			$output = 'Cron errors:' . "\n\n";
			foreach ( $errors as $error ) {
				$output .= $error . "\n";
			}
		} else {
			$output = null;
		}
		
		return $output;
	}
	
	public function addAction($action, $args = null) {
		$key = count ( $this->_actions ) + 1;
		$this->_actions [$key] = $action;
		$this->_actionsArgs [$key] = $args;
		return $this;
	}
	
	public function addError($message) {
		$this->_errors [] = $message;
		return $this;
	}
	
	public function getErrors() {
		return $this->_errors;
	}
}