<?php
/**
 * Load specific layout per module
 *
 * @category   Kernel
 * @package    App_Controller
 * @subpackage Plugin
 * @see  http://blog.vandenbos.org/2009/07/19/zend-framework-module-specific-layout/
 */
class App_Controller_Plugin_LayoutLoader extends Zend_Controller_Plugin_Abstract
{
    /**
     * Called before an action is dispatched by Zend_Controller_Dispatcher.
     *
     * @param  Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $config     = $bootstrap->getOptions();
        $moduleName = $request->getModuleName();

        if (isset($config[$moduleName]['resources']['layout']['layout'])) {
            $layoutScript = $config[$moduleName]['resources']['layout']['layout'];
            Zend_Layout::getMvcInstance()->setLayout($layoutScript);
        }

        if (isset($config[$moduleName]['resources']['layout']['layoutPath'])) {
            $layoutPath = $config[$moduleName]['resources']['layout']['layoutPath'];
            $moduleDir = Zend_Controller_Front::getInstance()->getModuleDirectory();
            Zend_Layout::getMvcInstance()->setLayoutPath(
            $moduleDir. DIRECTORY_SEPARATOR .$layoutPath
            );
        }

    }
}