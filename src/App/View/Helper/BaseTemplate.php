<?php

class App_View_Helper_BaseTemplate extends Zend_View_Helper_Abstract {

    function baseTemplate($string) {

        if (Zend_Registry::isRegistered('config')) {
            $config = Zend_Registry::get('config');
            return $config ['site'] ['baseTemplate'] . $string;
        }
    }

}
