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
 * @package 	App.Platform
 * @copyright 	Copyright (c) 2005-2011 XGOON MEDIA.
 * @license 	http://www.xgoon.com
 * @version 	App version 1.0.0
 * @author 	toan@xgoon.com <vnnfree@gmail.com>
 * @implement 	Name of developer
 */

abstract class App_Service_Cron_Abstract implements App_Service_Cron_Interface
{
    protected static $logger=null;
    /**
    * write debug message into log file
    * 
    * @param mixed $message
    */
    protected function debug($message){
        if($this->logger==null){
            $writer = new Zend_Log_Writer_Stream( XMVN_ROOT ."/data/logs/dailyreport.log");
            $this->logger = new Zend_Log($writer);    
        }
        $this->logger->log($message."\n", Zend_Log::DEBUG);
                
    }    
    /**
     * Run the cron task
     *
     * @return void
     * @throws MIIS_Plugin_Cron_Exception to describe any errors that should be returned to the user
     */
     public function run(){        
     }
}
