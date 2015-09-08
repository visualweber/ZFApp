<?php
/**
 * @see Zend_Controller_Request_Abstract
 */
require_once 'Zend/Controller/Request/Abstract.php';

/**
 * App_Controller_Request_Cli
 *
 * @uses Zend_Console_Getopt
 * @category    App
 * @package    App.Platform
 * @subpackage Request
 * @see http://blog.astrumfutura.com/archives/418-The-Mysteries-Of-Asynchronous-Processing-With-PHP-Part-2-Making-Zend-Framework-Applications-CLI-Accessible.html
 */
class App_Controller_Request_Cli extends Zend_Controller_Request_Abstract
{
    protected $_getopt = null;

    public function __construct(Zend_Console_Getopt $getopt)
    {
        $this->_getopt = $getopt;
        $getopt->parse();
        if ($getopt->{$this->getModuleKey()}) {
            $this->setModuleName($getopt->{$this->getModuleKey()});
        }
        if ($getopt->{$this->getControllerKey()}) {
            $this->setControllerName($getopt->{$this->getControllerKey()});
        }
        if ($getopt->{$this->getActionKey()}) {
            $this->setActionName($getopt->{$this->getActionKey()});
        }
    }

    public function getCliOptions()
    {
        return $this->_getopt;
    }
}