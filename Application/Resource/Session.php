<?php

/**
 * Sitengine - Zend Framework & Doctrine 2 & Dojo & Compass Integration
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @package    Sitengine
 * @copyright  Copyright (c) 2009, Christian Hoegl, Switzerland (http://sitengine.org)
 * @license    http://sitengine.org/license/new-bsd     New BSD License
 */


require_once 'Zend/Application/Resource/Session.php';


class App_Application_Resource_Session extends Zend_Application_Resource_Session
{


    /**
     * Get session save handler
     *
     * @return Zend_Session_SaveHandler_Interface
     */
    public function getSaveHandler()
    {
        return parent::getSaveHandler();
        /*
         // experimental: doctrine session handler
         $entityManager = $this->getBootstrap()->bootstrap('doctrine')->getResource('doctrine');

         require_once 'Xzend/Session/SaveHandler/Doctrine.php';
         $this->_saveHandler = new Xzend_Session_SaveHandler_Doctrine($this->_saveHandler);
         $this->_saveHandler->setEntityManager($entityManager);
         return $this->_saveHandler;
         */
    }

}