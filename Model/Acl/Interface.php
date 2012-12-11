<?php
/**
 * App_Model_Acl_Interface
 *
 * @category   Kernel
 * @package    App_Model_Acl
 */
interface App_Model_Acl_Interface
{
    public function setIdentity($identity);
    public function getIdentity();
    public function checkAcl($action);
    public function setAcl(App_Acl_Interface $acl);
    public function getAcl();
}
