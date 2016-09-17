<?php
class App_Form_Form extends Zend_Form {
    public $settings;

    public function __construct($options = null) {
        if (Zend_Registry::isRegistered('config') && $config = Zend_Registry::get('config')):
            if (isset($config ['settings']) && $config ['settings']):
                $this->settings = $config ['settings'];
            endif;
        endif;
        
        parent::__construct($options);
    }

}
