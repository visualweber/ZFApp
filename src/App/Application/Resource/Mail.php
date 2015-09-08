<?php

class App_Application_Resource_Mail extends Zend_Application_Resource_Mail {

    public function init() {
        return parent::init();
//        $options = $this->getOptions();
//        $transport = new Zend_Mail_Transport_Smtp($options['transport']['host'], $options['transport']);
//
//        Zend_Mail::setDefaultTransport($transport);
//        return $transport;
    }

}