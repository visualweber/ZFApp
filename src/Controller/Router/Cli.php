<?php
/**
 * @see Zend_Controller_Request_Abstract
 */
require_once 'Zend/Controller/Router/Interface.php';

/**
 * App_Controller_Request_Cli
 *
 * @category    App
 * @package    App.Platform
 * @subpackage Router
 * @see http://blog.astrumfutura.com/archives/418-The-Mysteries-Of-Asynchronous-Processing-With-PHP-Part-2-Making-Zend-Framework-Applications-CLI-Accessible.html
 */
class App_Controller_Router_Cli implements Zend_Controller_Router_Interface
{
    public function route(Zend_Controller_Request_Abstract $dispatcher){}
    public function assemble($userParams, $name = null, $reset = false, $encode = true){}
    public function getFrontController(){}
    public function setFrontController(Zend_Controller_Front $controller){}
    public function setParam($name, $value){}
    public function setParams(array $params){}
    public function getParam($name){}
    public function getParams(){}
    public function clearParams($name = null){}
}