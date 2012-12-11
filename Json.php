<?php

class App_Json extends Zend_Json {
	
	//protected static $maxRecursionDepthAllowed = 25;
	public static function fromXML($xmlStringContents, $ignoreXmlAttributes = true) {
		
		if ((is_string ( $xmlStringContents ) == false) || (is_bool ( $ignoreXmlAttributes ) == false)) {
			throw new Zend_Json_Exception ( 'Function fromXML was called with invalid parameter(s).' );
		}
		
		// Load the XML formatted string into a Simple XML Element object.
		$simpleXmlElementObject = simplexml_load_string ( $xmlStringContents );
		
		// If it is not a valid XML content, throw an exception.
		if ($simpleXmlElementObject == null) {
			throw new Zend_Json_Exception ( 'Function fromXML was called with an invalid XML formatted string.' );
		}
		
		$resultArray = null;
		
		try {
			$resultArray = self::_processXML ( $simpleXmlElementObject, $ignoreXmlAttributes );
		} catch ( Exception $e ) {
			// Rethrow the same exception.
			throw ($e);
		}
		$jsonStringOutput = self::encode ( $resultArray );
		return ($jsonStringOutput);
	}
	
	// comes from a comment in the php manuel for the json_decode function
	public static function indent($json) {
		
		$indentedJson = '';
		$identPos = 0;
		$jsonLength = strlen ( $json );
		
		for($i = 0; $i <= $jsonLength; $i ++) {
			
			$_char = substr ( $json, $i, 1 );
			
			if ($_char == '}' || $_char == ']') {
				$indentedJson .= chr ( 13 );
				$identPos --;
				for($ident = 0; $ident < $identPos; $ident ++) {
					$indentedJson .= chr ( 9 );
				}
			}
			
			$indentedJson .= $_char;
			
			if ($_char == ',' || $_char == '{' || $_char == '[') {
				
				$indentedJson .= chr ( 13 );
				if ($_char == '{' || $_char == '[') {
					$identPos ++;
				}
				for($ident = 0; $ident < $identPos; $ident ++) {
					$indentedJson .= chr ( 9 );
				}
			}
		}
		
		return $indentedJson;
	
	}
	
	protected static function _processXML($simpleXmlElementObject, $ignoreXmlAttributes, $recursionDepth = 0) {
		// Keep an eye on how deeply we are involved in recursion.
		if ($recursionDepth > self::$maxRecursionDepthAllowed) {
			// XML tree is too deep. Exit now by throwing an exception.
			throw new Zend_Json_Exception ( "Function _processXML exceeded the allowed recursion depth of " . self::$maxRecursionDepthAllowed );
		}
		
		if ($recursionDepth == 0) {
			/* Store the original SimpleXmlElementObject sent by the caller.
            We will need it at the very end when we return from here for good.*/
			$callerProvidedSimpleXmlElementObject = $simpleXmlElementObject;
		}
		
		if (get_class ( $simpleXmlElementObject ) == "SimpleXMLElement") {
			// Get a copy of the simpleXmlElementObject
			$copyOfSimpleXmlElementObject = $simpleXmlElementObject;
			// Get the object variables in the SimpleXmlElement object for us to iterate.
			$simpleXmlElementObject = get_object_vars ( $simpleXmlElementObject );
		}
		
		// It needs to be an array of object variables.
		if (is_array ( $simpleXmlElementObject )) {
			
			$resultArray = array ();
			// Is the input array size 0? Then, we reached the rare CDATA text if any.
			if (count ( $simpleXmlElementObject ) <= 0) {
				/* Let us return the lonely CDATA. It could even be
                an empty element or just filled with whitespaces.*/
				return (trim ( strval ( $copyOfSimpleXmlElementObject ) ));
			}
			
			// Let us walk through the child elements now.
			foreach ( $simpleXmlElementObject as $key => $value ) {
				/* Check if we need to ignore the XML attributes.
                If yes, you can skip processing the XML attributes.
                Otherwise, add the XML attributes to the result array.*/
				if (($ignoreXmlAttributes == true) && ($key == "@attributes")) {
					continue;
				}
				
				/* Let us recursively process the current XML element we just visited.
                Increase the recursion depth by one.*/
				$recursionDepth ++;
				$resultArray [$key] = self::_processXML ( $value, $ignoreXmlAttributes, $recursionDepth );
				
				// Decrease the recursion depth by one.
				$recursionDepth --;
			}
			
			if ($recursionDepth == 0) {
				/* That is it. We are heading to the exit now.
                Set the XML root element name as the root [top-level] key of
                he associative array that we are going to return to the original
                caller of this recursive function.*/
				$tempArray = $resultArray;
				$resultArray = array ();
				$resultArray [$callerProvidedSimpleXmlElementObject->getName ()] = $tempArray;
			}
			
			return ($resultArray);
		
		} else {
			/* We are now looking at either the XML attribute text or
            the text between the XML tags. */
			return (trim ( strval ( $simpleXmlElementObject ) ));
		
		}
	
	}

}