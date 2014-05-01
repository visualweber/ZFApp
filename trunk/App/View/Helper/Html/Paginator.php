<?php
/**
 * Here're your description about this file and its function
 *
 * @version			$Id: Pagination.php 29-07-2010 02:00:02$
 * @category    App
 * @package    App.Platform
 * @subpackage		subpackage
 * @license			http://xgoon.com
 * @copyright		Copyright (c) 2005-2011 XGOON MEDIA
 * @author			Toan LE <contact@xgoon.com>
 * @implements		Toan LE
 * @file			Pagination.php
 *
 *
 * @notes			I added prefix is "custo" to avoid mistakes or misconceptions which's support by Zend
 * 					And suggest everyone whom using Kernel classes must be focus thess points
 * 					Thanks!
 *
 */

require_once 'Kernel/Admin/View/Helper/Abstract.php';
class App_Admin_View_Helper_Html_Paginator extends AFAdmin_View_Helper_Abstract
{
    public $view;
    /**
     * Default view partial
     *
     * @var string|array
     */
    protected static $_defaultViewPartial = null;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    public function Paginator(Zend_Paginator $paginator = null, $partial = null, $params = null, $scrollingStyle = null)
    {
        if ($paginator === null)
        {
            if (isset ( $this->view->paginator ) and $this->view->paginator !== null and $this->view->paginator instanceof Zend_Paginator)
            {
                $paginator = $this->view->paginator;
            }
            else
            {
                /**
                 * @see Zend_View_Exception
                 */
                require_once 'Zend/View/Exception.php';

                $e = new Zend_View_Exception ( 'No paginator instance provided or incorrect type' );
                $e->setView ( $this->view );
                throw $e;
            }
        }

        if ($partial === null)
        {
            if (self::$_defaultViewPartial === null)
            {
                /**
                 * @see Zend_View_Exception
                 */
                require_once 'Zend/View/Exception.php';
                $e = new Zend_View_Exception ( 'No view partial provided and no default set' );
                $e->setView ( $this->view );
                throw $e;
            }

            $partial = self::$_defaultViewPartial;
        }

        $pages = get_object_vars ( $paginator->getPages ( $scrollingStyle ) );

        if ($params !== null)
        {
            $pages = array_merge ( $pages, ( array ) $params );
        }

        if (is_array ( $partial ))
        {
            if (count ( $partial ) != 2)
            {
                /**
                 * @see Zend_View_Exception
                 */
                require_once 'Zend/View/Exception.php';
                $e = new Zend_View_Exception ( 'A view partial supplied as an array must contain two values: the filename and its module' );
                $e->setView ( $this->view );
                throw $e;
            }

            if ($partial [1] !== null)
            {
                return $this->view->partial ( $partial [0], $partial [1], $pages );
            }

            $partial = $partial [0];
        }

        echo $this->view->partial ( $partial, $paginator, $pages );
    }

    /**
     * Sets the default view partial.
     *
     * @param string|array $partial View partial
     */
    public static function setDefaultViewPartial($partial)
    {
        self::$_defaultViewPartial = $partial;
    }

    /**
     * Gets the default view partial
     *
     * @return string|array
     */
    public static function getDefaultViewPartial()
    {
        return self::$_defaultViewPartial;
    }

    /**
     * Clone the current View
     *
     * @return Zend_View_Interface
     */
    public function cloneView()
    {
        $view = clone $this->view;
        $view->clearVars ();
        return $view;
    }
}