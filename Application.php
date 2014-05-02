<?php

/**
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
        parent::__construct($environment, $options);
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
            $ini_path = $config->get('ini_path_extends', '');

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
        }

        $config->__unset('ini_path_extends');
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
        $index = 'application_cache_' . $environment . '_' . $suffix;
        if ($environment != 'production') {
            $config = $this->loadConfigFile($file)->toArray();
        } else {
            // load configuration from cache for other environments
            if (!$config = $this->_app_cache->load($index)) {
                $config = $this->loadConfigFile($file);
                $this->_app_cache->save($config->toArray(), $index);
            }
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
