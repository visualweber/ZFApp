<?php
/**
 * Here're your description about this file and its function
 *
 * @version			$Id: UnPublish.php Jul 27, 2010 2:07:54 PM$
 * @category    App
 * @package    App.Platform
 * @subpackage		App_Admin_View_Helper_Html_Grid
 * @license			http://xgoon.com
 * @copyright		Copyright (c) 2005-2011 XGOON MEDIA
 * @author			toan@xgoon.com (toan)
 * @file			Grid.php
 *
 */

class App_View_Helper_Html_IntegerList extends App_Admin_View_Helper_Abstract
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    /**
     * Generates a select list of integers
     *
     * @param int The start integer
     * @param int The end integer
     * @param int The increment
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param mixed The key that is selected
     * @param string The printf format to be applied to the number
     * @returns string HTML for the select list
     */
    function integerlist($start, $end, $inc, $name, $attribs = null, $selected = null, $format = "")
    {
        $start = intval ( $start );
        $end = intval ( $end );
        $inc = intval ( $inc );
        $arr = array ();

        for($i = $start; $i <= $end; $i += $inc)
        {
            $fi = $format ? sprintf ( "$format", $i ) : "$i";
            $arr [] = App_View_Helper_Html_Option::option ( $fi, $fi );
        }

        return App_View_Helper_Html_options::options ( $arr, $name, $attribs, 'value', 'text', $selected );
    }
}