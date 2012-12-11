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

class App_View_Helper_Html_Id extends App_Admin_View_Helper_Abstract
{
    /**
     * @param int The row index
     * @param int The record id
     * @param boolean
     * @param string The name of the form element
     *
     * @return string
     */
    public function Id($rowNum, $recId, $checkedOut = false, $name = 'cid')
    {
        if ($checkedOut)
        {
            return '';
        }
        else
        {
            return '<input type="checkbox" id="cb' . $rowNum . '" name="' . $name . '[]" value="' . $recId . '" onclick="isChecked(this.checked);" />';
        }
    }
}