<?php
require_once 'Zend/Form/Element/Textarea.php';

class App_Form_Element_Textarea extends Zend_Form_Element_Textarea {
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