<?php
/**
 *      Bushido
 *
 * @category   Bushido
 * @package    Bushido_Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2008-2009 Nathan Keyes
 * @author Nathan Keyes
 * @version    $Id: Dbmulticonnection.php 429 2009-08-19 19:51:33Z Nathan Keyes $
 */

class Bushido_Application_Resource_Dbmulticonnection extends Zend_Application_Resource_ResourceAbstract
{
     protected $_databases = array ();

     protected $_defaultDatabase = null;

     /**
      * Adapter type to use
      *
      * @return string
      */
     public function getAdapterType($database)
     {
         $database = $this->isValidDb ( $database );
         return $this->_options [$database] ['adapter'];

     }

      /**
       * Adapter parameters
       *
       * @return array
       */
     public function getParams($database)
     {
         $database = $this->isValidDb ( $database );
         return $this->_options [$database] ['params'];
     }

  /**
   * Is this adapter the default table adapter?
       *
       * @return void
       */
     public function isDefaultTableAdapter($database)
     {
         $database = $this->isValidDb ( $database );
         return isset ( $this->_options [$database] ['isDefaultTableAdapter'] ) && $this->_options [$database] ['isDefaultTableAdapter'];
     }

     /**
      * Retrieve initialized DB connection
      *
      * @return Zend_Db_Adapter_Abstract
         */
     public function getAdapter($database = null)
     {
         // check if the DB is valid
         $database = $this->isValidDb ( $database, true );
         if (! isset ( $database ) && ! isset ( $this->_defaultDatabase ))
         {
             return null;
         }
         elseif (! isset ( $database ) && isset ( $this->_defaultDatabase ))
         {
             $database = $this->_defaultDatabase;

         }
         if (! isset ( $this->_databases [$database] ) && $this->getAdapterType ( $database ) !== null)
         {

             $this->_databases [$database] = Zend_Db::factory ( $this->getAdapterType ( $database ), $this->getParams ( $database ) );
             //Zend_Debug::dump($this->_databases[$database], 'default db!');exit;
         }
         return $this->_databases [$database];
     }

     /**
      * Defined by Zend_Application_Resource_IResource
      *
      * @return void
      */
     public function init()
     {
         if (! isset ( $this->_defaultDatabase ))
         {
             $options = $this->getOptions ();
             $defaultDatabase = null;
             foreach ( $options as $database => $databaseOptions )
             {
                 $adapter = $this->getAdapter ( $database );
                 if ($adapter !== null)
                 {
                     if ($this->isDefaultTableAdapter ( $database ) || ! isset ( $defaultDatabase ))
                     {
                         $defaultDatabase = $database;

                     }
                 }
             }
             if (isset ( $defaultDatabase ))
             {
                 $this->_defaultDatabase = $defaultDatabase;
                 Zend_Db_Table::setDefaultAdapter ( $this->getAdapter ( $defaultDatabase ) );
             }
         }
     }

     /**
      * Check if a database key is valid
      *
      * @param string $database
      * @param boolean $revertToDefaultDb
      * @return string
      */
     public function isValidDb($database, $revertToDefaultDb = false)
     {
         $database = strtolower ( trim ( $database ) );
         if (! in_array ( $database, array_keys ( $this->_options ) ))
         {
             //Zend_Debug::dump(array_keys($this->_options), "$database not in:");


             if ($revertToDefaultDb)
             {
                 $database = $this->_defaultDatabase;
             }
             else
             {
                 throw new Zend_Application_Resource_Exception ( 'Invalid database specified' );
             }
         }
         return $database;
     }

}