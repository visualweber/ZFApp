<?php

/**
 * Here're your description about this file and its function
 *
 * @version		$Id: Action.php 16 Aug 2010 00:39:33$
 * @license		http://visualweber.com
 * @copyright		Copyright (c) 2010-2014 Visual Weber
 * @author		Toan LE <contact@visualweber.com>
 * @implements		Toan LE
 * @file		Action.php
 *
 */
class App_Controller_Action extends Zend_Controller_Action {

    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    private $cache = null;

    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    private $logger;

    /**
     * 
     * Enter description here ...
     * @var unknown_type
     */
    private $_instance;

    /**
     * application session
     * 
     * @var mixed
     */
    private $_session = null;

    /**
     * get email object
     * 
     */
    protected $options;

    /**
     * init property for index controller
     * 
     * 
     */
    protected $config;
    
    /**
     *
     * @var Bisna\Doctrine\Container 
     */
    protected $doctrine;

    public function init() {
        $router = $this->getFrontController()->getRouter();
        $router->addRoute('requestVars', new App_Controller_Router_Route_RequestVars());

        if (Zend_Registry::isRegistered('config')):
            $this->config = Zend_Registry::get('config');
        endif;
        if(Zend_Registry::isRegistered('doctrine')){
            $this->doctrine = Zend_Registry::get('doctrine');
        }
        $this->view->config = $this->config;
        $this->initLanguage();
    }

    /**
     * (non-PHPdoc)
     * @see Zend_Controller_Action::preDispatch()
     */
    public function preDispatch() {
        $this->view->addHelperPath('App' . DS . 'View' . DS . 'Helper', 'App_View_Helper');
        $this->_helper->addPath('App' . DS . 'Controller' . DS . 'Action' . DS . 'Helper', 'App_Controller_Action_Helper_');

        //if  its an AJAX request stop here
        if ($this->isAjaxRequest()) {
            Zend_Controller_Action_HelperBroker::removeHelper('Layout');
        }
    }

    /**
     * is post method
     * 
     */
    protected function isAjaxRequest() {
        return $this->getRequest()->isXmlHttpRequest() || isset($_GET['ajax']);
    }

    /**
     * is post method
     * 
     */
    protected function isPost() {
        return $this->getRequest()->isPost();
    }

    /**
     * http://stackoverflow.com/questions/3479336/why-is-there-no-translation-for-the-language-en-us
     * http://stackoverflow.com/questions/1875851/application-wide-locales-with-gettext-and-zend-translate
     * Enter description here ...
     * @throws Exception
     */
    public function initLanguage() {
        $config = Zend_Registry::get('config');
        try {
            if (!Zend_Session::isStarted())
                Zend_Session::start();
            $languageSession = new Zend_Session_Namespace('language');
        } catch (Zend_Session_Exception $e) {
            
        }

        $lang = $this->_request->getParam('lang');
        if ($lang == null) {
            if (!isset($languageSession->current_lang)) {
                $lang = $config ['site'] ['language'];
                if ($languageSession->isLocked())
                    $languageSession->unlock();
                $languageSession->current_lang = $lang;
            } else {
                $lang = $languageSession->current_lang;
            }
        } else {
            if ($languageSession->isLocked())
                $languageSession->unlock();
            $languageSession->current_lang = $lang;
        }

        $languageSession->setExpirationSeconds(1 * 60 * 60 * 12);
        $languageSession->lock();

        $this->view->lang = $lang;
        Zend_Registry::set('lang', $lang);

        $options = array('separator' => '=');
        try {
            $p_module = $this->_request->getModuleName();

            // get language file
            $languageDir = PATH_PROJECT . $config ['site'] ['language_dir'] . $lang . DIRECTORY_SEPARATOR;
            $languageModuleDir = $languageDir . $p_module . DIRECTORY_SEPARATOR;

            $translate = new Zend_Translate('ini', $languageDir . "$lang.ini", "$lang", $options);
            $translate->getAdapter()->addTranslation($languageModuleDir . "module.lang.ini", "$lang", $options);

            //store translate object to the registry
            Zend_Registry::set('Zend_Translate', $translate);
            $this->view->translate = $translate;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * get authenticated user
     * @return App_Models_SzUser
     * 
     */
    public function isLogin() {
        $auth = App_Auth::getInstance();
        $auth->getStorage();
        return $auth->hasIdentity();
    }

    /**
     * validate form token
     * 
     */
    protected function isValidToken() {
        $req = $this->getRequest();
        $token = $req->getParam('token', '');
        if ($token) {
            $ses_token = App_Util::getToken();
            if ($token == $ses_token) {
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param $message
     * @return unknown_type
     */
    protected function addMessage($message) {
        if (!$this->flashMessenger)
            $this->flashMessenger = $this->_helper->getHelper('Messenger');
        $this->flashMessenger->addMessage($message);
    }

    /**
     * get email object
     * @return Zend_Mail
     */
    protected function getMail() {
        $mail = new Zend_Mail('utf-8');
        if ($mail) {
            return $mail;
        }
        return false;
    }

    /**
     * render a view script. if $view===false, don't render the view script
     * if $view = array('action'=>'action','name'=>null,'noController'=>true/false)
     * @param $view
     * @return void
     */
    protected function renderView($view = false) {
        if ($view === false) {
            $this->_helper->viewRenderer->setNoRender(true);
        } elseif (is_array($view)) {
            $this->render($view ['action'], $view ['name'], $view ['noController']);
        } else {
            $this->render();
        }
    }

    /**
     * @desc set layout for a view
     * @param layout name
     */
    protected function setLayout($layout = "") {
        if ($layout != "") {
            $this->_helper->layout()->setLayout($layout);
        }
    }

    /**
     * disable layout in a action
     * @return void
     */
    protected function disableLayout() {
        $this->_helper->layout()->disableLayout();
    }

    /**
     * endable layout in a action
     * @return void
     */
    protected function enableLayout() {
        $this->_helper->layout()->enableLayout();
    }

    /**
     * get cookie object
     * 
     * @param mixed $instance_name
     * @return App_Auth_Storage_Cookie
     */
    protected function getCookie($instance_name = 'DEFAULT_COOKIE') {
        return new App_Auth_Storage_Cookie($instance_name, true);
    }

    protected function getAuthAdapter() {
        return (isset($this->options ['xm_service'] ['adapter']) ? $this->options ['xm_service'] ['adapter'] : '');
    }

    /**
     * init session state for appilication
     * 
     */
    protected function initSession() {
        if (!Zend_Session::isStarted()) {
            Zend_Session::start();
        }
        try {
            if ($this->_session == null)
                $this->_session = new Zend_Session_Namespace('XMVN_APPLICATION_NAMESPACE');

            // unlocking read-only lock
            if ($this->_session->isLocked()) {
                $this->_session->unLock();
            }
            return $this->_session;
        } catch (Zend_Session_Exception $e) {
            return null;
        }
    }

    /**
     * @param Kernel_Controller_Action_Helper_Messenger $flashMessenger
     * @return unknown_type
     */
    public function setFlashMessenger(App_Controller_Action_Helper_Messenger $flashMessenger) {
        $messages = $flashMessenger->getCurrentMessages();
        $html = $flashMessenger->showmessage($messages [count($messages) - 1]);
        $this->view->assign('msg', $html);
    }

    /**
     * TOAN LE
     * 'payment' . DS . Zend_Registry::get ( 'lang' ) . DS . 'manager.phtml'
     * Enter description here ...
     * @param unknown_type $datas
     * @param unknown_type $templateMail
     */
    public function sendMailAction($subject, $email, $name, $datas, $templateMail, $logger = null) {
        $translate = Zend_Registry::get('Zend_Translate');
        if ($logger == null) {
            $logger = App_Util::getLogger("/data/logs/register-email.log");
        }

        $flashMessenger = $this->_helper->getHelper('Messenger');
        $this->view->assign('datas', $datas);
        $this->view->assign('config', $this->config);

        // 2. Request
        switch ($this->config->resources->mail->transport->type) {
            case 'smtp' :
                $smtp_config = array('username' => $this->config->resources->mail->transport->username, 'password' => $this->config->resources->mail->transport->password);
                if ($this->config->resources->mail->transport->auth) {
                    $smtp_config ['auth'] = 'login';
                }
                $transport = new Zend_Mail_Transport_Smtp($this->config->resources->mail->transport->host, $smtp_config);
                break;
            default :
                break;
        }

        try {
            // 5. Send mail to Administrator
            $email_content = $this->view->render($templateMail);

            try {

                $mail = new Zend_Mail('UTF-8');
                $mail->clearRecipients();
                $mail->setBodyHtml($email_content);
                $mail->setFrom('welcome@like.vn', 'Saga Viet Nam');
                $mail->addTo($email, $name);
                $mail->setSubject($subject);
                $sent = $mail->send();

                //				$mail->send ( $transport );
                $message = "Sent mail success to \n";
                $message .= "\n\nParams: \n" . var_export($datas, true) . "\n";
                $message .= "-------------------------------\n\n\n";
                $status = 1;
                $flashMessenger->addMessage($translate->translate('SEND_MAIL_SUCCESS'));
            } catch (Zend_Exception $e) {
                $message = $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\nParams: \n" . var_export($datas, true) . "\n";
                $message .= "-------------------------------\n\n\n";
                $status = 0;
                $flashMessenger->addMessage($translate->translate('SEND_MAIL_FAILED'));
            }
        } catch (Zend_Exception $e) {
            $message = $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\nParams: \n" . var_export($datas, true) . "\n";
            $message .= "-------------------------------\n\n\n";

            $status = 0;
            $flashMessenger->addMessage($translate->translate('SEND_MAIL_FAILED'));
        }
        $this->setFlashMessenger($flashMessenger);
        $logger->log($message, Zend_Log::DEBUG);

        return $status;
    }

    /**
     * send email
     * 
     * @param mixed $from
     * @param mixed $to
     * @param mixed $subject
     * @param mixed $html_content
     * @param mixed $alt
     * @param mixed $cc
     * @return Zend_Mail
     */
    protected function sendMail($from, $to, $subject, $html_content, $alt = '', $cc = '') {
        $mail = $this->getMail();

        if ($mail) {
            if (!empty($from)) {
                $from_email = $from;
                $from_name = 'No-reply';
                if (is_array($from)) {
                    $from_name = $from [0];
                    $from_email = $from [1];
                }
                $mail->setFrom($from_email, $from_name);
                $mail->setReplyTo($from_email);
            }
            $mail->addTo($to);
            $mail->setSubject($subject);
            $mail->setBodyHtml($html_content);
            $mail->setBodyText($alt);
            if ($cc) {
                $mail->addCc($cc);
            }
            return $mail->send();
        }
        return false;
    }
    /**
     * Retrieve the Doctrine Container.
     *
     * @return Bisna\Doctrine\Container
     */
    public function getDoctrineContainer() {
        return $this->doctrine;
    }

}
