<?php
class App_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {
        $options = $this->getOptions();
        return $this;
    }
}