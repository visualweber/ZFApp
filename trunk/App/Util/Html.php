<?php
class App_Util_Html  extends App_Util {
    /**
     * @author Toan LE
     * Cleanup HTML entities
     * @param unknown_type $text
     */
    function cleanHtml ($text)
    {
        return htmlentities($text, ENT_QUOTES, 'UTF-8');
    }
}