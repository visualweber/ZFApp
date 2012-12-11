<?php
/**
* Class to provide 2 way encryption of data
*
* @author    Kevin Waterson
* @copyright    2009    PHPRO.ORG
*
*/
class App_Encryption_Mcrypt 
{
    public function encrypt($str, $key) {
        $key = $this->_hex2bin($key);

        $td = mcrypt_module_open("rijndael-128", "", "cbc", "fedcba9876543210");
        $iv = "fedcba9876543210";
        
        mcrypt_generic_init($td, $key, $iv);
        $encrypted = mcrypt_generic($td, $str);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return bin2hex($encrypted);
    }

    public function decrypt($code, $key) {
        $key = $this->_hex2bin($key);
        $code = $this->_hex2bin($code);
        $iv   = "fedcba9876543210";

        $td = mcrypt_module_open("rijndael-128", "", "cbc", "fedcba9876543210");

        mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $code);

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return utf8_encode(trim($decrypted));
    }

    private function _hex2bin($hexdata) {
        $bindata = "";

        for ($i = 0; $i < strlen($hexdata); $i += 2) {
                $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }

        return $bindata;
    }
}