<?php
class App_View_Helper_Get extends Zend_View_Helper_Abstract {
	
	public function get($param) {
		$request = Zend_Controller_Front::getInstance ()->getRequest ();
		$name = $param ['name'];
		switch ($name) {
			default :
			case 'url' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					return $options->site->url->idlikevn;
				}
				break;
			case 'url_saga' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					return $options->site->url->sagalikevn;
				}
				break;
			/*case 'action' :
				return $request->getActionName ();
				break;
			case 'controller' :
				return $request->getControllerName ();
				break;
			case 'module' :
				return $request->getModuleName ();
				break;
			case 'key' :
				$rnd = rand ( 10, 99 );
				return $rnd . substr ( md5 ( $rnd ), 0, 11 ) . ($rnd + $value);
				break;
			case 'dekey' :
				$rnd = substr ( $value, 0, 2 );
				return str_replace ( substr ( $value, 0, 13 ), '', $value ) - $rnd;
				break;
			case 'fbappurl' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					return $options ['miis_facebook'] ['url'];
				}
				break;
			case 'backendUrl' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					return $options ['miis_app'] ['http_host'] . '/backend';
				}
				break;
			case 'canvasurl' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					return $options ['miis_facebook'] ['canvasurl'];
				}
				break;
			case 'appName' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					return $options ['miis_facebook'] ['appName'];
				}
				break;
			case 'show' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					return explode ( ',', $options ['miis_app'] ['show'] );
				}
				return array (10, 20, 100, 200, 500 );
				break;
			case 'appId' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					return $options ['miis_facebook'] ['appId'];
				}
				break;
			case 'language' :
				return App_Util::getLanguage ();
				break;
			case 'currency' :
				return App_Util::getCurrency ();
				break;
			case 'symbol' :
				return App_Util::getCurrency ( true );
				break;
			case 'token' :
				$a = $request->getActionName ();
				return App_Util::getToken ();
				break;
			case 'dateFormat' :
				$time = strtotime ( $value );
				if ($time !== false) {
					$format = (empty ( $format ) ? '%d%s %s %d' : $format);
					return sprintf ( $format, date ( 'd', $time ), date ( 'S', $time ), date ( 'M', $time ), date ( 'Y', $time ) );
				}
				return '';
				break;
			case 'numFormat' :
				if (empty ( $format ))
					$format = 0;
				return number_format ( $value, $format );
				break;
			case 'sizeFormat' :
				if (empty ( $format ))
					$format = 2;
				return App_Util::formatSize ( $value, $format );
				break;
			case 'trunName' :
				return App_Util::truncate ( $value, $format );
				break;
			case 'prettyTime' :
				$time_rem = $value;
				$time_sec = $time_rem % 60;
				$time_min = floor ( $time_rem / 60 ) % 60;
				$time_hour = floor ( $time_rem / 3600 ) % 24;
				$time_day = floor ( $time_rem / (24 * 3600) );
				$count = substr_count ( $format, '%s' );
				if ($count == 4)
					$time_rem_pretty = sprintf ( $format, $time_day, $time_hour, $time_min, $time_sec );
				elseif ($count == 3)
					$time_rem_pretty = sprintf ( $format, $time_hour, $time_min, $time_sec );
				elseif ($count == 2)
					$time_rem_pretty = sprintf ( $format, $time_min, $time_sec );
				else
					$time_rem_pretty = sprintf ( $format, $time_sec );
				return $time_rem_pretty;
				break;
			case 'direction' :
				$session = App_Util::getSession ();
				//sort direction parameter
				if ($session->__get ( 'files_sort_order' ) == $value)
					return substr ( $session->__get ( 'files_sort_direction' ), 0, 3 );
				return '';
				break;
			case 'slugify' :
				return App_Util::slugify ( $value );
				break;
			case 'country' :
				require_once 'Zend/Locale.php';
				$locale = new Zend_Locale ( 'en_US' );
				$countries = ($locale->getTranslationList ( 'Territory', 'en', 2 ));
				asort ( $countries, SORT_LOCALE_STRING );
				return $countries;
				break;
			case 'months' :
				require_once 'Zend/Locale.php';
				$locale = new Zend_Locale ( 'en_US' );
				$months = ($locale->getTranslationList ( 'Months', 'en' ));
				asort ( $months, SORT_LOCALE_STRING );
				return $months ['format'] ['wide'];
				break;
			case 'exchange' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					if (isset ( $options ['exchange'] [$value] ))
						return $options ['exchange'] [$value];
				}
				return array ();
				break;
			case 'eSupport' :
				return file_get_contents ( 'http://gigasize.webminds-support.com/giga_open.php' );
				break;
			case 'products' :
				if (Zend_Registry::isRegistered ( 'options' )) {
					$options = Zend_Registry::get ( 'options' );
					return $options ['products'];
				}
				return array ();
				break;
			case 'captcha' :
				return App_Util::generateCaptcha ();
				break;
			case 'uri' :
				return str_replace ( '/', '', $_SERVER ['REQUEST_URI'] );
				break;*/
		}
	}
}
