<?php
/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/Abstract.php';

/**
 * App_Controller_Action_Helper_Cache
 *
 * @category   Kernel
 * @package    App_Controller
 * @subpackage Action
 * @see http://blog.astrumfutura.com/archives/381-Zend-Framework-Page-Caching-Part-2-Controller-Based-Cache-Management.html
 */
class App_Controller_Action_Helper_Cache extends Zend_Controller_Action_Helper_Abstract
{

    protected $_caches = array();

    protected $_cleaners = array();

    protected $_caching = array();

    protected $_obstarted = false;

    public function addCache($cacheId, $cache) {
        if (!$cache instanceof Zend_Cache_Core && !$cache instanceof App_Cache_Backend_Static_Adapter) {
            throw new Exception('Need to provide a valid cache!');
        }
        $this->_caches[$cacheId] = $cache;
    }

    public function createCache($cacheId, $frontend, $backend, $frontendOptions = array(), $backendOptions = array(), $customFrontendNaming = false, $customBackendNaming = false, $autoload = false) {
        $cache = Zend_Cache::factory($frontend, $backend, $frontendOptions, $backendOptions, $customFrontendNaming, $customBackendNaming, $autoload);
        $this->addCache($cacheId, $cache);
        return $cache;
    }

    public function getCache($cacheId) {
        if ($this->hasCache($cacheId)) {
            return $this->_caches[$cacheId];
        }
        return false;
    }

    public function hasCache($cacheId) {
        if (isset($this->_caches[$cacheId])) {
            return true;
        }
        return false;
    }

    // Pass array of actions to cache for the current Controller
    public function direct(array $actions) {
        $controller = $this->getRequest()->getControllerName();
        foreach ($actions as $action) {
            if (!isset($this->_caching[$controller])) {
                $this->_caching[$controller] = array();
            }
            if (!isset($this->_caching[$controller][$action])) {
                $this->_caching[$controller][] = $action;
            }
        }
    }

    // Remove page caches based on URL, with recursive matching directory
    // removal for those where, for example, pagination is also being cached.
    // Sec: remember what they say about "rm -R" - checks needed
    public function removePageCache($relativeUrl, $recursive = false) {
        if ($recursive) {
            $this->getCache('page')->removeRecursive($relativeUrl);
        } else {
            $this->getCache('page')->remove($relativeUrl);
        }
    }

    // create a nested array assigning cleaners to various
    // controller+action combinations
    public function useCleaner($cleanerName, array $actions)
    {
        foreach ($actions as $action) {
            $controller = $this->getRequest()->getControllerName();
            if (!isset($this->_cleaners[$controller])) {
                $this->_cleaners[$controller] = array();
            }
            if (!isset($this->_cleaners[$controller][$action])) {
                $this->_cleaners[$controller][$action] = array();
            }
            if (!isset($this->_caching[$controller][$action][$cleanerName])) {
                $this->_cleaners[$controller][$action][] = $cleanerName;
            }
        }
    }

    // Commence caching for matching Actions
    // Will exit if caching has already started
    public function preDispatch()
    {
        if (!empty($this->_caching)) {
            $controller = $this->getRequest()->getControllerName();
            if (isset($this->_caching[$controller]) &&
            in_array($this->getRequest()->getActionName(), $this->_caching[$controller])) {
                // do not start caching if started earlier in cycle
                // otherwise commence caching here
                $stats = ob_get_status(true);
                foreach ($stats as $status) {
                    if ($status['name'] == 'Zend_Cache_Frontend_Page::_flush') {
                        return;
                    }
                }
                $this->getCache('page')->start();
                $this->_obstarted = true;
            }
        }
    }

    // Run cache cleaning operations after actions are dispatched
    // enforces Cleaner methods as being "after{ActionMethod}"
    public function postDispatch()
    {
        if (!empty($this->_cleaners)) {
            $controller = $this->getRequest()->getControllerName();
            $action = $this->getRequest()->getActionName();
            if (isset($this->_cleaners[$controller][$action])) {
                $cleanerNames = $this->_cleaners[$controller][$action];
                foreach ($cleanerNames as $cleanerName) {
                    $cleaner = $this->createCleaner($cleanerName);
                    $method = 'after' . ucfirst($action);
                    $cleaner->{$method}();
                }
            }
        }
        if ($this->_obstarted) {
            $this->getCache('page')->end();
        }
    }

    // Cheat by stealing functionality from the Dispatcher! Haha!
    // In a real class, should really implement this natively
    // to keep down on dependencies, and allow cleaners to
    // exist elsewhere. Also this is not Module friendly yet.
    public function createCleaner($cleanerName)
    {
        $dispatcher = $this->getFrontController()->getDispatcher();
        $className = $cleanerName . 'Cleaner';
        $finalClassName = $dispatcher->loadClass($className);
        $cleaner = new $finalClassName;
        return $cleaner;
    }

}