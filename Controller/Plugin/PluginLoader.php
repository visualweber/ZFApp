<?php
/**
 * Load specific plugins per module
 *
 * @category   Kernel
 * @package    App_Controller
 * @subpackage Plugin
 * @see http://blog.vandenbos.org/2009/09/03/zend-framework-module-specific-frontcontroller-plugins/
 */
class App_Controller_Plugin_PluginLoader extends Zend_Controller_Plugin_Abstract
{
    protected $_modulePlugins = array();

    public function registerFrontControllerPlugin($module, $pluginName)
    {
        if (array_key_exists($module, $this->_modulePlugins)
        && is_array($this->_modulePlugins[$module])) {
            array_push($this->_modulePlugins[$module], $pluginName);
        } else {
            $this->_modulePlugins[$module] = array($pluginName);
        }
    }

    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        if (isset($this->_modulePlugins[$request->getModuleName()])) {
            $frontController = Zend_Controller_Front::getInstance();
            foreach ($this->_modulePlugins[$request->getModuleName()] as $pluginName) {
                $frontController->registerPlugin( new $pluginName);
            }
        }
    }

}