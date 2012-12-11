<?php
/**
 * Here're your description about this file and its function
 *
 * @version			$Id: Block.php 12 Sep 2010 19:09:21$
 * @category		App_View_Helper_Block
 * @package			Kernel Package
 * @subpackage		App_View_Helper_Block
 * @license			http://xgoon.com
 * @copyright		Copyright (c) 2005-2011 XGOON MEDIA
 * @author			huy.do@xgoon.com (xgoon)
 * @implements		Toan LE
 * @file			Block.php
 *
 */

class App_View_Helper_Block extends Zend_View_Helper_Partial
{
    protected $_prefix_block = 'block';

    public function block($pos = 'lefat')
    {
        $navigationConfig = Zend_Registry::get ( 'navigator_config' );
        $block_config = Zend_Registry::get ( 'block_config' );

        $block_config_parsed_toarray = $block_config->toArray ();

        $navigation = new Zend_Navigation ( $navigationConfig );

        $view = $this->cloneView ();
        $currentpage = $navigation->findOneBy ( 'active', 1 );
        if (! $currentpage instanceof Zend_Navigation_Page)
        {

            return false;
        }
        $currentpage_parsed_toarray = $currentpage->toArray ();
        if (array_key_exists ( $pos, $block_config_parsed_toarray ))
        {
            $block_parsed_toarray = explode ( ',', $currentpage_parsed_toarray ['block'] );

            foreach ( $block_parsed_toarray as $block_id )
            {
                if (array_key_exists ( $this->_prefix_block . "_$block_id", $block_config_parsed_toarray [$pos] ))
                {
                    $block = $block_config_parsed_toarray [$pos] [$this->_prefix_block . "_$block_id"];
                    $block_params = array_key_exists ( 'params', $block ) ? $block ['params'] : null;
                    echo $view->partial ( $block ['filename'], $block_params );
                }
            }
        }
        else
        {
            $e = new Zend_View_Helper_Partial_Exception ( 'this position not exist' );
            throw $e;
        }

    }
}

?>