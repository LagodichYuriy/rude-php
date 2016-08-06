<?

namespace rude;

class floating
{
	public static function rand($min = 0, $max = 1)
	{
		return $min + mt_rand() / mt_getrandmax() * ($max - $min);
	}

	public static function to_upper($float)
	{
		return floor($float);
	}

	public static function to_lower($float)
	{
		return ceil($float);
	}

	public static function round($number, $precision, $mode = PHP_ROUND_HALF_UP)
	{
		return round($number, $precision, $mode);
	}
}