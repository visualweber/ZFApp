<?php
class App_Application_Resource_Client extends Zend_Application_Resource_ResourceAbstract {
	
	/**
	 * Initialize
	 *
	 * @return Zend_Acl
	 */
	public function init() {
        $options = $this->getOptions();
        var_dump($options); exit;
	}
}