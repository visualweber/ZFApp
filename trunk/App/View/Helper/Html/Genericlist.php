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

class App_View_Helper_Html_options extends App_Admin_View_Helper_Abstract
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    /**
     * Generates an HTML select list
     *
     * @param	array	An array of objects
     * @param	string	The value of the HTML name attribute
     * @param	string	Additional HTML attributes for the <select> tag
     * @param	string	The name of the object variable for the option value
     * @param	string	The name of the object variable for the option text
     * @param	mixed	The key that is selected (accepts an array or a string)
     * @returns	string	HTML for the select list
     */
    public function options($arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = NULL, $idtag = false, $translate = false)
    {
        if (is_array ( $arr ))
        {
            reset ( $arr );
        }

        if (is_array ( $attribs ))
        {
            $attribs = Util_ArrayHelper_ArrayHelper::toString ( $attribs );
        }

        $id = $name;

        if ($idtag)
        {
            $id = $idtag;
        }

        $id = str_replace ( '[', '', $id );
        $id = str_replace ( ']', '', $id );

        $html = '<select name="' . $name . '" id="' . $id . '" ' . $attribs . '>';
        $html .= App_View_Helper_Html_Options::Options ( $arr, $key, $text, $selected, $translate );
        $html .= '</select>';

        return $html;
    }
}