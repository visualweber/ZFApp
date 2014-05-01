<?php
/**
 * Core Mailer Component
 * Easy way for mailing
 *
 * <code>
 * $options = array(
 *     'storage'   => array(
 *          'type'    => 'DbTable',
 *          'options' => array(
 *               'table'  => new Model_DbTable_Mail()
 *          )),
 *     'transport' => array(
 *          'type'    => 'ZendMail',
 *          'options' => array(
 *               'fromEmail' => 'sender@mail.com',
 *               'fromName'  => 'ZFCore Webmaster',
 *               'transport' => new Zend_Mail_Transport_Sendmail()
 *          )),
 * );
 *
 * App_Mailer::init($options);
 *
 * $template = App_Mailer::getTemplate('activation');
 *
 * $template->toName = 'vasya';
 * // or
 * $template->setData(array('toName'  => 'george',
 *                          'toEmail' => 'george@bush.com'));
 *
 * // assign variables data
 * $template->assign('activation_code', 'TopSecret');
 *
 * App_Mailer::send($template);
 *
 * App_Mailer::send('activation', array('toName'  => 'george',
 *                                       'toEmail' => 'george@bush.com'));
 * </code>
 *
 * @category    App
 * @package  App_Mailer
 *
 * @version  $Id: Mailer.php 136 2010-06-16 14:30:42Z AntonShevchuk $
 */
class App_Mailer
{
    /**
     * Instance of himself
     *
     * @var App_Mailer
     */
    private static $_instance;

    /**
     * Array of posible adapters
     *
     * @var array
     */
    private $_storageAdapters = array('DbTable', 'Directory');

    /**
     * Source adapter for templates
     *
     * @var App_Mailer_Storage_Interface
     */
    private $_storage;

    /**
     * Array of posible mail adapters
     *
     * @var array
     */
    private $_transportAdapters = array('ZendMail', 'PHPMailer');

    /**
     * Source adapter for mail
     * @var App_Mailer_Transport_Interface
     */
    private $_transport;

    /**
     * return instance of already exists App_Mailer
     * or create new instance
     *
     * @return  App_Mailer
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::init();
        }
        return self::$_instance;
    }

    /**
     * Init App_Mailer component
     *
     * @param array|Zend_Config|null $options
     * @return App_Mailer
     */
    public static function init($options = null)
    {
        $coreMailer = new App_Mailer();

        /**
         * check $options if array or Zend_Config else throw Exception
         */
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        if (Zend_Registry::isRegistered('App_Mailer_Config')) {
            $config  = Zend_Registry::get('App_Mailer_Config');
            $config = (array) (is_object($config)?($config->toArray()):$config);
            $options = array_merge($config, (array) $options);
        }

        if (!is_array($options)) {
            throw new App_Mailer_Exception('options must be array or Zend_Config instance');
        }

        /**
         * Create instance of storage adapter
         */
        if (!isset($options['storage']['type']) ||
        !in_array($options['storage']['type'], $coreMailer->_storageAdapters)) {
            throw new App_Mailer_Exception("No such template adapter, " .
            $options['storage']['type']);
        } else {
            $storageClass = "App_Mailer_Storage_" . $options['storage']['type'];
            $coreMailer->_storage = new $storageClass($options['storage']['options']);
        }

        /**
         * Create instance of transport adapter
         */
        if (!isset($options['transport']['type']) ||
        !in_array($options['transport']['type'], $coreMailer->_transportAdapters)) {
            throw new App_Mailer_Exception("No such mail adapter, " .
            $options['transport']['type']);
        } else {
            $transportClass = "App_Mailer_Transport_".$options['transport']['type'];
            $coreMailer->_transport = new $transportClass($options['transport']['options']);
        }

        self::$_instance = $coreMailer;
        return self::$_instance;
    }

    /**
     * Return Template for mailing
     *
     * @param   string               $alias
     * @return  App_Mailer_Template Use this for assigning values to Template
     */
    public static function getTemplate($alias)
    {
        $coreMailer = self::getInstance();
        /**
         * Get template by alias from adapter and return new Template object for future assigning variables
         */
        return new App_Mailer_Template($coreMailer->_storage->getTemplate($alias));
    }

    /**
     * Send mail through App_Mailer_Transport
     * using data from App_Mailer_Template
     *
     * @param   App_Mailer_Template|string $template
     * @return  bool
     */
    public static function send($template, $options = null)
    {
        $coreMailer = self::getInstance();

        if (is_string($template)) {
            if (!isset($options['toEmail']) || !isset($options['toName'])) {
                throw new App_Mailer_Exception('Must be specified toEmail and toName');
            }
            $template = self::getTemplate($template);
            $template->setData($options);
        } elseif (!$template instanceof App_Mailer_Template) {
            throw new App_Mailer_Exception('Template must be instance of App_Mailer_Template or string');
        }

        return $coreMailer->_transport->send($template);
    }
}