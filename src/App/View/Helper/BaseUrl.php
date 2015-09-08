<?php
/**
 * Helper for retrieving the BaseUrl
 *
 * @package    Zend_View
 * @subpackage Helper
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class App_View_Helper_BaseUrl extends Zend_View_Helper_Abstract
{
    /**
     * BaseUrl
     *
     * @var string
     */
    protected $_baseUrl;

    /**
     * Returns site's base url, or file with base url prepended
     *
     * $file is appended to the base url for simplicity
     *
     * @param  string|null $file
     * @return string
     */
    public function baseUrl($file = null)
    {
        // Get baseUrl
        $baseUrl = $this->getBaseUrl();

        // Remove trailing slashes
        if (null !== $file) {
            $file = '/' . ltrim($file, '/\\');
        }

        return $baseUrl . $file;
    }

    /**
     * Set BaseUrl
     *
     * @param  string $base
     * @return Zend_View_Helper_BaseUrl
     */
    public function setBaseUrl($base)
    {
        $this->_baseUrl = rtrim($base, '/\\');
        return $this;
    }

    /**
     * Get BaseUrl
     *
     * @return string
     */
    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            /** @see Zend_Controller_Front */
            require_once 'Zend/Controller/Front.php';
            $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();

            // Remove scriptname, eg. index.php from baseUrl
            $baseUrl = $this->_removeScriptName($baseUrl);

            $this->setBaseUrl($baseUrl);
        }

        return $this->_baseUrl;
    }

    /**
     * Remove Script filename from baseurl
     *
     * @param  string $url
     * @return string
     */
    protected function _removeScriptName($url)
    {
        if (!isset($_SERVER['SCRIPT_NAME'])) {
            // We can't do much now can we? (Well, we could parse out by ".")
            return $url;
        }

        if (($pos = strripos($url, basename($_SERVER['SCRIPT_NAME']))) !== false) {
            $url = substr($url, 0, $pos);
        }

        return $url;
    }
}
