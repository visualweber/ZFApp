<?php
/**
 * Kernel Library
 *
 * @category   Kernel
 * @package    App_Application
 * @subpackage Resource
 * @see http://blog.vandenbos.org/2009/07/07/zend-framework-module-config/
 */

/**
 * Resource for loading plugins per module
 *
 * @uses       Zend_Application_Resource_Base
 * @category   Kernel
 * @package    App_Application
 * @subpackage Resource
 * @see http://blog.vandenbos.org/2009/07/07/zend-framework-module-config/
 */
class App_Application_Resource_Moduleplugins extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Initialize resource
     *
     * @return mixed
     */
    public function init()
    {
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('frontcontroller');
        $front = $bootstrap->getResource('frontcontroller');
        $pluginLoader = $front->getPlugin('App_Controller_Plugin_PluginLoader');
        $options = $this->getOptions();

        foreach ($options as $pluginName) {
            $pluginLoader->registerFrontControllerPlugin(
            strtolower($this->getBootstrap()->getModuleName()), $pluginName);
        }
    }
}