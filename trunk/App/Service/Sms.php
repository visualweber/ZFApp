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
 * XGOON MEDIA VIETNAM is a software development company
 * specializing in Web Application, Mobile Application and Multimedia. xgoon's combination of experience
 * and specialization on Internet technologies extends our customers' competitive
 * advantage and helps them maximize their return on investment. We aim to realize
 * your company's goals and vision though ongoing communication and our commitment
 * to quality.
 *
 * @category 	App
 * @package 	App.Platform
 * @copyright 	Copyright (c) 2010-2014 XGOON MEDIA VIETNAM.
 * @license 	http://www.xgoon.com
 * @version 	App version 1.0.0
 * @author 	toan@xgoon.com <vnnfree@gmail.com>
 * @implement 	All XGOON's members
 */
class App_Service_Sms {
	public static function smsMobileTerminated($params) {
		if (Zend_Registry::isRegistered ( 'config' )) {
			$config = Zend_Registry::get ( 'config' );
		}
		if (Zend_Registry::isRegistered ( 'Zend_Translate' )) {
			$translate = Zend_Registry::get ( 'Zend_Translate' );
		}
		
		$soapClient = new Zend_Soap_Client ( $config->xm_app->incom->sms->wsdl );
		
		$datas = array (
			'account_name' => $config->xm_app->incom->sms->username, 
			'account_password' => $config->xm_app->incom->sms->password, 
			'User_ID' => $params ['phone'], 
			'Content' => $params ['smsContent'], 
			'Service_ID' => $config->xm_app->incom->sms->service_id, 
			'Command_Code' => $config->xm_app->incom->sms->command_code, 
			'Request_ID' => 0, 
			'Message_Type' => 0, 
			'Total_Message' => 1, 
			'Message_Index' => 1, 
			'IsMore' => 0, 
			'Content_Type' => 0 );
		
		$result = $soapClient->SendSMS ( $datas );
		
		if (1 == $result->SendSMSResult)
			return $params ['smsCode'];
		
		return false;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $params
	 */
	public static function smsMobileOriginated($params) {
	
	}
}