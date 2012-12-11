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
//require_once 'Kernel/Admin/View/Helper/Abstract.php';


class App_View_Helper_Html_Order extends App_Admin_View_Helper_Abstract
{

    public function order($rows, $image = 'filesave.png', $task = "saveorder")
    {
        $href = '<a href="javascript:saveorder(' . (count ( $rows ) - 1) . ', \'' . $task . '\')" title="' . ('Save Order') . '">';
        $href .= $this->_images ( $image );
        $href .= '</a>';
        return $href;
    }

    /**
     * @param	string	The file name, eg foobar.png
     * @param	string	Alt text
     * @param	array	An associative array of attributes to add
     * @param	boolean	True (default) to display full tag, false to return just the path
     */
    public function _images($file, $alt = null, $attribs = null, $type = 1)
    {
        $alt = html_entity_decode ( $alt );

        if (file_exists ( BASE_PATH . '/public/assets/images/' . $file ))
        {
            $image = '/public/assets/images/' . $file;
        }

        // Prepend the base path
        $image = $this->baseUrl ( $image );
        if ($type)
        {
            $image = '<img src="' . $image . '" alt="' . $alt . '" ' . $attribs . ' />';
        }
        return $image;
    }

    /**
     * Return the icon to move an item UP
     *
     * @access	public
     * @param	int		$i The row index
     * @param	boolean	$condition True to show the icon
     * @param	string	$task The task to fire
     * @param	string	$alt The image alternate text string
     * @return	string	Either the icon to move an item up or a space
     * @since	1.0
     */
    public function orderUpIcon()
    {
        $args = func_get_args ();
        list ( $i, $condition, $task, $alt, $enabled ) = $args [0];

        $html = '&nbsp;';
        if ($i > 0 && $condition)
        {
            if ($enabled)
            {
                $html = '<a href="#reorder" onclick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $alt . '">';
                $html .= '<img src="' . $this->baseUrl ( '/public/assets/images/uparrow.png' ) . '" width="16" height="16" border="0" alt="' . $alt . '" />';
                $html .= '</a>';
            }
            else
            {
                $html = '<img src="' . $this->baseUrl ( '/public/assets/images/uparrow0.png' ) . '" width="16" height="16" border="0" alt="' . $alt . '" />';
            }
        }
        return $html;
    }

    /**
     * Return the icon to move an item DOWN
     *
     * @access	public
     * @param	int		$i The row index
     * @param	int		$n The number of items in the list
     * @param	boolean	$condition True to show the icon
     * @param	string	$task The task to fire
     * @param	string	$alt The image alternate text string
     * @return	string	Either the icon to move an item down or a space
     * @since	1.0
     */
    public function orderDownIcon()
    {
        $args = func_get_args ();
        list ( $i, $n, $condition, $task, $alt, $enabled ) = $args [0];
        $html = '&nbsp;';

        if ($i < $n - 1 && $condition)
        {
            if ($enabled)
            {
                $html = '<a href="#reorder" onclick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $alt . '">';
                $html = '<img src="' . $this->baseUrl ( '/public/assets/images/downarrow.png' ) . '" width="16" height="16" border="0" alt="' . $alt . '" />';
                $html .= '</a>';
            }
            else
            {
                $html = '<img src="' . $this->baseUrl ( '/public/assets/images/downarrow0.png' ) . '" width="16" height="16" border="0" alt="' . $alt . '" />';
            }
        }

        return $html;
    }
}