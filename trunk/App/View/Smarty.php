<?php
/**
 * Here're your description about this file and its function
 *
 * @version			$Id: Smarty.php Aug 17, 2010 2:04:02 PM$
 * @category    App
 * @package    App.Platform
 * @subpackage		subpackage
 * @license			http://xgoon.com
 * @copyright		Copyright (c) 2005-2011 XGOON MEDIA
 * @author			toan@xgoon.com (toan)
 * @implements		Toan LE
 * @file			Smarty.php
 *
 */

require_once 'Zend/View.php';

require_once 'Smarty/Smarty.class.php';

class App_View_Smarty extends Zend_View_Abstract {
	protected $_smarty;
	
	protected $_plugins;
	
	/**
	 * Constructor
	 *
	 * Pass it a an array with the following configuration options:
	 *
	 * scriptPath: the directory where your templates reside
	 * compileDir: the directory where you want your compiled templates (must be
	 * writable by the webserver)
	 * compile_dir: the directory where your configuration files reside
	 *
	 * both scriptPath and compileDir are mandatory options, as Smarty needs
	 * them. You can't set a cacheDir, if you want caching use Zend_Cache
	 * instead, adding caching to the view explicitly would alter behaviour
	 * from Zend_View.
	 *
	 * @see Zend_View::__construct
	 * @param array $config ["scriptPath" => /path/to/templates,
	 *			     "compileDir" => /path/to/compileDir,
	 *			     "compile_dir"  => /path/to/compile_dir ]
	 * @throws Exception
	 */
	public function __construct($config = array()) {
		$this->_smarty = new Smarty ( );
		
		$config = array ( 
    		"compileDir" =>  PATH_ROOT . '/data/templates_c/pay.like.vn', 
    		"compile_dir" => '/path/to/compile_dir',
		    "cache_dir" =>PATH_ROOT . '/data/cache/pay.like.vn'
		);
		
		if (! array_key_exists ( 'compileDir', $config )) {
			throw new Zend_Exception ( 'compileDir must be set in $config for ' . get_class ( $this ) );
		} else {
		    //compile dir must be set
		    $this->_smarty->compile_dir = $config ['compileDir'];
		}
		$this->_smarty->caching = 0;
		$this->_smarty->debugging = true;
		if (array_key_exists ( 'configDir', $config )) {
			$this->_smarty->config_dir = $config ['configDir'];
		}
		
		parent::__construct ( $config );
		
		$this->_plugins = new App_View_Smarty_Plugin_Broker ( $this );
		$this->registerPlugin ( new App_View_Smarty_Plugin_Standard ( ) );
	
	}
	
	/**
	 * Return the template engine object
	 *
	 * @return Smarty
	 */
	public function getEngine() {
		return $this->_smarty;
	}
	
	/**
	 * register a new plugin
	 *
	 * @param App_View_Smarty_Plugin_Abstract
	 */
	public function registerPlugin(App_View_Smarty_Plugin_Abstract $plugin, $stackIndex = null) {
		$this->_plugins->registerPlugin ( $plugin, $stackIndex );
		return $this;
	}
	
	/**
	 * Unregister a plugin
	 *
	 * @param string|App_View_Smarty_Plugin_Abstract $plugin Plugin object or class name
	 */
	public function unRegisterPlugin($plugin) {
		$this->_plugins->registerPlugin ( $plugin );
		return $this;
	}
	
	/**
	 * fetch a template, echos the result,
	 *
	 * @see Zend_View_Abstract::render()
	 * @param string $name the template
	 * @return void
	 */
	protected function _run() {
		$this->strictVars ( true );
		$vars = get_object_vars ( $this );
		foreach ( $vars as $key => $value ) {
			if ('_' != substr ( $key, 0, 1 )) {
				$this->_smarty->assign ( $key, $value );
			}
		}
		//assign variables to the template engine
		$this->_smarty->assign_by_ref ( 'this', $this );
		//why 'this'?
		//to emulate standard zend view functionality
		//doesn't mess up smarty in any way
		$scriptPaths = $this->getScriptPaths ();
		$path = array_shift ( $scriptPaths );

		$file = substr ( func_get_arg ( 0 ), strlen ( $path ) );
		//smarty needs a template_dir, and can only use templates,
		//found in that directory, so we have to strip it from the filename
		$this->_smarty->template_dir = $path;
		
		//set the template diretory as the first directory from the path
		echo $this->_smarty->fetch ( $file );
		//process the template (and filter the output)
	}
}
