<?php
/**
 * Here're your description about this file and its function
 *
 * @version			$Id: Action.php 07-08-2010 15:45:54$
 * @category    App
 * @package    App.Platform
 * @subpackage		subpackage
 * @license			http://xgoon.com/license
 * @copyright		Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved. (http://xgoon.com)
 * @author			toan@xgoon.com
 * @implements		Toan LE
 * @file			Action.php
 *
 */

final class App_Controller_Plugin_Debug extends Zend_Controller_Plugin_Abstract {
	const DEBUG_NAMESPACE = 'DEBUG_Plugin';
	
	public static function debug($object, $label = '', $die = false) {
		if (FALSE === DEBUG) {
			return FALSE;
		}
		
		if (FALSE === Zend_Session::isStarted ()) {
			Zend_Session::start ();
		}
		$debug_session = new Zend_Session_Namespace ( self::DEBUG_NAMESPACE );
		
		$debug_backtrace = debug_backtrace ();
		
		if ($object === FALSE)
			$object = '<span style="color:blue">FALSE</span>';
		if ($object === TRUE)
			$object = '<span style="color:blue">TRUE</span>';
		
		if ($label == '')
			$label = 'DEBUG';
		
		$debug = '<div id="debug_wrapper" style="clear: both; text-align: left; width: 98%; margin:10px auto; background: #FFFFD7; border: 1px dotted #008200; font-family: Tahoma;  font-size: 12px;">'; // Start
		$debug .= '<div id="debug_content" style="padding: 10px 10px 0px 10px;">';
		$debug .= '<div id="debug_location" style="font-weight: bold; color: #008200; border-bottom: 1px dotted #008200; padding-bottom: 10px;">'; // Start
		$debug .= '<span style="color:#FFF; background-color:green;padding:0px 10px;margin:0px 10px 0px 0px; border:1px solid green; -moz-border-radius: 5px; -webkit-border-radius: 5px;">' . strtoupper ( $label ) . '</span>Debug called from ' . $debug_backtrace [1] ['file'] . ' (line ' . $debug_backtrace [1] ['line'] . ')';
		$debug .= '</div>'; // End of debug_location
		$debug .= '<pre>';
		$debug .= print_r ( $object, true );
		$debug .= '</pre>';
		
		$debug .= '</div>'; // End of debug_content
		$debug .= '</div>'; // End of debug_wrapper
		
		self::logger ( $object, null );
		$debug_session->debug = isset ( $debug_session->debug ) ? $debug_session->debug . $debug : $debug;
	}
	
	public static function logger($message, $type = Zend_Log::INFO, $extras = array()) {
		if (Zend_Registry::isRegistered ( 'logger' ) === TRUE && DEBUG === TRUE) {
			// $logger = new Zend_Log();
			// $writer = new Zend_Log_Writer_Firebug();
			// $logger->addWriter($writer);
			
			Zend_Registry::get ( 'logger' )->log ( $message, Zend_Log::INFO, $extras );
		}
	}
} 