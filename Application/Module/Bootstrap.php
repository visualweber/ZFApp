<?php
class App_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap {
	
	/**
	 * Constructor
	 *
	 * @param $application Zend_Application|Zend_Application_Bootstrap_Bootstrapper       	
	 * @return void
	 */
	public function __construct($application) {
		parent::__construct ( $application );
		$this->init ();
	}
	
	/**
	 * Initialize
	 *
	 * @return void
	 */
	public function init() {
		$this->registerPluginResource ( 'ModuleConfig' );
		$this->_executeResource ( 'ModuleConfig' );
	}

}