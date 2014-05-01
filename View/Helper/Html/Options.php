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
class App_View_Helper_Html_Options extends App_Admin_View_Helper_Abstract
{
    public $view;

    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
    }

    /**
     * Generates just the option tags for an HTML select list
     *
     * @param	array	An array of objects
     * @param	string	The name of the object variable for the separator option value
     * @param	string	The name of the object variable for the option text
     * @returns	array [input of Zend Form Select]
     */
    public function options($datas, $defaultText = '', $value_name = 'value', $text_name = 'text', $disable = false)
    {
        $ids = array ();
        $texts = array ();

        if (! is_array ( $datas ))
        $datas = ( array ) $datas;

        for($i = 0, $n = count ( $datas ); $i < $n; $i ++)
        :
        $data = &$datas [$i];
        $ids [] = $data [$value_name];
        $texts [] = $data [$text_name];
        endfor;

        if (! is_array ( $ids ))
        $ids = ( array ) $ids;

        if (! is_array ( $texts ))
        $ids = ( array ) $texts;

        if ($defaultText)
        {
            array_unshift ( $ids, - 1 );
            array_unshift ( $texts, $defaultText );
        }
        return array_combine ( $ids, $texts );
    }

    /**
     * Generates just the option tags for an HTML select list
     *
     * @param	array	An array of objects
     * @param	string	The name of the object variable for the option value
     * @param	string	The name of the object variable for the option text
     * @param	mixed	The key that is selected (accepts an array or a string)
     * @returns	string	HTML for the select list
     */
    public function Joptions($arr, $key = 'value', $text = 'text', $selected = null, $translate = false)
    {
        $html = '';

        foreach ( $arr as $i => $option )
        {
            $element = & $arr [$i]; // since current doesn't return a reference, need to do this
            $isArray = is_array ( $element );
            $extra = '';
            if ($isArray)
            {
                $k = $element [$key];
                $t = $element [$text];
                $id = (isset ( $element ['id'] ) ? $element ['id'] : null);
                if (isset ( $element ['disable'] ) && $element ['disable'])
                {
                    $extra .= ' disabled="disabled"';
                }
            }
            else
            {
                $k = $element->$key;
                $t = $element->$text;
                $id = (isset ( $element->id ) ? $element->id : null);
                if (isset ( $element->disable ) && $element->disable)
                {
                    $extra .= ' disabled="disabled"';
                }
            }

            // This is real dirty, open to suggestions,
            // barring doing a propper object to handle it
            if ($k === '<OPTGROUP>')
            {
                $html .= '<optgroup label="' . $t . '">';
            }
            else if ($k === '</OPTGROUP>')
            {
                $html .= '</optgroup>';
            }
            else
            {
                //if no string after hypen - take hypen out
                $splitText = explode ( ' - ', $t, 2 );
                $t = $splitText [0];
                if (isset ( $splitText [1] ))
                {
                    $t .= ' - ' . $splitText [1];
                }

                //$extra = '';
                //$extra .= $id ? ' id="' . $arr[$i]->id . '"' : '';
                if (is_array ( $selected ))
                {
                    foreach ( $selected as $val )
                    {
                        $k2 = is_object ( $val ) ? $val->$key : $val;
                        if ($k == $k2)
                        {
                            $extra .= ' selected="selected"';
                            break;
                        }
                    }
                }
                else
                {
                    $extra .= (( string ) $k == ( string ) $selected ? ' selected="selected"' : '');
                }

                //if flag translate text
                if ($translate)
                {
                    $t = $t;
                }

                // ensure ampersands are encoded
                $k = App_Filter_FilterOutput::ampReplace ( $k );
                $t = App_Filter_FilterOutput::ampReplace ( $t );

                $html .= '<option value="' . $k . '" ' . $extra . '>' . $t . '</option>';
            }
        }

        return $html;
    }
}