<?php
require_once 'Zend/Form/Element/Submit.php';

class App_Form_Element_Submit extends Zend_Form_Element_Submit {
    public function init() {
    	$this->setDisableLoadDefaultDecorators(true);
    	$this->addDecorator("ViewHelper");
    }
}