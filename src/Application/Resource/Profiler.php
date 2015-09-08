<?php

class App_Application_Resource_Profiler extends Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {
        $bootstrap = $this->getBootstrap();
        try {
            $bootstrap->bootstrap('db');
        } catch ( Zend_Application_Bootstrap_Exception $e ) {
            return null;
        }
        $db = $bootstrap->db;

        if ( $db!==null ) {
            $profiler = new Zend_Db_Profiler_Firebug( 'Queries' );
            $profiler->setEnabled( true );

            // Attach the profiler to your db adapter
            $db->setProfiler( $profiler );
        }
        return $db;
    }
}