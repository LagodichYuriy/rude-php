<?

namespace rude;

class measures
{
	public static function bytes_to_kilobytes($bytes, $precision = 2)
	{
		return round(($bytes / 1024), $precision);
	}

	public static function bytes_to_megabytes($bytes, $precision = 2)
	{
		return round(($bytes / 1024 / 1024), $precision);
	}
}