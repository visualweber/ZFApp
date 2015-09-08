<?php

/**
 * ZF Plugin which checks, if memory consumed is greater then a defined amount. If so: log an alert.
 * Based on code published at http://www.phpgangsta.de/fruhzeitig-memory-limit-probleme-entdecken
 *
 * @package&nbsp;&nbsp;&nbsp; Web_Punk
 * @author&nbsp;&nbsp;&nbsp;&nbsp; Christian Koncilia
 */
class App_Controller_Plugin_CheckMemoryUsage extends Zend_Controller_Plugin_Abstract {

    // send a warning to the admin if this limit in percent (here, 70%) is reached.
    const WARNING_LIMIT = 0.3;

    public function dispatchLoopShutdown() {
        if (Zend_Registry::isRegistered('logger')):
            $logger = Zend_Registry::get('logger');
        endif;
        if (memory_get_peak_usage() > $this->getBytes(ini_get('memory_limit')) * self::WARNING_LIMIT) {
            $body = memory_get_peak_usage() . " memory_limit: " . ini_get('memory_limit') . " Request: " . $_SERVER["REQUEST_URI"];
            $logger->getLog('memory')->log(__METHOD__ . "Peak usage: " . $body, Zend_Log::INFO);
        }
    }

    private function getBytes($val) {
        $val = trim($val);
        $last = strtolower($val{strlen($val) - 1});
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

}
