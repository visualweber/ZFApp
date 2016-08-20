<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Auth
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Auth.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

/**
 * @category   Zend
 * @package    Zend_Auth
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class App_Auth extends Zend_Auth {

    protected $_client = 'viwebcms-backend';
    protected static $_session_id = '';

    /**
     * Returns an instance of Zend_Auth
     *
     * Singleton pattern implementation
     *
     * @return Zend_Auth Provides a fluent interface
     */
    public static function getInstance() {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        self::$_instance = new self();
        try {
            if (!Zend_Session::isStarted()):
                Zend_Session::start();
            endif;
        } catch (Zend_Session_Exception $e) {
            echo $e->getFile();
            echo $e->getMessage();
            echo $e->getTrace();
        }
        self::$_session_id = session_id();
        return self::$_instance;
    }

    public function setClient($client = 0) {
        if ($client) {
            $this->_client = $client;
        }
    }

}
