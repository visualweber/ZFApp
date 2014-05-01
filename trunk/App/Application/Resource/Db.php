<?php
/**
 * Set Db metadata cache
 *
 * @category    App
 * App.Platform
 * @subpackage Resource
 *
 * @version  $Id$
 */
class App_Application_Resource_Db extends Zend_Application_Resource_Db
{


    /**
     * Get metadata cache from cachemanager
     * @return Zend_Cache_Abstract
     */
    public function getMetadataCache()
    {
        /*if (Zend_Registry::get('Zend_Cache_Manager')->hasCache('metadata')) {
         $cache = Zend_Registry::get('Zend_Cache_Manager')->getCache('metadata');
         return $cache;
         }*/
    }

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Zend_Db_Adapter_Abstract|null
     */
    public function init()
    {
        if (null !== ($db = $this->getDbAdapter())) {
            if ($this->isDefaultTableAdapter()) {
                Zend_Db_Table::setDefaultAdapter($db);
                $cache = $this->getMetadataCache();
                if ($cache) {
                    Zend_Db_Table::setDefaultMetadataCache($cache);
                }
            }
            return $db;
        }
    }
}