<?

namespace rude;

class date
{
	/**
	 * @en Sets the default timezone
	 * @ru Устанавливает временную зону по умолчанию
	 *
	 * $success = date::set_timezone('Europe/Minsk'); # bool(true) if success or bool(false) if failed
	 *
	 * @param $timezone
	 * @return bool
	 */
	public static function set_timezone($timezone)
	{
		return date_default_timezone_set($timezone);
	}

	public static function date($separator = '-', $custom_timestamp = null)
	{
		if ($custom_timestamp !== null)
		{
			return date('Y' . $separator . 'm' . $separator . 'd', $custom_timestamp);
		}

		return date('Y' . $separator . 'm' . $separator . 'd');
	}

	public static function time($separator = ':', $custom_timestamp = null)
	{
		if ($custom_timestamp !== null)
		{
			return date('H' . $separator . 'i' . $separator . 's', $custom_timestamp);
		}

		return date('H' . $separator . 'i' . $separator . 's');
	}

	public static function hour()
	{
		return date('H');
	}

	public static function minute()
	{
		return date('i');
	}

	public static function timestamp($microseconds = false, $microseconds_get_as_float = null)
	{
		if ($microseconds === false)
		{
			return microtime($microseconds_get_as_float);
		}

		return time();
	}

	/**
	 * @en Get current date and time ("YYYY-MM-DD HH:MM:SS")
	 * @ru Возвращает текущую дату и время в формате "ГГГГ-ММ-ДД ЧЧ:ММ:СС"
	 *
	 * $result = date::datetime();         # string(19) "2014-08-31 18:57:49"
	 * $result = date::datetime('/');      # string(19) "2014/08/31 18:57:49"
	 * $result = date::datetime('/', '-'); # string(19) "2014/08/31 18-57-49"
	 *
	 * @param string $separator_date
	 * @param string $separator_time
	 * @param int $custom_timestamp
	 *
	 * @return string
	 */
	public static function datetime($separator_date = '-', $separator_time = ':', $custom_timestamp = null)
	{
		return date::date($separator_date, $custom_timestamp) . ' ' . date::time($separator_time, $custom_timestamp);
	}

	public static function day($timestamp = null)
	{
		if ($timestamp !== null)
		{
			return date('z', $timestamp) + 1;
		}

		return date('z') + 1;
	}

	public static function week($timestamp = null)
	{
		if ($timestamp !== null)
		{
			return (int) date('W', $timestamp);
		}

		return (int) date('W');
	}

	/**
	 * @en Get current month ("MM")
	 * @ru Возвращает порядкойвый номер текущего месяца ("ММ")
	 *
	 * $result = date::month(); # 08
	 *
	 * @param int|null $timestamp
	 *
	 * @return string
	 */
	public static function month($timestamp = null)
	{
		if ($timestamp !== null)
		{
			return date('m', $timestamp);
		}

		return date('m');
	}

	/**
	 * @en Get current year ("YYYY")
	 *
	 * $result = date::year(); # 2014
	 *
	 * @param int|null $timestamp
	 *
	 * @return string
	 */
	public static function year($timestamp = null)
	{
		if ($timestamp !== null)
		{
			return date('Y', $timestamp);
		}

		return date('Y');
	}

	/**
	 * @en Total number of days in year
	 * @ru Возвращает число дней в году
	 *
	 * $result = date::days_in_year();     # int(365) # default year is current (365 days in 2014)
	 * $result = date::days_in_year(2010); # int(365)
	 * $result = date::days_in_year(2004); # int(366)
	 * $result = date::days_in_year(1970); # int(365)
	 *
	 * @param int $year
	 *
	 * @return string
	 */
	public static function days_in_year($year = null)
	{
		if ($year === null)
		{
			$year = date::year();
		}

		return date('z', mktime(0, 0, 0, 12, 31, $year)) + 1;
	}

	/**
	 * @en Total number of days in month
	 * @ru Возвращает число дней в указанном месяце года
	 *
	 * $result = date::days_in_month();        # int(31) # default year and month are current (31 day in August of 2014)
	 * $result = date::days_in_month(2010);    # int(31) # default month is current (31 day in August of 2010)
	 * $result = date::days_in_month(1970, 4); # int(30) # 30 days in April of 1970
	 * $result = date::days_in_month(1970, 5); # int(31) # 31 day in May of 1970
	 *
	 * @param int $year
	 * @param int $month
	 *
	 * @return int
	 */
	public static function days_in_month($year = null, $month = null)
	{
		if ($year === null)
		{
			$year = date::year();
		}

		if ($month === null)
		{
			$month = date::month();
		}

		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	}

	public static function to_timestamp($string, $format)
	{
		$date = date_create_from_format($format, $string);

		return $date->getTimestamp();
	}

	public static function weeks_in_month($year = null, $month = null)
	{
		if ($year === null)
		{
			$year = static::year();
		}

		if ($month === null)
		{
			$month = static::month();
		}


		$month_start = mktime(0, 0, 0, $month, 1, $year);                              # start of month
		$month_end   = mktime(0, 0, 0, $month, date('t', $month_start), $year);        # end of month

		$week_start = date('W', $month_start);                                         # start of week
		$week_end   = date('W', $month_end);                                           # end of week

		if ($week_end < $week_start)
		{
			return ((static::weeks_in_year($year - 1) + $week_end) - $week_start) + 1; # month wraps
		}

		return ($week_end - $week_start) + 1;
	}

	public static function week_borders($year = null, $week = null, $format = 'Y-m-d')
	{
		if ($year === null) { $year = static::year(); }
		if ($week === null) { $week = static::week(); }

		$time = strtotime("1 January $year");

		$day = date('w', $time);

		$time += ((7 * $week) + 1 - $day) * 24 * 3600;

		return [date($format, $time), date($format, $time + seconds_in_week)];
	}

	public static function weeks_in_year($year = null)
	{
		if ($year === null)
		{
			$year = static::year();
		}

		$date = new \DateTime;
		$date->setISODate($year, 53);

		return ($date->format('W') === '53' ? 53 : 52);
	}

	public static function first_day_of_month($year = null, $month = null, $format = 'j')
	{
		if ($year === null)
		{
			$year = static::year();
		}

		if ($month === null)
		{
			$month = static::month();
		}

		return date($format, mktime(0, 0, 0, $month, 1, $year));
	}

	public static function last_day_of_month($year = null, $month = null, $format = 'j')
	{
		if ($year === null)
		{
			$year = static::year();
		}

		if ($month === null)
		{
			$month = static::month();
		}

		$month_start = mktime(0, 0, 0, $month, 1, $year);

		return date($format, mktime(0, 0, 0, $month, date('t', $month_start), $year));
	}

	public static function first_day_of_week($year = null, $month = null, $week = null, $format = 'j')
	{
		if ($year === null)  { $year  = static::year();  }
		if ($month === null) { $month = static::month(); }
		if ($week === null)  { $week  = static::week();  }

		if ($week == 1)
		{
			return static::first_day_of_month($year, $month, $format);
		}

		if ($week > static::weeks_in_month($year, $month))
		{
			return 0;
		}

		$day_of_weak = static::day_of_week(static::first_day_of_month($year, $month, 'U'));

		$days_in_first_week = 8 - $day_of_weak;

		$day = 7 * ($week - 2) + $days_in_first_week + 1;

		return date($format, strtotime($year . '-' . $month . '-' . $day));
	}

	public static function last_day_of_week($year = null, $month = null, $week = null, $format = 'j')
	{
		if ($year === null)  { $year  = static::year();  }
		if ($month === null) { $month = static::month(); }
		if ($week === null)  { $week  = static::week();  }

		if ($week == static::weeks_in_month($year, $month))
		{
			return static::last_day_of_month($year, $month, $format);
		}

		if ($week > static::weeks_in_month($year, $month))
		{
			return 0;
		}


		return date($format, strtotime('this sunday', static::first_day_of_week($year, $month, $week, 'U')));
	}

	public static function calendar($year)
	{
		$tmp = new \stdClass();
		$tmp->id = $year;

		$year = $tmp;
		$year->total_weeks = static::weeks_in_year($year->id);
		$year->total_days  = static::days_in_year($year->id);


		$year->months = null;

		for ($i = 1; $i <= 12; $i++)
		{
			$month = new \stdClass();
			$month->id = $i;
			$month->total_days  = static::days_in_month($year->id, $month->id);
			$month->total_weeks = static::weeks_in_month($year->id, $month->id);
			$month->day_first   = static::first_day_of_month($year->id, $month->id);
			$month->day_last    = static::last_day_of_month($year->id, $month->id);

			$month->weeks = null;

			for ($j = 1; $j <= $month->total_weeks; $j++)
			{
				list($week_start, $week_end) = static::week_borders($year->id, $j);

				$week = new \stdClass();
				$week->id = $j;
				$week->is_first  = (int) ($j === 1);
				$week->is_last   = (int) ($j === $month->total_weeks);
				$week->day_first = static::first_day_of_week($year->id, $month->id, $week->id);
				$week->day_last  = static::last_day_of_week($year->id, $month->id, $week->id);
				$week->start     = $week_start;
				$week->end       = $week_end;

				$week->total_days = $week->day_last - $week->day_first + 1;

				$month->weeks[$week->id] = $week;
			}

			$year->months[$month->id] = $month;
		}

		return $year;
	}

	public static function day_of_week($timestamp = null)
	{
		$day = date('w', $timestamp);

		if ($day === '0')
		{
			return 7;
		}

		return (int) $day;
	}

	public static function is_monday   ($timestamp = null) { return date('w', $timestamp) === '1'; }
	public static function is_tuesday  ($timestamp = null) { return date('w', $timestamp) === '2'; }
	public static function is_wednesday($timestamp = null) { return date('w', $timestamp) === '3'; }
	public static function is_thursday ($timestamp = null) { return date('w', $timestamp) === '4'; }
	public static function is_friday   ($timestamp = null) { return date('w', $timestamp) === '5'; }
	public static function is_saturday ($timestamp = null) { return date('w', $timestamp) === '6'; }
	public static function is_sunday   ($timestamp = null) { return date('w', $timestamp) === '0'; }
}