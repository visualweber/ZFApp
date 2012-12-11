<?php

class App_Application_Resource_SmtpServer extends Zend_Application_Resource_ResourceAbstract
{
    public function init ()
    {
        $options = $this->getOptions();
        $transport = new Zend_Mail_Transport_Smtp($options['server'], $options);

        return $transport;
    }
}