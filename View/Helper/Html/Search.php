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


class App_View_Helper_Search extends Zend_View_Helper_Abstract
{
    public function search()
    {
       	$html = $this->view->render("helper/search.phtml");
        return $html;
    }
}