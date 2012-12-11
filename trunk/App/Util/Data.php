<?php
class App_Util_Data extends App_Util
{
    public static function getValueOrNull($value)
    {
        if (empty($value)) {
            return null;
        } else {
            return $value;
        }
    }
}