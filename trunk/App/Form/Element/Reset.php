<?php
require_once 'Zend/Form/Element/Reset.php';

class App_Form_Element_Reset extends Zend_Form_Element_Reset {
    public function init() {
        $this->addDecorator('ViewHelper');
    }
}