<?php
require_once 'App/Form/Element/Text.php';

class App_Form_Element_Text_Email extends App_Form_Element_Text {
    public function init() {
        // Call up our parents init method as well
        parent::init();

        // Add a validator
        $this->addValidator('EmailAddress');
    }
}