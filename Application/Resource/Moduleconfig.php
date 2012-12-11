<?php
class App_Application_Resource_ModuleConfig extends Zend_Application_Resource_ResourceAbstract {
	
	/**
	 * Initialize
	 *
	 * @return Zend_Config
	 */
	public function init() {
		return $this->_getModuleConfig ();
	}
	
	/**
	 * Load the module's config
	 *
	 * @return Zend_Config
	 */
	protected function _getModuleConfig() {
		$bootstrap = $this->getBootstrap ();
		if (! ($bootstrap instanceof Zend_Application_Module_Bootstrap)) {
			throw new Zend_Application_Exception ( 'Invalid bootstrap class' );
		}
		$path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . strtolower($bootstrap->getModuleName ()) . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR;
		
		$cfgdir = new DirectoryIterator ( $path );
		$modOptions = $this->getBootstrap ()->getOptions ();
		foreach ( $cfgdir as $file ) {
			if ($file->isFile ()) {
				$filename = $file->getFilename ();
				$options = $this->_loadOptions ( $path . $filename );
				if (($len = strpos ( $filename, '.' )) !== false) {
					$cfgtype = substr ( $filename, 0, $len );
				} else {
					$cfgtype = $filename;
				}
				if (strtolower ( $cfgtype ) == 'module') {
					$modOptions = array_merge ( $modOptions, $options );
				} else {
					$modOptions ['resources'] [$cfgtype] = $options;
				}
			}
		}

		$this->getBootstrap ()->setOptions ( $modOptions );
	}
	
	/**
	 * Load the config file
	 *
	 * @param $fullpath string       	
	 * @return array
	 */
	protected function _loadOptions($fullpath) {
		if (file_exists ( $fullpath )) {
			switch (substr ( trim ( strtolower ( $fullpath ) ), - 3 )) {
				case 'ini' :
					$cfg = new Zend_Config_Ini ( $fullpath, $this->getBootstrap ()->getEnvironment () );
					break;
				case 'xml' :
					$cfg = new Zend_Config_Xml ( $fullpath, $this->getBootstrap ()->getEnvironment () );
					break;
				default :
					throw new Zend_Config_Exception ( 'Invalid format for config file' );
					break;
			}
		} else {
			throw new Zend_Application_Resource_Exception ( 'File does not exist' );
		}
		return $cfg->toArray ();
	}

}