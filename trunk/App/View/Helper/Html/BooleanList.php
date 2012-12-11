<?php
/**
 * Here're your description about this file and its function
 *
 * @version			$Id: UnPublish.php Jul 27, 2010 2:07:54 PM$
 * @category		Kernel/Admin
 * @package			AFAdmin_View_Helper_Abstract Package
 * @subpackage		App_Admin_View_Helper_Html_Grid
 * @license			http://xgoon.com
 * @copyright		Copyright (c) 2005-2011 XGOON MEDIA
 * @author			toan@xgoon.com (toan)
 * @file			Grid.php
 *
 */

class App_View_Helper_Html_BooleanList extends App_Admin_View_Helper_Abstract
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    /**
     * Generates a yes/no radio list
     *
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param mixed The key that is selected
     * @returns string HTML for the radio list
     */
    function booleanlist($name, $attribs = null, $selected = null, $yes = 'yes', $no = 'no', $id = false)
    {
        $arr = array (
        App_View_Helper_Html_Option::option ( '0', $no ),
        App_View_Helper_Html_Option::option ( '1', $yes  ) );
        return JHTML::_ ( 'select.radiolist', $arr, $name, $attribs, 'value', 'text', ( int ) $selected, $id );
    }
}