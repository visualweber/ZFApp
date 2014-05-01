<?php
/**
 * App_Model_Acl_Interface
 *
 * @category    App
 * @package    App.Platform
 */
interface App_Model_Acl_Interface
{
    public function setIdentity($identity);
    public function getIdentity();
    public function checkAcl($action);
    public function setAcl(App_Acl_Interface $acl);
    public function getAcl();
}
