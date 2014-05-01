<?php
/**
 * App_Model_Interface
 * All models use this interface
 *
 * @category    App
 * @package    App.Platform
 */
interface App_Model_Interface
{
    public function __construct($options = null);
    public function getResource($name);
    public function getForm($name);
    public function init();
}
