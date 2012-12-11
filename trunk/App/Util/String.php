<?php

/**
 * 
 * Enter description here ...
 * @author TOAN
 *
 */

class App_Util_String extends App_Util {
	
	/**
	 * @author Toan LE
	 * Gender
	 * @param unknown_type $gender
	 */
	public static function writtenBy($gender) {
		if (Zend_Registry::isRegistered ( 'Zend_Translate' )) {
			$translate = Zend_Registry::get ( 'Zend_Translate' );
		} else {
		
		}
		
		if (is_null ( $gender ))
			return $translate->translate ( 'WRITTEN_BY' );
		if ($gender == 'm')
			return $translate->translate ( 'WRITTEN_BY_MALE' );
		if ($gender == 'f')
			return $translate->translate ( 'WRITTEN_BY_FEMALE' );
	}
	/**
	 * @author Toan LE
	 * Word limit
	 * @param unknown_type $str
	 * @param unknown_type $limit
	 * @param unknown_type $end_char
	 */
	public static function wordLimit($str, $limit = 100, $end_char = '&#8230;') {
		if (trim ( $str ) == '')
			return $str;
		
		// always strip tags for text
		$str = strip_tags ( $str );
		
		//$find = array ("/\r|\n/", "/\t/", "/\s\s+/" );
		//$replace = array (" ", " ", " " );
		//$str = preg_replace ( $find, $replace, $str );
		

		preg_match ( '/\s*(?:\S*\s*){' . ( int ) $limit . '}/', $str, $matches );
		if (strlen ( $matches [0] ) == strlen ( $str ))
			$end_char = '';
		return rtrim ( $matches [0] ) . $end_char;
	}
	
	/**
	 * @author Toan LE
	 * Character limit
	 * @param unknown_type $str
	 * @param unknown_type $limit
	 * @param unknown_type $end_char
	 * @param unknown_type $enc
	 */
	public static function characterLimit($str, $limit = 150, $end_char = '...', $enc = 'utf-8') {
		if (trim ( $str ) == '')
			return $str;
		
		// always strip tags for text
		$str = strip_tags ( trim ( $str ) );
		
		//$find = array ("/\r|\n/", "/\t/", "/\s\s+/" );
		//$replace = array (" ", " ", " " );
		//$str = preg_replace ( $find, $replace, $str );
		if (strlen ( $str ) > $limit) {
			if (function_exists ( "mb_substr" )) {
				$str = mb_substr ( $str, 0, $limit, $enc );
			} else {
				$str = substr ( $str, 0, $limit );
			}
			return rtrim ( $str ) . $end_char;
		} else {
			return $str;
		}
	}
	/**
	 * returns a randomly generated string
	 * commonly used for password generation
	 *
	 * @param int $length
	 * @return string
	 */
	public static function random($length = 8) {
		// start with a blank string
		$string = "";
		
		// define possible characters
		$possible = "0123456789abcdfghjkmnpqrstvwxyz";
		
		// set up a counter
		$i = 0;
		
		// add random characters to $string until $length is reached
		while ( $i < $length ) {
			
			// pick a random character from the possible ones
			$char = substr ( $possible, mt_rand ( 0, strlen ( $possible ) - 1 ), 1 );
			
			// we don't want this character if it's already in the string
			if (! strstr ( $string, $char )) {
				$string .= $char;
				$i ++;
			}
		
		}
		
		// done!
		return $string;
	}
	
	/**
	 * replaces spaces with hyphens (used for urls)
	 *
	 * @param string $string
	 * @return string
	 */
	public static function addHyphens($string) {
		return str_replace ( ' ', '-', trim ( $string ) );
	}
	
	/**
	 * replaces hypens with spaces
	 *
	 * @param string $string
	 * @return string
	 */
	public static function stripHyphens($string) {
		return str_replace ( '-', ' ', trim ( $string ) );
	}
	
	/**
	 * replace slashes with underscores
	 *
	 * @param string $string
	 * @return string
	 */
	public static function addUnderscores($string, $relative = false) {
		$string = str_replace ( "_", "[UNDERSCORE]", $string );
		return str_replace ( '/', '_', trim ( $string ) );
	}
	
	/**
	 * replaces underscores with slashes
	 * if relative is true then return the path as relative
	 *
	 * @param string $string
	 * @param bool $relative
	 * @return string
	 */
	public static function stripUnderscores($string, $relative = false) {
		$string = str_replace ( '_', '/', trim ( $string ) );
		if ($relative) {
			$string = App_Util_String::stripLeading ( '/', $string );
		}
		$string = str_replace ( "[UNDERSCORE]", "_", $string );
		return $string;
	}
	
	/**
	 * strips the leading $replace from the $string
	 *
	 * @param string $replace
	 * @param string $string
	 * @return string
	 */
	public static function stripLeading($replace, $string) {
		if (substr ( $string, 0, strlen ( $replace ) ) == $replace) {
			return substr ( $string, strlen ( $replace ) );
		} else {
			return $string;
		}
	}
	
	/**
	 * returns the parent from the passed path
	 *
	 * @param string $path
	 * @return string
	 */
	public static function getParentFromPath($path) {
		$path = App_Util_Regex::stripTrailingSlash ( $path );
		$parts = explode ( '/', $path );
		array_pop ( $parts );
		return implode ( '/', $parts );
	}
	
	/**
	 * returns the current file from the path
	 * this is a custom version of basename
	 *
	 * @param string $path
	 * @return string
	 */
	public static function getSelfFromPath($path) {
		$path = App_Util_Regex::stripTrailingSlash ( $path );
		$parts = explode ( '/', $path );
		return array_pop ( $parts );
	}
	
	public static function truncateText($text, $count = 25, $stripTags = true) {
		if ($stripTags) {
			$filter = new Zend_Filter_StripTags ();
			$text = $filter->filter ( $text );
		}
		$words = split ( ' ', $text );
		$text = ( string ) join ( ' ', array_slice ( $words, 0, $count ) );
		return $text;
	}
    
	/**
	 * Return the real length of an UTF8 string
	 *
	 * @access public
	 * @param string $utf8_string
	 * @return int the number of utf8 characters in the string
	*/	
	public static function utf8_strlen($utf8_string)
	{
		return strlen(utf8_decode($utf8_string));
	}

	/**
	 * Checks if a string contains 7bit ASCII only
	 *
	 * @access public
	 * @param string $utf8_string
	 * @return bool 'true' if the string contains only ascii characters, 'false' otherwise
	 */
	public static function utf8_is_ascii($utf8_string)
	{
		$count = strlen($utf8_string);
		for($i=0; $i++<$count;)
		{
			if(ord($utf8_string[$i]) >127) return false;
		}
		return true;
	}

	/**
	 * Escape an UTF8 string for display in an HTML page
	 * Typically, will convert only the 'special fives'
	 *    which are <,>,&,',"
	 * Convert "\n" to "<br>"
	 *
	 * @access public
	 * @param string $utf8_string
	 * @return string the escaped string
	 * @see Smarty modifier named utf8_escape_html
	 */
	public static function utf8_escape_html($utf8_string)
	{
		return nl2br(htmlspecialchars($utf8_string,ENT_QUOTES,"UTF-8"));
	}
	
	/**
	 * Escape an UTF8 string for display in an HTML textarea element
	 * Typically, will convert only the 'special fives'
	 *    which are <,>,&,',"
	 *
	 * @access public
	 * @param string $utf8_string
	 * @return string the escaped string
	 * @see Smarty modifier named utf8_escape_html
	 */
	public static function utf8_escape_textarea($utf8_string)
	{
		return htmlspecialchars($utf8_string,ENT_QUOTES,"UTF-8");
	}

	/**
	 * Escape an UTF8 string for use as JS litteral in HTML page
	 * Typically, will convert only the 'special fives'
	 *    which are <,>,&,'," (html parser escape)
	 * after having added slashes in front of all ' and " and \
	 * (javascript parser escape)
	 *
	 * @access public
	 * @param string $utf8_string
	 * @return string the escaped string
	 * @see Smarty modifier named utf8_escape_jshtml
	 */
	public static function utf8_escape_jshtml($utf8_string)
	{
		return htmlspecialchars(preg_replace("/\r?\n/", "\\n", str_replace("<","\\x3C",addslashes($utf8_string))),ENT_QUOTES,"UTF-8");
	}

	/**
	 * Escape an UTF8 string for use as JS litteral in JS script section
	 * of an html page.
	 * Will add slashes in front of all ' and " and \, then will escape
	 * the opening tag characters '<' for avoiding problem with injected
	 * closing </SCRIPT> tag.
	 *
	 * @access public
	 * @param string $utf8_string
	 * @return string the escaped string
	 * @see Smarty modifier named utf8_escape_js
	 */
	public static function utf8_escape_js($utf8_string)
	{
        $utf8_string = str_replace(array("\r", "\n"), '', $utf8_string);
		return preg_replace("/\r?\n/", "\\n", str_replace("<","\\x3C",addslashes($utf8_string)));
	}

	/**
	 * Escape an UTF8 string for use as string in an XML document
	 * The escaping is compatible for both text node and attributes
	 * value.
	 *
	 * To fit with W3C recommandation on XML 1.0, we escape using UTF8 code
	 * of the entity (and not the html entity). Only the recommanded characters
	 * are escaped => &,",<,>,Carriage return and Line feed
	 *
	 * @param string $utf8_string
	 * @return string the escaped string
	 * @see Smarty modifier named utf8_escape_xml
	 * @link http://www.w3.org/TR/2006/REC-xml-20060816/
	 * @link http://www.w3.org/XML/Datamodel.html
	 */
	public static function utf8_escape_xml($utf8_string)
	{
		return str_replace(array('&','"',"'",'<','>',"\r","\n"),
			array('&#38;','&#34;','&#39;','&#60;','&#62;','&#12;','&#10;'),
			$utf8_string );
	}

	/**
	 * Working utf8 substr function with no risks of cutting a >1 byte utf8 character
	 * in half.
	 *
	 * To achieve that, regexp are used with curly brace repetition of an UTF8 char regexp,
	 * it allows to read $from character, then capture $len character (max) in the second capture
	 * group, then return the whole 2nd capture group.
	 *
	 * @param string $utf8_string
	 * @param int $from the returned string will start at the start 'th position in string ,
	 *   counting from zero. If $from is negative, the full string will be returned.
	 * @param int $len If $len  is given and is positive, the string returned will contain at most $len
	 *   characters beginning from start (depending on the length of string ). If string length is less than
	 *   or equal to $from characters long, empty string will be returned. If $len is negative, the full string
	 *   will be returned. If $len is 0 or false, it is considered infinite
	 * @return string the subbed string
	 * @see Smarty modifier named utf8_substr
	 * @link http://vn.php.net/substr
	 */
	public static function utf8_substr($utf8_string,$from,$len = 0)
	{
		$from = intval($from); $len = intval($len);
		$str_len = DRX_StringUtils::utf8_strlen($utf8_string);

		// treat $from parameter
		if ($from < 0)
		{
			$from += $str_len;
			if ($from < 0)
			{
				return '';
			}
		}
		// speed up the process in case the $from is too big
		elseif ($from >= $str_len)
		{
			return '';
		}
		

		// treat negative $len parameter
		if ($len < 0)
		{
			//speedup the process a little
			if ($from - $len >= $str_len)
			{
				return '';
			}
			
			$len += $str_len - $from;
		}


		return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
			'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+)'.(1?'{0,'.$len.'}':'*').').*#s',
			'$1',$utf8_string);
	}
	
	/**
	 * check string satisfy multilanguage format(eg. <en>xxxx</en><fr>yyyyy</fr>)
	 *
	 * @param string $source_str string is checked
	 */
    public static function isI18nStringFormat($source_str, $language = 'en')
	{
        if ($language && $language != '[a-z]{2}(_[A-Z]{2})*?' && $language != 'en') {
            if (!preg_match('/^[a-z]{2}(_[A-Z]{2})*?$/', $language)) {
                return false;
            }
        }
    
        $pattern = '/<('.$language.')>(.*?)<\/('.$language.')>/s';
        
		preg_match($pattern, $source_str, $source_matches);
             
		if(count($source_matches) > 0) {
			if ((count($source_matches) == 4 && (!$source_matches[2] || $source_matches[1] != $source_matches[3]))
                    || (count($source_matches) > 4 && (!$source_matches[3] || $source_matches[1] != $source_matches[4]))) {
				return false; 
			}
			
			$source_str = trim(str_replace($source_matches[0], '', $source_str));
			
			if ($source_str) {
				return DRX_StringUtils::isI18nStringFormat($source_str, '[a-z]{2}(_[A-Z]{2})*?');
			}
		} 
        else {
			if ($source_str) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * append id to name of string satisfy multilanguage format(eg. <en>id_xxxx</en><fr>id_yyyyy</fr>)
	 *
	 * @param string $id
	 * @param string $name
	 * 
	 */
	public static function appendIdtoName($id, $name)
	{
		$id_name = '';
		if (self::isI18nStringFormat($name)) {
			$pattern = '/<([a-z]{2}(_[A-Z]{2})*?)>(.*?)<\/([a-z]{2}(_[A-Z]{2})*?)>/s';
			preg_match_all($pattern, $name, $source_matches);
            foreach	($source_matches[0] as $key => $value) {
                $str_name = $source_matches[2];
                if (count($source_matches) == 6) {
                    $str_name = $source_matches[3];
                }
				$id_name .= str_replace($str_name[$key], $id.'_'.$str_name[$key], $value);
			}
		} else {
			$id_name = $id.'_'.$name;	
		}
		return $id_name;
	}
	
	public static function isI18nSqlCheckStringUnique($source_str, $field)
	{
		$pattern = '/<([a-z]{2}(_[A-Z]{2})*?)>.*?<\/([a-z]{2}(_[A-Z]{2})*?)>/';
		preg_match_all($pattern, $source_str, $source_matches);
		$sql_arr = array();
		foreach($source_matches[0] as $key => $value) {
			$sql_arr[] = $field.' LIKE "%'.$value.'%"';
		}
		$sql = implode(' OR ',$sql_arr);
		return $sql; 
	}

    public static function getI18n($string, $language_code = '')
	{
        if ($language_code == '')
		{
			$language_code = DRX_Multilanguage::getLanguage();
		}

        if (strlen($language_code) == 2) {
            $language_code = strtolower($language_code);
        }
        // Try to find the chain in the string
        if (preg_match('/<'.$language_code.'>([^<]*)<\/'.$language_code.'>/',$string,$matches) > 0)
        {
            return str_replace(array('&lt;','&gt;'),array('<','>'),$matches[1]);
        }
        else
        {
        	if (strtolower($language_code) != strtolower(APP_DEFAULT_LANGUAGE)) {
				return self::getI18n($string, strtolower(APP_DEFAULT_LANGUAGE));
			} else {
                $pattern = '/<([a-z]{2}(_[A-Z]{2})*?)>(.*?)<\/([a-z]{2}(_[A-Z]{2})*?)>/s';
	            preg_match($pattern, $string, $matches);
	
	            if(count($matches) == 4)
	            {
	                return str_replace(array('&lt;','&gt;'),array('<','>'),$matches[2]);
	            } else if(count($matches) == 5 || count($matches) == 6)
	            {
	                return str_replace(array('&lt;','&gt;'),array('<','>'),$matches[3]);
	            } else {
	                return str_replace(array('&lt;','&gt;'),array('<','>'),$string);
	            }
			}
        }
  	}

    public static function getI18nRealValueSql($field_name, $lang_input = '')
	{
        if (!$lang_input) {
            $lang_input = DRX_Multilanguage::getLanguage();
        }
        if (strlen($lang_input) == 2) {
            $lang_input = strtolower($lang_input);
        }
        $len_lang_input = strlen($lang_input)+2;
        
        $lang_default = DEFAULT_LANGUAGE;
        $len_lang_default = strlen($lang_default)+2;
        
        $str = "SUBSTR(".$field_name.",
		IF(POSITION('<".$lang_input.">' IN ".$field_name.")=0, 
			( IF(POSITION('<".$lang_default.">' IN ".$field_name.")=0, POSITION('>' IN ".$field_name.")+1, POSITION('<".$lang_default.">' IN ".$field_name.")+".$len_lang_default.") ), 
		POSITION('<".$lang_input.">' IN ".$field_name.")+".$len_lang_input."))";
        $str = "TRIM(SUBSTR($str, 1, POSITION('</' IN $str)-1))";
		return $str;
	}

	public static function getI18nRealStringSql($field_name)
    {
        //return "i18nString($field_name,'" . DRX_Multilanguage::getLanguage() . "','" . APP_DEFAULT_LANGUAGE . "')";
        return self::getI18nRealValueSql($field_name);
    }


	public static function convertUTF8($text){
	  if(is_array($text))
	    {
	      foreach($text as $k => $v)
	    {
	      $text[$k] = self::convertUTF8($v);
	    }
	      return $text;
	    }
	
	    $max = strlen($text);
	    $buf = "";
	    for($i = 0; $i < $max; $i++){
	        $c1 = $text{$i};
	        if($c1>="\xc0"){ //Should be converted to UTF8, if it's not UTF8 already
	          $c2 = $i+1 >= $max? "\x00" : $text{$i+1};
	          $c3 = $i+2 >= $max? "\x00" : $text{$i+2};
	          $c4 = $i+3 >= $max? "\x00" : $text{$i+3};
	            if($c1 >= "\xc0" & $c1 <= "\xdf"){ //looks like 2 bytes UTF8
	                if($c2 >= "\x80" && $c2 <= "\xbf"){ //yeah, almost sure it's UTF8 already
	                    $buf .= $c1 . $c2;
	                    $i++;
	                } else { //not valid UTF8.  Convert it.
	                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
	                    $cc2 = ($c1 & "\x3f") | "\x80";
	                    $buf .= $cc1 . $cc2;
	                }
	            } elseif($c1 >= "\xe0" & $c1 <= "\xef"){ //looks like 3 bytes UTF8
	                if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf"){ //yeah, almost sure it's UTF8 already
	                    $buf .= $c1 . $c2 . $c3;
	                    $i = $i + 2;
	                } else { //not valid UTF8.  Convert it.
	                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
	                    $cc2 = ($c1 & "\x3f") | "\x80";
	                    $buf .= $cc1 . $cc2;
	                }
	            } elseif($c1 >= "\xf0" & $c1 <= "\xf7"){ //looks like 4 bytes UTF8
	                if($c2 >= "\x80" && $c2 <= "\xbf" && $c3 >= "\x80" && $c3 <= "\xbf" && $c4 >= "\x80" && $c4 <= "\xbf"){ //yeah, almost sure it's UTF8 already
	                    $buf .= $c1 . $c2 . $c3;
	                    $i = $i + 2;
	                } else { //not valid UTF8.  Convert it.
	                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
	                    $cc2 = ($c1 & "\x3f") | "\x80";
	                    $buf .= $cc1 . $cc2;
	                }
	            } else { //doesn't look like UTF8, but should be converted
	                    $cc1 = (chr(ord($c1) / 64) | "\xc0");
	                    $cc2 = (($c1 & "\x3f") | "\x80");
	                    $buf .= $cc1 . $cc2;				
	            }
	        } elseif(($c1 & "\xc0") == "\x80"){ // needs conversion
	                $cc1 = (chr(ord($c1) / 64) | "\xc0");
	                $cc2 = (($c1 & "\x3f") | "\x80");
	                $buf .= $cc1 . $cc2;				
	        } else { // it doesn't need convesion
	            $buf .= $c1;
	        }
	    }
	    return $buf;
	}
	
	
    public static function getBasicString($str="")
    {
        $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
			'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
			'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
			'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', '-'=>' ');

		return strtr($str, $unwanted_array);
    }
    //Functions from Google

    // Encode a string to URL-safe base64
    public static function encodeBase64UrlSafe($value)
    {
      return str_replace(array('+', '/'), array('-', '_'),
        base64_encode($value));
    }

    // Decode a string from URL-safe base64
    public static function decodeBase64UrlSafe($value)
    {
      return base64_decode(str_replace(array('-', '_'), array('+', '/'),
        $value));
    }

    // Sign a URL with a given crypto key
    // Note that this URL must be properly URL-encoded
    public static function signUrl($myUrlToSign, $privateKey)
    {
      // parse the url
      $url = parse_url($myUrlToSign);

      $urlPartToSign = $url['path'] . "?" . $url['query'];

      // Decode the private key into its binary format
      $decodedKey = self::decodeBase64UrlSafe($privateKey);

      // Create a signature using the private key and the URL-encoded
      // string using HMAC SHA1. This signature will be binary.
      $signature = hash_hmac("sha1",$urlPartToSign, $decodedKey,  true);

      $encodedSignature = self::encodeBase64UrlSafe($signature);

      return $myUrlToSign."&signature=".$encodedSignature;
    }
    
    public static function isI18nStringIsExistByLanguage($source_str){
        $language = '[a-z]{2}(_[A-Z]{2})*?';
        $pattern = '/<('.$language.')>(.*?)<\/('.$language.')>/s';
        preg_match($pattern, $source_str, $source_matches);
        if(count($source_matches) > 0) {
            if(!Front_Language::isExists(0, $source_matches[1])){
                return false;
            }
            $source_str = trim(str_replace($source_matches[0], '', $source_str));
            if ($source_str) {
				return DRX_StringUtils::isI18nStringIsExistByLanguage($source_str, $language);
			}
        }else {
			if ($source_str) {
				return false;
			}
		}
        return true;
    }    
}