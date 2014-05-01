<?php
/**
 * 
 * Enter description here ...
 * @author TOAN
 *
 */
class App_Util {
	
	// Object
	public $_config;
	
	/**
	 * @author Toan LE
	 * Enter description here ...
	 */
	public function __construct() {
	}
	
    /**
     * @author Toan LE
     * Cleanup HTML entities
     * @param unknown_type $text
     */
    function cleanHtml ($text)
    {
        return htmlentities($text, ENT_QUOTES, 'UTF-8');
    }
    
	/**
	 * remove accent char
	 * 
	 * @param mixed $str
	 * @return mixed
	 */
	public static function removeAccent($str) {
		$a = array ('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ' );
		$b = array ('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o' );
		return str_replace ( $a, $b, $str );
	}
	
	/**
	 * Generate a random string
	 *
	 * @static
	 * @param	int		$length	Length of the string to generate
	 * @return	string			Random String
	 */
	public function generateString($length = 8) {
		$salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$len = strlen ( $salt );
		$makepass = '';
		
		$stat = @stat ( __FILE__ );
		if (empty ( $stat ) || ! is_array ( $stat ))
			$stat = array (php_uname () );
		
		mt_srand ( crc32 ( microtime () . implode ( '|', $stat ) ) );
		
		for($i = 0; $i < $length; $i ++) {
			$makepass .= $salt [mt_rand ( 0, $len - 1 )];
		}
		
		return $makepass;
	}
		
	/**
	 * This function generates 11 digit (pseudo)unique ids
	 * @return string
	 */
	public static function getUniqueId() {
		$id = uniqid ();
		// $alpha = "bcdfghjklmnopqrstvwxyz0123456789";
		$alpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		for($i = 0; $i < strlen ( $id ); $i ++) {
			//$binid .= $hexbin_arr[$id[$i]];
			$binid_p [$i] = str_pad ( decbin ( hexdec ( $id [$i] ) ), 4, '0', STR_PAD_LEFT );
		}
		shuffle ( $binid_p );
		$binid = implode ( '', $binid_p );
		$id_arr = explode ( ' ', chunk_split ( $binid, 5, ' ' ) );
		$fin_id = '';
		foreach ( $id_arr as $id_part ) {
			if (! ($id_part == '')) {
				$id_p = str_pad ( $id_part, 5, '0', STR_PAD_LEFT );
				$fin_id .= $alpha [bindec ( $id_p )];
			}
		}
		return $fin_id;
	}
	
	/**
	 * Get host name
	 * @return string
	 */
	public static function getHost() {
		$host = shell_exec ( 'hostname -s' );
		$host = str_replace ( "\n", '', $host );
		return $host;
	}
	/**
	 * Get LAN IP
	 * @return string
	 */
	public static function getLanIp() {
		$ip = self::getHost ();
		if (empty ( $ip )) {
			$ip = $_SERVER ["SERVER_ADDR"];
		}
		//try to get one ip in lan
		$ip_text = explode ( "\n", shell_exec ( "ifconfig | grep inet\ addr | grep 10.0. | sed -e 's/^\ *//g' | cut -d \  -f 2 | grep -o '10.0[0-9.]*' | head -n 1" ) );
		//for local version
		if (isset ( $ip_text [0] ) && ! empty ( $ip_text [0] )) {
			$ip = $ip_text [0];
		}
		return $ip;
	}
	public static function getClientIp($long = false) {
		/**
        $req = Zend_Controller_Front::getInstance()->getRequest();
        if($req){
            $ip = $req->getClientIp();
        }else{
            $ip =    $_SERVER["REMOTE_ADDR"];
        }
		 **/
		$ip = $_SERVER ["REMOTE_ADDR"];
		if ($long)
			return sprintf ( '%u', ip2long ( $ip ) );
		return $ip;
	}
	public static function getHttpAgent() {
		return $_SERVER ['HTTP_USER_AGENT'];
	}
	/**
	 * write content to a file
	 * 
	 * @param mixed $path
	 * @param mixed $content
	 * @param mixed $mode
	 */
	public static function writeFile($path, $content, $mode = 'w') {
		if ($fp = fopen ( $path, $mode )) {
			fwrite ( $fp, $content );
			fclose ( $fp );
			@chmod ( $path, 0777 );
		}
	}
	/**
	 * Cleanup the uploaded directory to remove files that are older than one hour
	 *
	 * Notice that it is coded for PHP4 instead using PHP5's scandir() 'cause of old incompatible setups
	 */
	public static function cleanupFolder($dir) {
		// 0-Select the time() from 60 minutes ago
		$miniTime = time () - 3600;
		// 1-Obtain file list        
		$dh = opendir ( $dir );
		if ($dh) {
			while ( false !== ($filename = readdir ( $dh )) ) {
				if (! $filename)
					break;
				$files [] = $filename;
			}
		}
		closedir ( $dh );
		// 2-for each file gets it's time() then delete if too old ( more than 1 hour)
		foreach ( $files as $file ) {
			$p = strpos ( $file, '-' );
			if ($p !== false) {
				// We only process files with a dash '-' naturally
				$time = substr ( $file, 0, $p );
				if (doubleval ( $time ) < doubleval ( $miniTime )) {
					// Too old, it's time is under our limit, so we remove it!
					$filename = $dir . '/' . $file;
					@unlink ( $filename );
				}
			}
		}
	
	}
	/**
	 * Encryption routines that uses XOR should be considered secure.
	 * 
	 * @param mixed $string
	 * @return string
	 */
	public static function encrypt($string) {
		
		$key = 'BaI123vUI!@#';
		$result = '';
		for($i = 0; $i < strlen ( $string ); $i ++) {
			$char = substr ( $string, $i, 1 );
			$keychar = substr ( $key, ($i % strlen ( $key )) - 1, 1 );
			$char = chr ( ord ( $char ) + ord ( $keychar ) );
			$result .= $char;
		}
		$string = str_replace ( '+', ':gplus:', base64_encode ( $result ) );
		return urlencode ( $string );
	}
	/**
	 * decryption routines that uses XOR should be considered secure.
	 * 
	 * @param string $string
	 */
	public static function decrypt($string) {
		$key = 'BaI123vUI!@#';
		$result = '';
		$string = str_replace ( ':gplus:', '+', urldecode ( $string ) );
		$string = base64_decode ( $string );
		for($i = 0; $i < strlen ( $string ); $i ++) {
			$char = substr ( $string, $i, 1 );
			$keychar = substr ( $key, ($i % strlen ( $key )) - 1, 1 );
			$char = chr ( ord ( $char ) - ord ( $keychar ) );
			$result .= $char;
		}
		return $result;
	}
	/**
	 * get session
	 * @return Zend_Session_Namespace
	 */
	public static function getSession() {
		try {
			if (! Zend_Session::isStarted ()) {
				Zend_Session::start ();
			}
			$session = new Zend_Session_Namespace ( 'App_APPLICATION_NAMESPACE' );
			// unlocking read-only lock
			if ($session->isLocked ()) {
				$session->unLock ();
			}
			return $session;
		} catch ( Zend_Session_Exception $e ) {
			return null;
		}
	}
	/**
	 * read value from cookie
	 * 
	 */
	public static function readCookie($cookie_name) {
		$cookie = new App_Auth_Storage_Cookie ( $cookie_name );
		if (Zend_Registry::isRegistered ( 'config' )) {
			$config = Zend_Registry::get ( 'config' );
			$cookie->setDomain ( $config->xm_app->auth->cookie->domain )->setExpiration ( $config->xm_app->auth->cookie->remember_me_seconds );
			unset ( $config );
		}
		
		return $cookie->read ();
	}
	
	/**
	 * write value to cookie
	 * 
	 */
	public static function writeCookie($cookie_name, $value) {
		$cookie = new App_Auth_Storage_Cookie ( $cookie_name );
		if (Zend_Registry::isRegistered ( 'config' )) {
			$config = Zend_Registry::get ( 'config' );
			$cookie->setDomain ( $config->xm_app->auth->cookie->domain );
			$cookie->setExpiration ( $config->xm_app->auth->cookie->remember_me_seconds );
			unset ( $config );
		}
		$cookie->write ( $value );
	}
	
	/**
	 * clear to cookie
	 * 
	 */
	public static function clearCookie($cookie_name = null) {
		if (null !== $cookie_name) {
			$cookie = new App_Auth_Storage_Cookie ( $cookie_name );
			if (Zend_Registry::isRegistered ( 'config' )) {
				$config = Zend_Registry::get ( 'config' );
				$cookie->setDomain ( $config->xm_app->auth->cookie->domain );
				$cookie->setExpiration ( $config->xm_app->auth->cookie->remember_me_seconds );
				unset ( $config );
			}
			$cookie->clear ();
		}
	}
	
	/**
	 * send email
	 * 
	 * @param mixed $from
	 * @param mixed $to
	 * @param mixed $subject
	 * @param mixed $html_content
	 * @param mixed $alt
	 * @param mixed $cc
	 * @return Zend_Mail
	 */
	public static function sendMail($from, $to, $subject, $html_content = '', $alt = '', $cc = '') {
		$mail = new Zend_Mail ( 'utf-8' );
		if ($mail) {
			if (! empty ( $from )) {
				$from_email = $from;
				$from_name = 'No-reply';
				if (is_array ( $from )) {
					$from_name = $from [0];
					$from_email = $from [1];
				}
				$mail->setFrom ( $from_email, $from_name );
				$mail->setReplyTo ( $from_email );
			}
			$mail->addTo ( $to );
			$mail->setSubject ( $subject );
			if (! empty ( $html_content ))
				$mail->setBodyHtml ( $html_content );
			
			if (! empty ( $alt ))
				$mail->setBodyText ( $alt );
			if ($cc) {
				$mail->addCc ( $cc );
			}
			return $mail->send ();
		}
		return false;
	}
	/**
	 * Modifies a string to remove all non ASCII characters and spaces.
	 */
	public static function slugify($text) {
		// replace non letter or digits by -
		$text = preg_replace ( '~[^\\pL\d]+~u', '-', $text );
		// trim
		$text = trim ( $text, '-' );
		// transliterate
		if (function_exists ( 'iconv' )) {
			$text = iconv ( 'utf-8', 'us-ascii//TRANSLIT', $text );
		}
		// lowercase
		$text = strtolower ( $text );
		// remove unwanted characters
		$text = preg_replace ( '~[^-\w]+~', '', $text );
		if (empty ( $text )) {
			return 'n-a';
		}
		return $text;
	}
	/**
	 * trancate string
	 * 
	 * @param mixed $value
	 * @param mixed $format
	 */
	public static function truncate($value = '', $format = '') {
		if (empty ( $format ))
			$format = 70;
		if (strlen ( $value ) > $format) {
			$string = wordwrap ( $value, $format );
			$string = substr ( $value, 0, strpos ( $string, "\n" ) );
			if (strlen ( $string ) < ceil ( $format / 2 ))
				$string = substr ( $value, 0, $format );
			return $string . '...';
		}
		return $value;
	}
	
	public static function getServerName() {
		return $_SERVER ['SERVER_NAME'];
	}
	
	public static function getExt($filename) {
		if (function_exists ( 'pathinfo' )) {
			$path_info = pathinfo ( $filename );
			return strtolower ( $path_info ['extension'] );
		}
		return strtolower ( end ( explode ( ".", $filename ) ) );
	}
	public static function mkdir($path, $id) {
		$patt = str_pad ( $id, 8, '0', STR_PAD_LEFT );
		$l1 = substr ( $patt, 0, 2 );
		$l2 = substr ( $patt, 2, 2 );
		$l3 = substr ( $patt, 4, 2 );
		$l4 = substr ( $patt, 6 );
		$patt = '/' . $l1;
		if (! is_dir ( $path . $patt )) {
			mkdir ( $path . $patt, 0777 );
		}
		$patt .= '/' . $l2;
		if (! is_dir ( $path . $patt )) {
			mkdir ( $path . $patt, 0777 );
		}
		$patt .= '/' . $l3;
		if (! is_dir ( $path . $patt )) {
			mkdir ( $path . $patt, 0777 );
		}
		$patt .= '/' . $l4;
		if (! is_dir ( $path . $patt )) {
			mkdir ( $path . $patt, 0777 );
		}
		return $patt;
	}
	
	/**
	 * generate a token
	 * 
	 * @param mixed $generate
	 * @return string
	 */
	public static function getToken($generate = false) {
		try {
			$session = self::getSession ();
			$session->setExpirationSeconds ( 900, 'token' );
			if ($generate) {
				if ($session->isLocked ()) {
					$session->unlock ();
				}
				$ts = self::getUniqueId ();
				$session->__set ( 'token', $ts );
			} else {
				$ts = $session->__get ( 'token' );
				if (! $ts) {
					$ts = self::getUniqueId ();
					$session->__set ( 'token', $ts );
				}
			}
			$session->lock ();
			return $ts;
		} catch ( Zend_Exception $e ) {
			return '';
		}
	}
	/**
	 * generate a token
	 * 
	 * @param mixed $generate
	 * @return string
	 */
	public static function accessToken($generate = false, $value) {
		try {
			$session = self::getSession ();
			$session->setExpirationSeconds ( 900, 'access_token' );
			if ($generate) {
				if ($session->isLocked ()) {
					$session->unlock ();
				}
				$ts = $value;
				$session->__set ( 'access_token', $ts );
			} else {
				$ts = $session->__get ( 'access_token' );
				if (! $ts) {
					$ts = $value;
					$session->__set ( 'access_token', $ts );
				}
			}
			$session->lock ();
			return $ts;
		} catch ( Zend_Exception $e ) {
			return '';
		}
	}
	/**
	 * clear current token
	 * 
	 * @param mixed $generate
	 * @return string
	 */
	public static function clearToken() {
		$session = self::getSession ();
		if ($session->isLocked ()) {
			$session->unlock ();
		}
		$session->__unset ( 'token' );
		$session->lock ();
	}
	/**
	 *generates an instance of Zend_Captcha	 
	 *@param string $format
    @return string: ID of captcha session
	 * 
	 */
	public static function generateCaptcha($render = false, $format = '%s %s', $field_name = 'captcha') {
		require_once ('Zend/Captcha/Image.php');
		$imgDir = PATH_PROJECT . "/htdocs/images/captcha";
		$captcha = new Zend_Captcha_Image ( array ('wordLen' => 5, 'font' => PATH_PROJECT . '/data/fonts/Glasten_Bold.ttf', 'imgDir' => $imgDir, 'imgUrl' => '/images/captcha', 'width' => 120, 'height' => 40, 'dotNoiseLevel' => 0, 'lineNoiseLevel' => 0 ) );
		$captcha->setFontSize ( '18' );
		$id = $captcha->generate ();
		$input = '<input type="text" name="' . $field_name . '[input]" value="" /><input type="hidden" value="' . $captcha->getId () . '" name="' . $field_name . '[id]" />';
		$image = $captcha->render ();
		return sprintf ( $format, $image, $input );
	}
	/**
	 * validates captcha response
	 * @param mixed $captcha
	 */
	public static function validateCaptcha($captchaInput, $captchaId) {
		$captchaIterator = $_SESSION ['Zend_Form_Captcha_' . $captchaId];
		$captchaWord = $captchaIterator ['word'];
		if (! empty ( $captchaWord )) {
			if (strtoupper ( $captchaWord ) != strtoupper ( $captchaInput )) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	
	}
	/**	 
	 * Excute query
	 * @param $queries
	 * @param $type
	 */
	public static function getDataWithQuery($queries, $type = 'all') {
		$adapter = Zend_Db_Table_Abstract::getDefaultAdapter ();
		if ($type == 'one') {
			$result = $adapter->fetchOne ( $queries );
		} elseif ($type == 'row') {
			$result = $adapter->fetchRow ( $queries );
		} elseif ($type == 'col') {
			$result = $adapter->fetchCol ( $queries );
		} elseif ($type == 'all') {
			$result = $adapter->fetchAll ( $queries );
		}
		return $result;
	}
	
	public static function isValidEmail($email) {
		return eregi ( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email );
	}
	
	public static function getLogger($logPath = "/data/logs/register-email.log") {
		$logger = new Zend_Log ();
		$writer = new Zend_Log_Writer_Stream ( PATH_PROJECT . $logPath );
		$logger->addWriter ( $writer );
		$logger->registerErrorHandler ();
		
		return $logger;
	}
    
    public static function detectLanguageCode() {
        $langcode = explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $langcode = explode(",", $langcode['0']);
        $langcode = explode("-", $langcode['0']);
        return $langcode['0'];
    }  
    
    public static function debug($params, $quit = 0){
        echo '<pre>';
        print_R($params);
        echo '</pre>';

        if($quit) exit();    
    }
    
    function validatePhoneNumber($phone_number="")
    {
        if(strlen($phone_number) < 0)
            return false;

        $pattern = "/^\(?(\+?\d{2}|\d{3})\)?([-. ]?\(?([0-9]+)?\)?)+$/";

        if(!preg_match($pattern, $phone_number))
        {
            return false;
        }

        return true;
    }
    
    function qencode($str) {
        $str = preg_replace("[\r\n]", "", $str);
        $str = preg_replace('/( [\000-\011\013\014\016-\037\075\077\137\177-\377] )/e',
            "'='.sprintf( '%02X', ord( '\1' ))", $str);
        $str = str_replace(" ", "_", $str);
        return $str;
    }

    function encode_mime($str) {
        $deb = "=?iso-8859-1?q?";
        $fin = "?=";
        $crlf = "\r\n";

        $longueur = 75 - strlen($deb) - strlen($fin);
        $longueur_step = floor($longueur / 3); // Ratio 1/3 sur encodage q pour etre sur

        $encoded = "";

        for ($i = 0; $i < strlen($str); $i += $longueur_step) {
            $encoded .= ' ' . $deb . qencode(substr($str, $i, $longueur_step)) . $fin . $crlf;
        }

        // On enleve le premier espace et le dernier crlf
        return substr($encoded, 1, - strlen($crlf));
    }
}
