<?php
/**
 * Here're your description about this file and its function
 *
 * @version			$Id: UnPublish.php Jul 27, 2010 2:07:54 PM$
 * @category    App
 * @package    App.Platform
 * @subpackage		App_Admin_View_Helper_Html_Grid
 * @license			http://xgoon.com
 * @copyright		Copyright (c) 2010-2014 XGOON MEDIA VIETNAM
 * @author			toan@xgoon.com (toan)
 * @file			Grid.php
 *
 */

class App_View_Helper_Html_Optgroup extends App_Admin_View_Helper_Abstract
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    /**
     * Generates an HTML radio list
     *
     * @param array An array of objects
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param mixed The key that is selected
     * @param string The name of the object variable for the option value
     * @param string The name of the object variable for the option text
     * @returns string HTML for the select list
     */
    function radiolist($arr, $name, $attribs = null, $key = 'value', $text = 'text', $selected = null, $idtag = false, $translate = false)
    {
        reset ( $arr );
        $html = '';

        if (is_array ( $attribs ))
        {
            $attribs = JArrayHelper::toString ( $attribs );
        }

        $id_text = $name;
        if ($idtag)
        {
            $id_text = $idtag;
        }

        for($i = 0, $n = count ( $arr ); $i < $n; $i ++)
        {
            $k = $arr [$i]->$key;
            $t = $translate ? ( $arr [$i]->$text ) : $arr [$i]->$text;
            $id = (isset ( $arr [$i]->id ) ? @$arr [$i]->id : null);

            $extra = '';
            $extra .= $id ? " id=\"" . $arr [$i]->id . "\"" : '';
            if (is_array ( $selected ))
            {
                foreach ( $selected as $val )
                {
                    $k2 = is_object ( $val ) ? $val->$key : $val;
                    if ($k == $k2)
                    {
                        $extra .= " selected=\"selected\"";
                        break;
                    }
                }
            }
            else
            {
                $extra .= (( string ) $k == ( string ) $selected ? " checked=\"checked\"" : '');
            }
            $html .= "\n\t<input type=\"radio\" name=\"$name\" id=\"$id_text$k\" value=\"" . $k . "\"$extra $attribs />";
            $html .= "\n\t<label for=\"$id_text$k\">$t</label>";
        }
        $html .= "\n";
        return $html;
    }
}