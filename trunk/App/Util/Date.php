<?php
class App_Util_Date extends App_Util 
{
	public static function getDaysOfCurrWeek(){
		$date = new Zend_Date();
		$date->setYear(date('Y', time()))
		->setWeekDay(1);
		$weekDates = array();
		for ($day = 1; $day <= 7; $day++) {
			if ($day == 1) {
				// we're already at day 1
			}
			else {
				// get the next day in the week
				$date->addDay(1);
			}

			$weekDates[] = date('Y-m-d', $date->getTimestamp());
		}
	}

	/**
	 * Get Array Date Time array('day','month','year','hour','minute','second')
	 * @example getDateArray("26/3/2007 03:23:27","d/m/y H:i:s")
	 */
	function getDateArray( $date_time, $format="d/m/y H:i", $seperator="/")
	{
		$arr		= explode(" ",$date_time);
		$date		= $arr[0];
		if(count($arr)>1) $time			= $arr[1];

		$date_format					= substr($format,0,5);

		if( $date_format == 'd/m/y')
		{
			list($day,$month,$year)			= explode($seperator,$date);
		}
		else if( $date_format == 'm/d/y')
		{
			list($month,$day,$year)			= explode($seperator,$date);
		}
		else if($date_format == 'y/m/d')
		{
			list($year,$month,$day)			= explode($seperator,$date);
		}

		if(isset($time) && $time != '') list($hour,$minute)			= explode(":",$time);

		if(isset($time) && $time != '')
		{
			return array("day"=>$day,"month"=>$month,"year"=>$year,"hour"=>$hour,"minute"=>$minute);
		}
		return array("day"=>$day,"month"=>$month,"year"=>$year);
	}

	public static function checkDateFormat($date, $format = "/^([0-9]{4})\/([0-9]{2})\/([0-9]{2})$/")
	{
		//match the format of the date
		if (preg_match ($format, $date, $parts))
		{
			//check weather the date is valid of not
			if(checkdate($parts[2],$parts[3],$parts[1]))
			return true;
			else
			return false;
		}
		else
		return false;
	}

}