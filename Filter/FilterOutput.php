<?php
/**
 * @version			$Id: Dir.php Jul 27, 2010 2:58:56 PM$
 * @category		FileSystem
 * @package			FileSystem Package
 * @subpackage		App_Filesystem_Folder
 * @license			http://xgoon.com
 * @copyright		Copyright (c) 2005-2011 XGOON MEDIA
 * @author			toan@xgoon.com (toan)
 * @implements		Toan LE
 * @file			Dir.php
 */

class App_Filter_FilterOutput
{

    /**
     * Makes an object safe to display in forms
     *
     * Object parameters that are non-string, array, object or start with underscore
     * will be converted
     *
     * @static
     * @param object An object to be parsed
     * @param int The optional quote style for the htmlspecialchars function
     * @param string|array An optional single field name or array of field names not
     *					 to be parsed (eg, for a textarea)
     */
    public function objectHTMLSafe(&$mixed, $quote_style = ENT_QUOTES, $exclude_keys = '')
    {
        if (is_object ( $mixed ))
        {
            foreach ( get_object_vars ( $mixed ) as $k => $v )
            {
                if (is_array ( $v ) || is_object ( $v ) || $v == NULL || substr ( $k, 1, 1 ) == '_')
                {
                    continue;
                }

                if (is_string ( $exclude_keys ) && $k == $exclude_keys)
                {
                    continue;
                }
                else if (is_array ( $exclude_keys ) && in_array ( $k, $exclude_keys ))
                {
                    continue;
                }

                $mixed->$k = htmlspecialchars ( $v, $quote_style, 'UTF-8' );
            }
        }
    }

    /**
     * This method processes a string and replaces all instances of & with &amp; in links only
     *
     * @static
     * @param	string	$input	String to process
     * @return	string	Processed string
     * @since	1.5
     */
    public function linkXHTMLSafe($input)
    {
        $regex = 'href="([^"]*(&(amp;){0})[^"]*)*?"';
        return preg_replace_callback ( "#$regex#i", array (
            'App_Filter_FilterOutput', 
            '_ampReplaceCallback' ), $input );
    }

    /**
     * This method processes a string and replaces all accented UTF-8 characters by unaccented
     * ASCII-7 "equivalents", whitespaces are replaced by hyphens and the string is lowercased.
     *
     * @static
     * @param	string	$input	String to process
     * @return	string	Processed string
     * @since	1.5
     */
    public function stringURLSafe($string)
    {
        //remove any '-' from the string they will be used as concatonater
        $str = str_replace ( '-', ' ', $string );

        $lang = & JFactory::getLanguage ();
        $str = $lang->transliterate ( $str );

        // remove any duplicate whitespace, and ensure all characters are alphanumeric
        $str = preg_replace ( array (
            '/\s+/', 
            '/[^A-Za-z0-9\-]/' ), array (
            '-', 
            '' ), $str );

        // lowercase and trim
        $str = trim ( strtolower ( $str ) );
        return $str;
    }

    /**
     * Replaces &amp; with & for xhtml compliance
     *
     * @todo There must be a better way???
     *
     * @static
     */
    public function ampReplace($text)
    {
        $text = str_replace ( '&&', '*--*', $text );
        $text = str_replace ( '&#', '*-*', $text );
        $text = str_replace ( '&amp;', '&', $text );
        $text = preg_replace ( '|&(?![\w]+;)|', '&amp;', $text );
        $text = str_replace ( '*-*', '&#', $text );
        $text = str_replace ( '*--*', '&&', $text );

        return $text;
    }

    /**
     * Callback method for replacing & with &amp; in a string
     *
     * @static
     * @param	string	$m	String to process
     * @return	string	Replaced string
     * @since	1.5
     */
    public function _ampReplaceCallback($m)
    {
        $rx = '&(?!amp;)';
        return preg_replace ( '#' . $rx . '#', '&amp;', $m [0] );
    }

    /**
     * Cleans text of all formating and scripting code
     */
    public function cleanText(&$text)
    {
        $text = preg_replace ( "'<script[^>]*>.*?</script>'si", '', $text );
        $text = preg_replace ( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $text );
        $text = preg_replace ( '/<!--.+?-->/', '', $text );
        $text = preg_replace ( '/{.+?}/', '', $text );
        $text = preg_replace ( '/&nbsp;/', ' ', $text );
        $text = preg_replace ( '/&amp;/', ' ', $text );
        $text = preg_replace ( '/&quot;/', ' ', $text );
        $text = strip_tags ( $text );
        $text = htmlspecialchars ( $text );
        return $text;
    }
}
