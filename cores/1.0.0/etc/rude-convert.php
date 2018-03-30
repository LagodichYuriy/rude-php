<?

namespace rude;

class convert
{
	public static function seconds_to_nanoseconds ($seconds) { return $seconds / seconds_in_nanosecond;  }
	public static function seconds_to_microseconds($seconds) { return $seconds / seconds_in_microsecond; }
	public static function seconds_to_milliseconds($seconds) { return $seconds / seconds_in_millisecond; }
	public static function seconds_to_minutes     ($seconds) { return $seconds / seconds_in_minute;      }
	public static function seconds_to_hours       ($seconds) { return $seconds / seconds_in_hour;        }
	public static function seconds_to_days        ($seconds) { return $seconds / seconds_in_day;         }
	public static function seconds_to_weeks       ($seconds) { return $seconds / seconds_in_week;        }
	public static function seconds_to_months      ($seconds) { return $seconds / seconds_in_month;       }
	public static function seconds_to_months_28   ($seconds) { return $seconds / seconds_in_month_28;    }
	public static function seconds_to_months_29   ($seconds) { return $seconds / seconds_in_month_29;    }
	public static function seconds_to_months_30   ($seconds) { return $seconds / seconds_in_month_30;    }
	public static function seconds_to_months_31   ($seconds) { return $seconds / seconds_in_month_31;    }
	public static function seconds_to_years       ($seconds) { return $seconds / seconds_in_year;        }
	public static function seconds_to_years_leap  ($seconds) { return $seconds / seconds_in_year_leap;   }

	public static function bytes_to_kilobytes($bytes) { return $bytes / bytes_in_kilobyte; }
	public static function bytes_to_megabytes($bytes) { return $bytes / bytes_in_megabyte; }
	public static function bytes_to_gigabytes($bytes) { return $bytes / bytes_in_gigabyte; }


	public static function kilobytes_to_bytes($kilobytes) { return $kilobytes * bytes_in_kilobyte; }
}