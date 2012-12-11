<?php
/**
 * @Desc : App_View_Smarty_Plugin_Broker
 * @author : TOANLE | xgoon.com
 * 
 * This class registers smarty plugins with the current smarty view
 */
abstract class App_View_Smarty_Plugin_Abstract extends Zend_View_Abstract {

    protected $_functionRegistry;
    protected $_namingPattern = '/([a-zA-Z1-9]+)(Function|Block)$/';

    function __construct() {
        parent::__construct ();
    }

    /**
     * @return array
     */
    public function getClassFunctionArray() {
        $type = get_class ( $this );
        $methods = get_class_methods ( $this );

        foreach ( $methods as $value ) {
            if (preg_match ( $this->_namingPattern, $value, $matches )) {
                $this->_functionRegistry [$matches [1]] = $type . "::" . $value;
            }
        }
        return $this->_functionRegistry;
    }

    /**
     * change the default naming pattern for functions that should be mapped to smarty functions
     */
    public function setNamingPattern($pattern = '/([a-zA-Z1-9]+)(Function|Block)$/') {
        //"/([a-zA-Z1-9]+)Function$/";
        $this->_namingPattern = $pattern;
    }

    public function _run() {
        parent::_run ();
    }
}