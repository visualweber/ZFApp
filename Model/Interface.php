<?php
/**
 * App_Model_Interface
 * All models use this interface
 *
 * @category   Kernel
 * @package    App_Model
 */
interface App_Model_Interface
{
    public function __construct($options = null);
    public function getResource($name);
    public function getForm($name);
    public function init();
}
