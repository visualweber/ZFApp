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
class App_Application_Resource_Cron extends Zend_Application_Resource_ResourceAbstract {
	
	public function init() {
		$options = $this->getOptions ();
		if (array_key_exists ( 'pluginPaths', $options )) {
			$cron = new App_Service_Cron ( $options ['pluginPaths'] );
		} else {
			$cron = new App_Service_Cron ( array ('App_Service_Cron_Adapter' => realpath ( PATH_LIB . DS . 'App' . DS . 'Service' . DS . 'Cron' . DS . 'Adapter' . DS ) ) );
		}
		
		if (array_key_exists ( 'actions', $options )) {
			foreach ( $options ['actions'] as $name => $args ) {
				$cron->addAction ( $name, $args );
			}
		}
		
		return $cron;
	}
}