<?

namespace rude;

/**
 * @category types
 */
class char
{
	public static function nth($string, $n)
	{
		if ($n < 1 or $n > static::count($string))
		{
			return null;
		}

		return strings::read($string, 1, $n - 1);
	}

	public static function count($string)
	{
		return strings::length($string);
	}

	public static function rand($string, $n = 1)
	{
		$result = '';


		$length = strings::length($string);

		for ($i = 0; $i < $n; $i++)
		{
			$result .= static::nth($string, integer::rand(1, $length));
		}

		return $result;
	}

	/**
	 * @en Get the first character of a string
	 * @ru Получение первого символа строки
	 *
	 * $char = char::first('Hello'); # string(1) "H"
	 *
	 * @param string $string Any string
	 * @param int    $length
	 *
	 * @return string mixed
	 */
	public static function first($string, $length = 1)
	{
		if (strings::is_utf8($string))
		{
			return mb_substr($string, 0, $length, RUDE_STRING_ENCODING);
		}

		return substr($string, 0, $length);
	}

	/**
	 * @en Get the last one character of a string
	 * @ru Получение последнего символа строки
	 *
	 * $char = char::last('Hello'); # string(1) "o"
	 *
	 * @param string $string Any string
	 * @param int    $length
	 *
	 * @return string mixed
	 */
	public static function last($string, $length = 1)
	{
		if (strings::is_utf8($string))
		{
			return mb_substr($string, -$length, $length, RUDE_STRING_ENCODING);
		}

		return substr($string, -$length, $length);
	}

	public static function swap($string, $a, $b)
	{
		$chars = strings::chars($string);

		$chars = items::swap($chars, $a, $b);

		return implode('', $chars);
	}

	/**
	 * @en Remove first character(s) from string
	 * @ru Удаление первого (первых) символа строки
	 *
	 * @param string $string Any string
	 * @param int    $count
	 *
	 * @return string
	 */
	public static function remove_first($string, $count = 1)
	{
		if (strings::is_utf8($string))
		{
			return mb_substr($string, $count, strings::length($string) - $count, RUDE_STRING_ENCODING);
		}

		return substr($string, $count);
	}

	/**
	 * @en Remove last character(s) from string
	 * @ru Удаление последнего (последних) символа строки
	 *
	 * $string = char::remove_last('Hello'); # string(4) "Hell"
	 *
	 * @param string $string Any string
	 * @param int    $count
	 *
	 * @return string
	 */
	public static function remove_last($string, $count = 1)
	{
		if (strings::is_utf8($string))
		{
			return mb_substr($string, 0, -$count, RUDE_STRING_ENCODING);
		}

		return substr($string, 0, -$count);
	}

	/**
	 * @en Count unique chars in the string
	 * @ru Возвращает количество уникальных букв в строке
	 *
	 * @param string $string Any string
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $count = char::count_unique($string_ASCII); # int(17)
	 * $count = char::count_unique($string_UTF8);  # int(21)
	 *
	 * @return int
	 */
	public static function count_unique($string)
	{
		return count(static::unique($string));
	}

	public static function unique($string)
	{
		$chars = strings::chars($string);

		$uniqie = array_unique($chars);

		return array_values($uniqie);
	}

	/**
	 * @en Get characters frequency
	 * @ru Возвращает именованный массив с указанием частоты повторения каждого символа в строке
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = char::frequency($string_ASCII); # Array
	 *                                           # (
	 *                                           #     [A] => 1
	 *                                           #     [S] => 1
	 *                                           #     [C] => 1
	 *                                           #     [I] => 2
	 *                                           #     [ ] => 2
	 *                                           #     [s] => 1
	 *                                           #     [t] => 1
	 *                                           #     [r] => 1
	 *                                           #     [i] => 1
	 *                                           #     [n] => 1
	 *                                           #     [g] => 1
	 *                                           #     [e] => 2
	 *                                           #     [x] => 1
	 *                                           #     [a] => 1
	 *                                           #     [m] => 1
	 *                                           #     [p] => 1
	 *                                           #     [l] => 1
	 *                                           # )
	 *
	 * $result = char::frequency($string_UTF8);  # Array
	 *                                           # (
	 *                                           #     [U] => 1
	 *                                           #     [T] => 1
	 *                                           #     [F] => 1
	 *                                           #     [-] => 1
	 *                                           #     [8] => 1
	 *                                           #     [ ] => 2
	 *                                           #     [s] => 1
	 *                                           #     [t] => 1
	 *                                           #     [r] => 1
	 *                                           #     [i] => 1
	 *                                           #     [n] => 1
	 *                                           #     [g] => 1
	 *                                           #     [π] => 1
	 *                                           #     [ρ] => 1
	 *                                           #     [ά] => 1
	 *                                           #     [δ] => 1
	 *                                           #     [ε] => 1
	 *                                           #     [ι] => 1
	 *                                           #     [γ] => 1
	 *                                           #     [μ] => 1
	 *                                           #     [α] => 1
	 *                                           # )
	 *
	 * @param string $string Any string
	 *
	 * @return array
	 */
	public static function frequency($string)
	{
		if (strings::is_utf8($string))
		{
			$length = mb_strlen($string, RUDE_STRING_ENCODING);

			$unique = array();

			for ($i = 0; $i < $length; $i++)
			{
				$char = mb_substr($string, $i, 1, RUDE_STRING_ENCODING);

				if (!array_key_exists($char, $unique))
				{
					$unique[$char] = 0;
				}

				$unique[$char]++;
			}

			return $unique;
		}

		return count_chars($string, 0);
	}

	/**
	 * @en Сheck for zero-terminate character (or first character in the provided string)
	 * @ru Проверяет, является ли переданный символ нуль-терминатором
	 *
	 * $is_null = char::is_null("0");  # bool(false)
	 * $is_null = char::is_null("A");  # bool(false)
	 * $is_null = char::is_null("\n"); # bool(false)
	 * $is_null = char::is_null("\0"); # bool(true)
	 *
	 * @param string $char Any string
	 *
	 * @return bool
	 */
	public static function is_null($char)
	{
		if (char::first($char) === "\0")
		{
			return true;
		}

		return false;
	}

	/**
	 * @en Check if character (or first character in the provided string) is printable: letters, digit or blank
	 * @ru Проверяет переданный символ (или первый символ, в случае если была передана строка) отображаемым: буква, цифра или пробел
	 *
	 * $is_printable = char::is_printable("A");  # bool(true)
	 * $is_printable = char::is_printable("B");  # bool(true)
	 * $is_printable = char::is_printable(" ");  # bool(true)
	 * $is_printable = char::is_printable("\0"); # bool(false)
	 *
	 * @param string $char Any string
	 * @return bool
	 */
	public static function is_printable($char)
	{
		return ctype_print(char::first($char));
	}

	/**
	 * @en Detect charset of character
	 * @ru Определяет кодировку символа
	 *
	 * @param $char
	 * @return bool
	 */
	public static function is_UTF8($char)
	{
		return strings::is_utf8(char::first($char));
	}
}