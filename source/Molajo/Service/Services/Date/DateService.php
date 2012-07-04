<?php
/**
 * @package   Molajo
 * @copyright 2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 */
namespace Molajo\Service\Services\Date;

use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * Date
 *
 * @package       Molajo
 * @subpackage    Service
 * @since         1.0
 */
Class DateService
{
	/**
	 * Static instance
	 *
	 * @var    object
	 * @since  1.0
	 */
	protected static $instance;

	/**
	 * getInstance
	 *
	 * @static
	 * @return bool|object
	 * @since  1.0
	 */
	public static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new DateService();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 *
	 * @return boolean
	 * @since  1.0
	 */
	protected function __construct()
	{
		return $this->getDate();
	}

	/**
	 * Converts standard MYSQL date (ex. 2011-11-11 11:11:11) to CCYY-MM-DD format (ex. 2011-11-11)
	 *
	 * @param  string  $date
	 *
	 * @return string  CCYY-MM-DD
	 * @since  1.0
	 */
	public function convertCCYYMMDD($date = null)
	{
		return substr($date, 0, 4) . '-' . substr($date, 5, 2) . '-' . substr($date, 8, 2);
	}

	/**
	 * differenceDays
	 *
	 * @param string $date1 expressed as CCYY-MM-DD
	 * @param string $date2 expressed as CCYY-MM-DD
	 *
	 * @since 1.0
	 * @return integer
	 */
	public function differenceDays($date1, $date2 = null)
	{

		if ($date2 === null) {
			$date2 = $this->convertCCYYMMDD($this->getDate());
		}

		$day_number1mm = substr($date1, 5, 2);
		$day_number1dd = substr($date1, 8, 2);
		$day_number1ccyy = substr($date1, 0, 4);

		$gregdate1 = gregoriantojd($day_number1mm, $day_number1dd, $day_number1ccyy);

		$day_number2mm = substr($date2, 5, 2);
		$day_number2dd = substr($date2, 8, 2);
		$day_number2ccyy = substr($date2, 0, 4);

		$gregdate2 = gregoriantojd($day_number2mm, $day_number2dd, $day_number2ccyy);

		return $gregdate2 - $gregdate1;
	}

	/**
	 * prettydate
	 *
	 * @param  $date
	 *
	 * @return string human-readable pretty date
	 * @since  1.0
	 */
	public function prettydate($source, $compare = null)
	{
		$source_date = new \Datetime ($source);

		if ($compare === null){
			$compare_to_date = new \DateTime('now');
		}

		$diff = $compare_to_date->format('U') - $source_date->format('U');
		$dayDiff = floor($diff / 86400);

		$prettyDate = '';

		if(is_nan($dayDiff) || $dayDiff < 0) {
			return '';
		}

		if ($dayDiff == 0) {

			if($diff < 60) {
				$prettyDate = Services::Language()->translate('JUST_NOW');

			} elseif($diff < 3600) {
				$number = floor($diff/60);
				$prettyDate = $number . ' '
					. $this->prettyDateFormat($number, 'DATE_MINUTE_SINGULAR', 'DATE_MINUTE_PLURAL')
					. ' ' . Services::Language()->translate('AGO');

			} elseif($diff < 86400) {
				$number = floor($diff/3660);
				$prettyDate = $number . ' '
					. $this->prettyDateFormat($number, 'DATE_HOUR_SINGULAR', 'DATE_HOUR_PLURAL')
					. ' ' . Services::Language()->translate('AGO');
			}

		} elseif($dayDiff == 1) {
			$pretty_date = Services::Language()->translate('YESTERDAY');

		} elseif($dayDiff < 7) {
			$number = $dayDiff;
			$prettyDate = $number . ' '
				. $this->prettyDateFormat($number, 'DATE_DAY_SINGULAR', 'DATE_DAY_PLURAL')
				. ' ' . Services::Language()->translate('AGO');

		} elseif($dayDiff < (7*6)) {
			$number = ceil($dayDiff/7);
			$prettyDate = $number . ' '
				. $this->prettyDateFormat($number, 'DATE_WEEK_SINGULAR', 'DATE_WEEK_PLURAL')
				. ' ' . Services::Language()->translate('AGO');

		} elseif($dayDiff < 365) {
			$number = ceil($dayDiff/(365/12));
			$prettyDate = $number . ' '
				. $this->prettyDateFormat($number, 'DATE_MONTH_SINGULAR', 'DATE_MONTH_PLURAL')
				. ' ' . Services::Language()->translate('AGO');

		} else {
			$number = ceil($dayDiff/(365));
			$prettyDate = $number . ' '
				. $this->prettyDateFormat($number, 'DATE_YEAR_SINGULAR', 'DATE_YEAR_PLURAL')
				. ' ' . Services::Language()->translate('AGO');
		}

		return $prettyDate;
	}

	/**
	 * prettyDateFormat
	 *
	 * @param  $numeric_value
	 * @param  $singular_literal
	 * @param  $plural_literal
	 *
	 * @return mixed
	 * @since  1.0
	 */
	public function prettyDateFormat($numeric_value, $singular_literal, $plural_literal)
	{
		if ($numeric_value == 0) {
			return '';
		}

		if ($numeric_value == 1) {
			return strtolower(Services::Language()->translate($singular_literal));
		}

		return strtolower(Services::Language()->translate($plural_literal));
	}

	/**
	 * getDayName
	 *
	 * Provides translated name of day in abbreviated or full format, given day number
	 *
	 * @param $day_number
	 * @param bool $abbreviation
	 *
	 * @return string
	 * @since  1.0
	 */
	public function getDayName($day_number, $abbreviation = false)
	{
		switch ($day_number) {
			case 1:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_MON');
				} else {
					Services::Language()->translate('DATE_MONDAY');
				}
				break;
			case 2:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_TUE');
				} else {
					Services::Language()->translate('DATE_TUESDAY');
				}
				break;
			case 3:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_WED');
				} else {
					Services::Language()->translate('DATE_WEDNESDAY');
				}
				break;
			case 4:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_THU');
				} else {
					Services::Language()->translate('DATE_THURSDAY');
				}
				break;
			case 5:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_FRI');
				} else {
					Services::Language()->translate('DATE_FRIDAY');
				}
				break;
			case 6:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_SAT');
				} else {
					Services::Language()->translate('DATE_SATURDAY');
				}
				break;
			default:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_SUN');
				} else {
					Services::Language()->translate('DATE_SUNDAY');
				}
				break;
		}
	}

	/**
	 * getMonthName
	 *
	 * Provides translated name of month in abbreviated or full format, given month number
	 *
	 * @param string $month_number
	 * @param bool   $abbreviation
	 *
	 * @return string
	 * @since  1.0
	 */
	public function getMonthName($month_number, $abbreviation = false)
	{
		switch ($month_number) {
			case 1:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_JAN');
				} else {
					Services::Language()->translate('DATE_JANUARY');
				}
			case 2:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_FEB');
				} else {
					Services::Language()->translate('DATE_FEBRUARY');
				}
			case 3:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_MAR');
				} else {
					Services::Language()->translate('DATE_MARCH');
				}
			case 4:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_APR');
				} else {
					Services::Language()->translate('DATE_APRIL');
				}
			case 5:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_MAY');
				} else {
					Services::Language()->translate('DATE_MAY');
				}
			case 6:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_JUN');
				} else {
					Services::Language()->translate('DATE_JUNE');
				}
			case 7:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_JUL');
				} else {
					Services::Language()->translate('DATE_JULY');
				}
			case 8:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_AUG');
				} else {
					Services::Language()->translate('DATE_AUGUST');
				}
			case 9:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_SEP');
				} else {
					Services::Language()->translate('DATE_SEPTEMBER');
				}
			case 10:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_OCT');
				} else {
					Services::Language()->translate('DATE_OCTOBER');
				}
			case 11:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_NOV');
				} else {
					Services::Language()->translate('DATE_NOVEMBER');
				}
			default:
				if ($abbreviation === true) {
					Services::Language()->translate('DATE_DECEMBER');
				} else {
					Services::Language()->translate('DATE_DECEMBER');
				}
		}
	}

	/**
	 * buildCalendar
	 *
	 *
	 * $d = getdate();
	 * $month = $d['mon'];
	 * $year = $d['year'];
	 * $calendar = Services::Date()->buildCalendar ($month, $year);
	 *
	 * @param string $month
	 * @param string $year
	 *
	 * @return string CCYY-MM-DD
	 * @since   1.0
	 */
//todo: Amy - redo to generate a set of dates in a model, combine with other data, pass to a view for rendering

	/**
	 * @param $month
	 * @param $year
	 * @return string
	 */
	public function buildCalendar($month, $year)
	{
		$day_numbersOfWeek = array('S', 'M', 'T', 'W', 'T', 'F', 'S');
		$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
		$numberDays = date('t', $firstDayOfMonth);
		$dateComponents = getdate($firstDayOfMonth);
		$monthName = $dateComponents['month'];
		$day_numberOfWeek = $dateComponents['wday'];

		$calendar = "<table class='calendar'>";
		$calendar .= "<caption>$monthName $year</caption>";
		$calendar .= "<tr>";
		foreach ($day_numbersOfWeek as $day_number) {
			$calendar .= "<th class='header'>$day_number</th>";
		}

		$currentDay = 1;
		$calendar .= "</tr><tr>";
		if ($day_numberOfWeek > 0) {
			$calendar .= "<td colspan='$day_numberOfWeek'>&nbsp;</td>";
		}

		$month = str_pad($month, 2, "0", STR_PAD_LEFT);
		while ($currentDay <= $numberDays) {
			if ($day_numberOfWeek == 7) {
				$day_numberOfWeek = 0;
				$calendar .= "</tr><tr>";
			}
			$currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
			$date = "$year-$month-$currentDayRel";
			$calendar .= "<td class='day' rel='$date'>$currentDay</td>";
			$currentDay++;
			$day_numberOfWeek++;
		}

		if ($day_numberOfWeek != 7) {
			$remainingDays = 7 - $day_numberOfWeek;
			$calendar .= "<td colspan='$remainingDays'>&nbsp;</td>";
		}

		$calendar .= "</tr>";
		$calendar .= "</table>";

		return $calendar;
	}

	/**
	 * getTimezoneDate - retrieves a date in the server or user timezone
	 *
	 * @param $utc_date
	 * @param string $server_or_user_UTC
	 *
	 * @return string
	 * @since  1.0
	 */
	public function getTimezoneDate($utc_date, $server_or_user_utc = 'user')
	{

		if (intval($utc_date)) {
		} else {
			return false;
		}

		$timezone = $this->getTimezone($server_or_user_utc);

		$date = $this->getDate($utc_date, 'UTC');
		$date->setTimezone(new \DateTimeZone($timezone));

		return $date->toSql(true);

	}

	/**
	 * Get the Time Zone of the user or server
	 *
	 * @param string $server_or_user_utc
	 *
	 * @return string
	 * @since  1.0
	 */
	public function getTimezone($server_or_user_utc = 'user')
	{
		$offset = '';
		if ($server_or_user_utc == 'server') {
		} else {
			if (Services::Registry()->exists('User')) {
				$offset = Services::Registry()->get('User', 'timezone', '');
			}
		}
		if ($offset == '') {
			$offset = Services::Registry()->get('Configuration', 'language_utc_offset', '');
		}
		if ($offset == '') {
			$offset = 'UTC';
		}

		return $offset;
	}

	/**
	 * getDate - prepares Date object
	 *
	 * @param mixed $time     The initial time for the Date object
	 * @param mixed $tzOffset The timezone offset.
	 *
	 * @return Date object
	 * @since  1.0
	 */
	public function getDate($time = 'now', $tzOffset = null)
	{
		if ($time == '') {
			$time = 'now';
		}
		$locale = Services::Language()->get('tag', 'en-GB');

		$class = str_replace('-', '_', $locale) . 'Date';
		if (class_exists($class)) {
		} else {
			$class = 'Joomla\\date\\JDate';
		}

		return new $class($time, $tzOffset);
	}
}
