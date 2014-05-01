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

include_once "Zend/Application.php";
include_once "Zend/Registry.php";
include_once "Zend/Cache.php";
include_once "Zend/Auth.php";

class App_Application extends Zend_Application {
	/**
	 * Singleton instance
	 *
	 * Marked only as protected to allow extension of the class. To extend,
	 * simply override {@link getInstance()}.
	 *
	 * @var App_Application
	 */
	protected static $_instance = null;
	
	/**
	 * doogleonduty application cache
	 *
	 * @var Zend_Cache
	 */
	protected $_app_cache;
	/**
	 * Constructor
	 *
	 * Initialize application. Potentially initializes include_paths, PHP
	 * settings, and bootstrap class.
	 *
	 * @param $environment string       	
	 * @param $options string|array|Zend_Config
	 *       	 String path to configuration file, or array/Zend_Config of
	 *       	 configuration options
	 * @throws Zend_Application_Exception When invalid options are provided
	 * @return void
	 */
	public function __construct($environment, $options = null) {
		$this->initCache ();
		parent::__construct ( $environment, $options );
	}
	/**
	 * init the caching for doogle application
	 */
	protected function initCache() {
		$frontendOpts = array (
				'caching' => true, 'lifetime' => 1800, 'automatic_serialization' => true );
		
		$backendOpts = array (
				'cache_dir' => PATH_PROJECT . '/data/cache', 'servers' => array (
						array (
								'host' => 'localhost', 'port' => 11211 ) ), 'compression' => false );
		
		if (is_null ( $this->_app_cache )) {
			if (Zend_Registry::isRegistered ( 'app_config_cache' )) {
				$this->_app_cache = Zend_Registry::get ( 'app_config_cache' );
			} else {
				$this->_app_cache = Zend_Cache::factory ( 'Core', 'File', $frontendOpts, $backendOpts );
				Zend_Registry::set ( 'app_config_cache', $this->_app_cache );
			}
		}
	}
	/**
	 * Load configuration from file
	 */
	protected function loadConfigFile($file) {
		
		$environment = $this->getEnvironment ();
		$suffix = strtolower ( pathinfo ( $file, PATHINFO_EXTENSION ) );
		
		switch ($suffix) {
			case 'ini' :
				$config = new Zend_Config_Ini ( $file, $environment, array (
						'allowModifications' => true ) );
				break;
			case 'xml' :
				$config = new Zend_Config_Xml ( $file, $environment, array (
						'allowModifications' => true ) );
				break;
			case 'php' :
			case 'inc' :
				$config = include $file;
				if (! is_array ( $config )) {
					throw new Zend_Application_Exception ( 'Invalid configuration file provided; PHP file does not return array value' );
				}
				return $config;
				break;
			default :
				throw new Zend_Application_Exception ( 'Invalid configuration file provided; unknown config type' );
		}
		
		if ($config instanceof Zend_Config) {
			$ini_path = $config->get ( 'ini_path_extends', '' );
			if ($ini_path) {
				$dir = new DirectoryIterator ( $ini_path );
				foreach ( $dir as $fileinfo ) {
					if (! $fileinfo->isDot () && $fileinfo->isFile ()) {
						if (strpos ( $fileinfo->getPathname (), '.ini' ) !== false) {
							$t_config = new Zend_Config_Ini ( $fileinfo->getPathname (), $environment );
							$config->merge ( $t_config );
						}
					}
				}
			
			}
		}
		
		$config->__unset ( 'ini_path_extends' );
		return $config;
	}
	/**
	 * Load configuration file of options
	 *
	 * @param $file string       	
	 * @throws Zend_Application_Exception When invalid configuration file is
	 *         provided
	 * @return array
	 */
	protected function _loadConfig($file) {
		$environment = $this->getEnvironment ();
		
		$suffix = strtolower ( pathinfo ( $file, PATHINFO_EXTENSION ) );
		$index = '_app_cache' . $environment . $suffix;
		// $index = base64_encode($index);
		
		if ($environment != 'production') {
			$config = $this->loadConfigFile ( $file )->toArray ();
		} else {
			// load configuration from cache for other environments
			if (! $config = $this->_app_cache->load ( $index )) {
				$config = $this->loadConfigFile ( $file );
				$return = $config->toArray ();
				$this->_app_cache->save ( $config->toArray (), $index );
			}
		}
		
		Zend_Registry::set ( 'config', $config );
		return $config;
	}
	
	public static function autoload($path) {
		$in_path = str_replace ( '_', '/', $path ) . '.php';
		if (file_exists ( PATH_LIB . DS . $in_path )) {
			include_once (PATH_LIB . DS . $in_path);
		} elseif (file_exists ( PATH_LIB . DS . $in_path )) {
			include_once (PATH_LIB . DS . $in_path);
		}
		return $path;
	}
	/**
	 * Set bootstrap path/class
	 *
	 * @param $path string       	
	 * @param $class string       	
	 * @return Zend_Application
	 */
	public function setBootstrap($path, $class = null) {
		// setOptions() can potentially send a null value; specify default
		// here
		if (null === $class) {
			$class = 'Bootstrap';
		}
		if (! class_exists ( $class, false )) {
			require_once $path;
			if (! class_exists ( $class, false )) {
				throw new Zend_Application_Exception ( 'Bootstrap class not found' );
			}
		}
		if (method_exists ( $class, 'getInstance' )) {
			$this->_bootstrap = call_user_func ( array (
					$class, 'getInstance' ), $this );
		} else {
			$this->_bootstrap = new $class ( $this );
		}
		if (! $this->_bootstrap instanceof Zend_Application_Bootstrap_Bootstrapper) {
			throw new Zend_Application_Exception ( 'Bootstrap class does not implement Zend_Application_Bootstrap_Bootstrapper' );
		}
		
		return $this;
	}
}
