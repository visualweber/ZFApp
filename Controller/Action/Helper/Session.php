<?php
/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * App_Controller_Action_Helper_Cache
 *
 * @category    App
 * @package    App.Platform
 * @subpackage Action
 * @see http://blog.astrumfutura.com/archives/381-Zend-Framework-Page-Caching-Part-2-Controller-Based-Cache-Management.html
 */
class App_Controller_Action_Helper_Session extends Zend_Controller_Action_Helper_Abstract
{
    public function save()
    {
        //check login status
        $auth = Zend_Auth::getInstance ();
        $storage = new App_Auth_Storage_Db ( new Model_Session ( ) );
        $auth->setStorage ( $storage );
        if(!$auth->hasIdentity ()){
            //save session
            $obj			=	new stdClass();
            $obj->client_id =   0;
            $obj->id 		=   0;
            $obj->is_admin	=	0;
            $obj->username 	=	null;
            $obj->guest     =   1;
            $auth->getStorage()->write($obj);

            $cache			=	Zend_Registry::get ( 'cache');
            $hits			=	$cache->load('hits');
            $hits			=	(int)$hits + 1;
            $cache->save($hits, 'hits');
        }

    }
}