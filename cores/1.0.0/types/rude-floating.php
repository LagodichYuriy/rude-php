<?

namespace rude;

class floating
{
	public static function rand($min = 0, $max = 1, $precision = 2, $mode = PHP_ROUND_HALF_UP)
	{
		$rand = $min + mt_rand() / mt_getrandmax() * ($max - $min);

		return static::round($rand, $precision, $mode);
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