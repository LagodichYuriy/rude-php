<?

namespace rude;

/**
 * @category types
 */
class integer
{
	/**
	 * @en Returns random int value
	 * @ru Возвращает псевдослучайное целочисленное значение
	 *
	 * @param int $min Minimum limit of the random variable
	 * @param int $max Maximum limit of the random variable
	 *
	 * @return int A random integer value between min and max
	 */
	public static function rand($min, $max)
	{
		return mt_rand($min, $max);
	}

	public static function rand_unique($min, $max, $count)
	{
		$result = [];

		for ($i = 0; $i < $count; $i++)
		{
			$result[] = mt_rand($min, $max);
		}

		$result = array_unique($result);

		while (count($result) < $count)
		{
			$number = mt_rand($min, $max);

			if (!in_array($number, $result))
			{
				$result[] = $number;
			}
		}

		return $result;
	}

	/**
	 * @en Check if number is odd
	 * @ru Проверяет, является ли число нечётным
	 *
	 * @param int $int Any integer
	 *
	 * $is_odd = int::is_odd(1); # bool(true)
	 * $is_odd = int::is_odd(2); # bool(false)
	 * $is_odd = int::is_odd(3); # bool(true)
	 * $is_odd = int::is_odd(4); # bool(false)
	 * $is_odd = int::is_odd(5); # bool(true)
	 * $is_odd = int::is_odd(6); # bool(false)
	 *
	 * @return bool
	 */
	public static function is_odd($int)
	{
		return (bool) ($int & 1);
	}

	/**
	 * @en Check if number is even
	 * @ru Проверяет, является ли число чётным
	 *
	 * @param int $int Any integer
	 *
	 * $is_even = int::is_even(1); # bool(false)
	 * $is_even = int::is_even(2); # bool(true)
	 * $is_even = int::is_even(3); # bool(false)
	 * $is_even = int::is_even(4); # bool(true)
	 * $is_even = int::is_even(5); # bool(false)
	 * $is_even = int::is_even(6); # bool(true)
	 *
	 * @return bool
	 */
	public static function is_even($int)
	{
		return (bool) (~$int & 1);
	}

	/**
	 * @en Decimal to binary converter
	 * @ru Конверцация целочисленного значения в представление системы счисления по основанию 2
	 *
	 * @param int $int Any integer
	 *
	 * @return string Binary representation
	 */
	public static function to_bin($int)
	{
		return decbin($int);
	}

	/**
	 * @en Decimal to hexadecimal converter
	 * @ru Конверцация целочисленного значения в представление системы счисления по основанию 16
	 *
	 * @param int $int Any integer
	 *
	 * @return string Hexadecimal representation
	 */
	public static function to_hex($int)
	{
		return dechex($int);
	}

	public static function in_range($number, $from, $to, $include_borders = true)
	{
		if ($include_borders)
		{
			if ($from <= $number and $number <= $to)
			{
				return true;
			}

			return false;
		}

		if ($from < $number and $number < $to)
		{
			return true;
		}

		return false;
	}
}