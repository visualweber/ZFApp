<?php
/**
 * http://www.regextester.com/pregsyntax.html
 * http://www.regular-expressions.info/examples.html
 * ^ : the beginning of a string
 * $ : end of string.
 * \s : single whitespace character (tab also count as whitespace)
 * + : one or more
 * | : conditional (OR)
 * g : global, mainly used for search and replace operation
 * 
 * Enter description here ...
 * @author TOAN
 *
 */
class App_Util_Validate extends App_Util {
	
	public static function isEmail($param) {
		$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
		return eregi ( $pattern, $email );
	}
	
	public static function isSpecialCharacter($param) {
		$pattern = "/[^-a-z0-9_.♥ß†♦@-]/i";
		return preg_match ( $pattern, $param ) ? true : false;
	}
	
	public static function isAlphabet($param) {
		$pattern = "/[^-a-z0-9-]/i";
		return preg_match ( $pattern, $param ) ? true : false;
	}
	
	public static function isTelephone($param) {
		;
	}
	
	/**
	 * The pattern that we use to check phone numbers will check both “standard” numbers — 555-123-4567 — as well as 800 numbers — 1-800-555-1234:
	 * /^([1]-)?[0-9]{3}-[0-9]{3}-[0-9]{4}$/i
	 * The slashes (/) at the start and end are the delimiters that signal where our pattern starts and ends. The caret (^) indicates the beginning of the string we want to check. ([1]-)? is asking “is there a ’1′ followed by a hyphen”? The question mark at the end makes this portion optional. In other words, if it’s not there — meaning that it’s not an 800 number — it won’t immediately assume that the phone number is invalid. The next part [0-9]{3}- is looking for ONLY numbers [0-9] and there must be three {3} of them. Now, the added hyphen after the {3}- makes it so that hyphens are required in the phone number. You can change this if you do not like this behavior.
	 * The following [0-9]{3}- is doing the same thing — making sure there are three numbers and a hyphen. Now, if you will notice, the last of these ends with {4}. As you my have guessed, this requires there to be four digits in the last part of the number. The $ at end signifies that this is the end of our string.
	 * The i after the final slash (/i) is a switch that tells regex to perform a case-insensitive search. In other words “aaa” would be considered the same as “AAA”. This isn’t too important when validating a phone number, but you should definitely include it when validating an email address.
	 * Enter description here ...
	 * @param unknown_type $param
	 */
	public static function isMobile($param) {
		$pattern = "/^((84))?[0-9]/i";
		$pattern = "/^((84))?[0-9]{3}[0-9]{3}[0-9]{3}$/i";
		if (strlen ( $param ) == 12) {
			$pattern = "/^((84))?[0-9]{3}[0-9]{3}[0-9]{4}$/i";
		}
		return preg_match ( $pattern, $param ) ? true : false;
	}
}