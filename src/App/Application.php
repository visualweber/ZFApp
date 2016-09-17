<?php

/**
 * Visual Weber is a software development company
 * specializing in Web Application, Mobile Application and Multimedia. Visual Weber ZFCMS's combination of experience
 * and specialization on Internet technologies extends our customers' competitive
 * advantage and helps them maximize their return on investment. We aim to realize
 * your company's goals and vision though ongoing communication and our commitment
 * to quality.
 *
 * @category 	App
 * @package 	App.Platform
 * @copyright 	Copyright (c) 2010-2014 Visual Weber.
 * @license 	http://www.visualweber.com
 * @version 	App version 1.0.0
 * @author 	Visual Weber <contact@visualweber.com>
 * @implement 	All Visual Weber members
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
     *
     * @var Zend_Cache_Core|null
     */
    protected $_configCache;

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
        $this->initCache();
        parent::__construct($environment, $options);

        //Zend_Loader_Autoloader::getInstance()->setDefaultAutoloader(array('MIIS_Bootstrap', 'autoload'));
        //$auth_cookie = $this->getOption('miis_app');
        //if(isset($auth_cookie['auth']['use_cookie']) && $auth_cookie['auth']['use_cookie']){
        //    Zend_Auth::getInstance()->setStorage(new MIIS_Auth_Storage_Cookie())->getStorage()->setDomain($auth_cookie['cookie']['domain'])->setExpiration(0);   
        //}         
    }

    protected function _cacheId($file) {
        return md5($file . '_' . $this->getEnvironment());
    }

    /**
     * init the caching for doogle application
     *
     */
    protected function initCache() {
//        $frontendOpts = array('caching' => true, 'lifetime' => 1800, 'automatic_serialization' => true);
//        $backendOpts = array('cache_dir' => PATH_PROJECT . '/data/cache', 'servers' => array(array('host' => 'localhost', 'port' => 11211)), 'compression' => false);
//        if (is_null($this->_configCache)) {
//            if (Zend_Registry::isRegistered('application_cache')) {
//                $this->_configCache = Zend_Registry::get('application_cache');
//            } else {
//                $this->_configCache = Zend_Cache::factory('Core', 'File', $frontendOpts, $backendOpts);
//                Zend_Registry::set('application_cache', $this->_configCache);
//            }
//        }
    }

    /**
     * Load configuration from file
     */
    private function loadConfigFile($file) {
        $environment = $this->getEnvironment();
        $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        switch ($suffix) {
            case 'ini' :
                $config = new Zend_Config_Ini($file, $environment, array(
                    'allowModifications' => true));
                break;
            case 'xml' :
                $config = new Zend_Config_Xml($file, $environment, array(
                    'allowModifications' => true));
                break;
            case 'php' :
            case 'inc' :
                $config = include $file;
                if (!is_array($config)) {
                    throw new Zend_Application_Exception('Invalid configuration file provided; PHP file does not return array value');
                }
                return $config;
                break;
            default :
                throw new Zend_Application_Exception('Invalid configuration file provided; unknown config type');
        }

        if ($config instanceof Zend_Config) {
            $ini_path = PATH_CONFIG . DS . APPLICATION_ENV;
            if ($ini_path) {
                $dir = new DirectoryIterator($ini_path);
                foreach ($dir as $fileinfo) {
                    if (!$fileinfo->isDot() && $fileinfo->isFile()) {
                        if (strpos($fileinfo->getPathname(), '.ini') !== false) {
                            $t_config = new Zend_Config_Ini($fileinfo->getPathname(), $environment);
                            $config->merge($t_config);
                        }
                    }
                }
            }

            $ini_path_app = APPLICATION_PATH . DS . 'config' . DS . APPLICATION_ENV;
            if ($ini_path_app) {
                $dir = new DirectoryIterator($ini_path_app);
                foreach ($dir as $fileinfo) {
                    if (!$fileinfo->isDot() && $fileinfo->isFile()) {
                        if (strpos($fileinfo->getPathname(), '.ini') !== false) {
                            $t_config = new Zend_Config_Ini($fileinfo->getPathname(), $environment);
                            $config->merge($t_config);
                        }
                    }
                }
            }
        }
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
        $environment = $this->getEnvironment();
        $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        // $index = 'application_cache_' . $environment . '_' . $suffix;
        $index = 'application_cache';

        /*
          if ($this->_configCache === null || $suffix == 'php' || $suffix == 'inc') { //No need for caching those
          return parent::_loadConfig($file);
          }
          $configMTime = filemtime($file);
          $cacheId = $this->_cacheId($file);
          $cacheLastMTime = $this->_configCache->test($cacheId);
          if ($cacheLastMTime !== false && $configMTime < $cacheLastMTime) { //Valid cache?
          return $this->_configCache->load($cacheId, true);
          } else {
          $config = parent::_loadConfig($file);
          $this->_configCache->save($config, $cacheId, array(), null);

          return $config;
          } */

        if ($environment != 'production') {
            $config = $this->loadConfigFile($file)->toArray();
        } else {
            if ($this->_configCache):
                // load configuration from cache for other environments
                if (!$config = $this->_configCache->load($index, true)):
                    $config = $this->loadConfigFile($file);
                    $this->_configCache->save($config->toArray(), $index);
                endif;
            else:
                $config = $this->loadConfigFile($file)->toArray();
            endif;
        }

        Zend_Registry::set('config', $config);

        return $config;
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
        if (!class_exists($class, false)) {
            require_once $path;
            if (!class_exists($class, false)) {
                throw new Zend_Application_Exception('Bootstrap class not found');
            }
        }
        if (method_exists($class, 'getInstance')) {
            $this->_bootstrap = call_user_func(array(
                $class, 'getInstance'), $this);
        } else {
            $this->_bootstrap = new $class($this);
        }
        if (!$this->_bootstrap instanceof Zend_Application_Bootstrap_Bootstrapper) {
            throw new Zend_Application_Exception('Bootstrap class does not implement Zend_Application_Bootstrap_Bootstrapper');
        }

        return $this;
    }

}
