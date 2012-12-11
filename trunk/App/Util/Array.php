<?php
/**
 * 
 * Enter description here ...
 * @author TOAN
 *
 */
class App_Util_Array extends App_Util {
	
	public static function cleanEmptyValue(& $array) {
		foreach ( $array as $key => $val ) {
			if (! is_numeric ( $val )) {
				if (empty ( $val )) {
					unset ( $array [$key] );
				}
			}
		}
	}
	
	/**
	 * Function to convert array to integer values
	 *
	 * @static
	 * @param	array	$array		The source array to convert
	 * @param	mixed	$default	A default value (int|array) to assign if $array is not an array
	 * @since	1.5
	 */
	function toInteger(&$array, $default = null) {
		if (is_array ( $array )) {
			foreach ( $array as $i => $v ) {
				$array [$i] = ( int ) $v;
			}
		} else {
			if ($default === null) {
				$array = array ();
			} elseif (is_array ( $default )) {
				App_Util_Array::toInteger ( $default, null );
				$array = $default;
			} else {
				$array = array (
					( int ) $default );
			}
		}
	}
	
	/**
	 * Utility function to map an array to a stdClass object.
	 *
	 * @static
	 * @param	array	$array		The array to map.
	 * @param	string	$calss 		Name of the class to create
	 * @return	object	The object mapped from the given array
	 * @since	1.5
	 */
	function toObject(&$array, $class = 'stdClass') {
		$obj = null;
		if (is_array ( $array )) {
			$obj = new $class ();
			foreach ( $array as $k => $v ) {
				if (is_array ( $v )) {
					$obj->$k = App_Util_Array::toObject ( $v, $class );
				} else {
					$obj->$k = $v;
				}
			}
		}
		return $obj;
	}
	
	function toString($array = null, $inner_glue = '=', $outer_glue = ' ', $keepOuterKey = false) {
		$output = array ();
		
		if (is_array ( $array )) {
			foreach ( $array as $key => $item ) {
				if (is_array ( $item )) {
					if ($keepOuterKey) {
						$output [] = $key;
					}
					// This is value is an array, go and do it again!
					$output [] = App_Util_Array::toString ( $item, $inner_glue, $outer_glue, $keepOuterKey );
				} else {
					$output [] = $key . $inner_glue . '"' . $item . '"';
				}
			}
		}
		
		return implode ( $outer_glue, $output );
	}
	
	/**
	 * Utility function to map an object to an array
	 *
	 * @static
	 * @param	object	The source object
	 * @param	boolean	True to recurve through multi-level objects
	 * @param	string	An optional regular expression to match on field names
	 * @return	array	The array mapped from the given object
	 * @since	1.5
	 */
	function fromObject($p_obj, $recurse = true, $regex = null) {
		$result = null;
		if (is_object ( $p_obj )) {
			$result = array ();
			foreach ( get_object_vars ( $p_obj ) as $k => $v ) {
				if ($regex) {
					if (! preg_match ( $regex, $k )) {
						continue;
					}
				}
				if (is_object ( $v )) {
					if ($recurse) {
						$result [$k] = App_Util_Array::fromObject ( $v, $recurse, $regex );
					}
				} else {
					$result [$k] = $v;
				}
			}
		}
		return $result;
	}
	
	/**
	 * Extracts a column from an array of arrays or objects
	 *
	 * @static
	 * @param	array	$array	The source array
	 * @param	string	$index	The index of the column or name of object property
	 * @return	array	Column of values from the source array
	 * @since	1.5
	 */
	function getColumn(&$array, $index) {
		$result = array ();
		
		if (is_array ( $array )) {
			$n = count ( $array );
			for($i = 0; $i < $n; $i ++) {
				$item = & $array [$i];
				if (is_array ( $item ) && isset ( $item [$index] )) {
					$result [] = $item [$index];
				} elseif (is_object ( $item ) && isset ( $item->$index )) {
					$result [] = $item->$index;
				}
			
		// else ignore the entry
			}
		}
		return $result;
	}
	
	/**
	 * Utility function to return a value from a named array or a specified default
	 *
	 * @static
	 * @param	array	$array		A named array
	 * @param	string	$name		The key to search for
	 * @param	mixed	$default	The default value to give if no key found
	 * @param	string	$type		Return type for the variable (INT, FLOAT, STRING, WORD, BOOLEAN, ARRAY)
	 * @return	mixed	The value from the source array
	 * @since	1.5
	 */
	function getValue(&$array, $name, $default = null, $type = '') {
		// Initialize variables
		$result = null;
		
		if (isset ( $array [$name] )) {
			$result = $array [$name];
		}
		
		// Handle the default case
		if (is_null ( $result )) {
			$result = $default;
		}
		
		// Handle the type constraint
		switch (strtoupper ( $type )) {
			case 'INT' :
			case 'INTEGER' :
				// Only use the first integer value
				@ preg_match ( '/-?[0-9]+/', $result, $matches );
				$result = @ ( int ) $matches [0];
				break;
			
			case 'FLOAT' :
			case 'DOUBLE' :
				// Only use the first floating point value
				@ preg_match ( '/-?[0-9]+(\.[0-9]+)?/', $result, $matches );
				$result = @ ( float ) $matches [0];
				break;
			
			case 'BOOL' :
			case 'BOOLEAN' :
				$result = ( bool ) $result;
				break;
			
			case 'ARRAY' :
				if (! is_array ( $result )) {
					$result = array (
						$result );
				}
				break;
			
			case 'STRING' :
				$result = ( string ) $result;
				break;
			
			case 'WORD' :
				$result = ( string ) preg_replace ( '#\W#', '', $result );
				break;
			
			case 'NONE' :
			default :
				// No casting necessary
				break;
		}
		return $result;
	}
	
	/**
	 * Utility function to sort an array of objects on a given field
	 *
	 * @static
	 * @param	array	$arr		An array of objects
	 * @param	string	$k			The key to sort on
	 * @param	int		$direction	Direction to sort in [1 = Ascending] [-1 = Descending]
	 * @return	array	The sorted array of objects
	 * @since	1.5
	 */
	function sortObjects(&$a, $k, $direction = 1) {
		$GLOBALS ['JAH_so'] = array (
			'key' => $k, 
			'direction' => $direction );
		usort ( $a, array (
			'App_Util_Array', 
			'_sortObjects' ) );
		unset ( $GLOBALS ['JAH_so'] );
		
		return $a;
	}
	
	/**
	 * Private callback function for sorting an array of objects on a key
	 *
	 * @static
	 * @param	array	$a	An array of objects
	 * @param	array	$b	An array of objects
	 * @return	int		Comparison status
	 * @since	1.5
	 * @see		App_Util_Array::sortObjects()
	 */
	function _sortObjects(&$a, &$b) {
		$params = $GLOBALS ['JAH_so'];
		if ($a->$params ['key'] > $b->$params ['key']) {
			return $params ['direction'];
		}
		if ($a->$params ['key'] < $b->$params ['key']) {
			return - 1 * $params ['direction'];
		}
		return 0;
	}
	
	/**
	 * finds the selected value, then splits the array on that key, and returns the two arrays
	 * if the value was not found then it returns false
	 *
	 * @param array $array
	 * @param string $value
	 * @return mixed
	 */
	public static function splitOnValue($array, $value) {
		if (is_array ( $array )) {
			$paramPos = array_search ( $value, $array );
			
			if ($paramPos) {
				$arrays [] = array_slice ( $array, 0, $paramPos );
				$arrays [] = array_slice ( $array, $paramPos + 1 );
			} else {
				$arrays = null;
			}
			if (is_array ( $arrays )) {
				return $arrays;
			}
		}
		return null;
	}
	
	/**
	 * takes a simple array('value','3','othervalue','4')
	 * and creates a hash using the alternating values:
	 * array(
	 * 'value' => 3,
	 * 'othervalue' => 4
	 * )
	 *
	 * @param array $array
	 */
	public static function makeHashFromArray($array) {
		$hash = null;
		
		if (is_array ( $array ) && count ( $array ) > 1) {
			for($i = 0; $i <= count ( $array ); $i += 2) {
				if (isset ( $array [$i] )) {
					$key = $array [$i];
					$value = $array [$i + 1];
					if (! empty ( $key ) && ! empty ( $value )) {
						$hash [$key] = $value;
					}
				}
			}
		}
		
		if (is_array ( $hash )) {
			return $hash;
		}
	}
	
	/**
	 * takes an array:
	 * $groups = array(
	 * 'group1' => "<h2>group1......",
	 * 'group2' => "<h2>group2...."
	 * );
	 *
	 * and splits it into 2 equal (more or less) groups
	 * @param unknown_type $groups
	 */
	public static function splitGroups($groups) {
		foreach ( $groups as $k => $v ) {
			//set up an array of key = count
			$g [$k] = strlen ( $v );
			$totalItems += $g [$k];
		}
		
		//the first half is the larger of the two
		$firstHalfCount = ceil ( $totalItems / 2 );
		
		//now go through the array and add the items to the two groups.
		$first = true;
		foreach ( $g as $k => $v ) {
			if ($first) {
				$arrFirst [$k] = $groups [$k];
				$count += $v;
				if ($count > $firstHalfCount) {
					$first = false;
				}
			} else {
				$arrSecond [$k] = $groups [$k];
			}
		}
		
		$arrReturn ['first'] = $arrFirst;
		$arrReturn ['second'] = $arrSecond;
		return $arrReturn;
	}
	
	/**
	 * this function builds an associative array from a standard get request string
	 * eg: animal=dog&sound=bark
	 * will return
	 * array(
	 * animal => dog,
	 * sound => bark
	 * )
	 *
	 * @param string $getParams
	 * @return array
	 */
	public static function arrayFromGet($getParams) {
		$parts = explode ( '&', $getParams );
		if (is_array ( $parts )) {
			foreach ( $parts as $part ) {
				$paramParts = explode ( '=', $part );
				if (is_array ( $paramParts ) && count ( $paramParts ) == 2) {
					$param [$paramParts [0]] = $paramParts [1];
					unset ( $paramParts );
				}
			}
		}
		return $param;
	}
}