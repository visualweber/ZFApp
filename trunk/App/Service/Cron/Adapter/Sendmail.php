<?php
/**
 * XGOON MEDIA COMPANY LIMITED
 *
 * Object Role Modeling (ORM) is a powerful method for designing and querying
 * database models at the conceptual level, where the application is described in
 * terms easily understood by non-technical users. In practice, ORM data models
 * often capture more business rules, and are easier to validate and evolve than
 * data models in other approaches.
 *
 * Asian opensource solutions [xgoon) is a software development company
 * specializing in Web Application and Media. xgoon's combination of experience
 * and specialization on Internet technologies extends our customers' competitive
 * advantage and helps them maximize their return on investment. We aim to realize
 * your company's goals and vision though ongoing communication and our commitment
 * to quality.
 *
 * @category 	App
 * @package 	App >> Base model
 * @copyright 	Copyright (c) 2005-2011 XGOON MEDIA.
 * @license 	http://www.xgoon.com
 * @version 	App version 1.0.0
 * @author 		toan@xgoon.com
 * @implement 	Name of developer
 */

class App_Service_Cron_Adapter_Sendmail implements App_Service_Cron_Interface {
	protected $_data;
	
	/**
	 * TOAN LE
	 * Enter description here ...
	 * @param unknown_type $args
	 * @throws App_Service_Cron_Exception
	 */
	public function __construct($args = null) {
		if (! is_array ( $args ) || ! array_key_exists ( 'run', $args )) {
			throw new App_Service_Cron_Exception ( 'The FileToucher cron task plugin is not configured correctly.' );
		}
		$this->_data = $args;
	}
	
	/**
	 * TOAN LE
	 * (non-PHPdoc)
	 * @see App_Service_Cron_Interface::run()
	 */
	public function run() {
		if ($this->_data ['run']) {
			$view = new Zend_View();
			$view->setScriptPath(PATH_TEMPLATE . DS . 'mailer' . DS . 'cron' . DS);
						
			/*****/
			$userModel = new Model_Users ();
			$adapter = $userModel->getMapper ()->getDbTable ()->getAdapter ();
			$select = $adapter->select ();
			$select->from ( array ('xm_users' ), array ('*' ) );
			
			$select->where ( "username RLIKE '[^a-zA-Z0-9()_.@\-]'" );
			$result = $adapter->fetchAll ( $select );			
			foreach ( $result as $res ) {				
				try {
					$view->datas = $res;					
					$body = $view->render('rename_username_unicode.phtml');					
					$smtp = new Zend_Mail ( 'UTF-8' );
					$smtp->clearRecipients ();
					$smtp->setBodyHtml ( $body );
					$smtp->addTo ( $res['email'], $res['username'] );
                    //if (file_exists($filename)) {
                        //$attachment = file_get_contents($filename);                    
                        //$smtp->createAttachment($attachment, 'application/octet-stream', 'attachment', 'base64', $report['fileName']);
                        //unlink($filename);// after done, remove file in server
                    //}
					$smtp->setSubject ( 'Thong bao thay doi tai khoan' );
					$smtp->send ();
					$message = "Sent mail success to \n";
					$message .= "Params: \n" . var_export ( $res, true ) . "\n";
					$message .= "-------------------------------\n\n\n";
				} catch ( Zend_Exception $exception ) {
					$message = $exception->getMessage () . "\n" . $exception->getTraceAsString () . "\n\nParams: \n" . var_export ( $res, true ) . "\n";
					$message .= "-------------------------------\n\n\n";
				}
				
				if (Zend_Registry::isRegistered ( 'logger' ))
					$logger = Zend_Registry::get ( 'logger' );
				
				$logger->log ( $message, Zend_Log::DEBUG );
				
			}
		}
	}
}