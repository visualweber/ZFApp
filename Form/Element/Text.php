<?php
require_once 'Zend/Form/Element/Text.php';

class App_Form_Element_Text extends Zend_Form_Element_Text {
    public function init() {
        $this->setDecorators(array(
             array('ViewHelper'),
             array('Description', array('escape' => false,
                                        'class' => 'fieldDescription')),
             array('Errors'),
             array('HtmlTag', array('tag' => 'dd')),
             array('Label', array('requiredSuffix' => ' *',
                                  'tag' => 'dt',
                                  'escape' => false))
        ));
    }
}