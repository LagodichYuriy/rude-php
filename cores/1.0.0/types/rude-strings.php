<?

namespace rude;

if (!defined('RUDE_STRING_NEWLINE_WIN')) define('RUDE_STRING_NEWLINE_WIN', chr(0xD) . chr(0xA)); # 0xD 0xA <=> CL RF <=> \r\n (Windows)
if (!defined('RUDE_STRING_NEWLINE_LIN')) define('RUDE_STRING_NEWLINE_LIN',            chr(0xA)); #     0xA <=>    RF <=>   \n (Linux)
if (!defined('RUDE_STRING_NEWLINE_MAC')) define('RUDE_STRING_NEWLINE_MAC', chr(0xD)           ); # 0xD     <=> CL    <=> \r   (Macintosh)

/**
 * @category types
 */
class strings
{
	/**
	 * @en Random string generator
	 * @ru Генератор псевдослучайных строк
	 *
	 *
	 * # ASCII examples:
	 * $string = strings::rand();  # string(32) "9qlluBJdOlYrFAWhmBEswmSdXvAmUvOQ"
	 * $string = strings::rand(4); # string(4) "xgFn"
	 * $string = strings::rand(8); # string(8) "ojK0Zw96"
	 *
	 *
	 * # UTF-8 example:
	 * $string = strings::rand(8, 'παράδειγμα'); # string(16) "εαγρδρμδ"
	 *
	 * @param int $length Any length (32 by default)
	 * @param string $alphabet String alphabet
	 *
	 * @return string
	 */
	public static function rand($length = 32, $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		$result = '';

		$alphabet_size = static::length($alphabet);

		for ($i = 0; $i < $length; $i++)
		{
			$number = mt_rand(1, $alphabet_size);

			$result .= static::char($alphabet, $number);
		}

		return $result;
	}

	/**
	 * @en Find position of occurrence of a string
	 * @ru Получение позиции указанной подстроки в строке
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::find($string_ASCII, 'example');  # int(13)
	 * $result = strings::find($string_UTF8, 'πράδειγμα'); # int(13)
	 *
	 * @param string $string Any string
	 * @param string $substring Any substring
	 * @param int|null $offset
	 * @param bool|null $case_sensitive
	 *
	 * @return int|false
	 */
	public static function find($string, $substring, $offset = null, $case_sensitive = true)
	{
		if ($case_sensitive)
		{
			return mb_strpos($string, $substring, $offset);
		}

		return mb_stripos($string, $substring, $offset);
	}

	/**
	 * @en Find position of first occurrence of a string
	 * @ru Получение первой позиции указанной подстроки в строке
	 *
	 * @param           $string
	 * @param           $substring
	 * @param null      $offset
	 * @param bool|true $case_sensitive
	 *
	 * @return bool|int
	 */
	public static function find_last($string, $substring, $offset = null, $case_sensitive = true)
	{
		if ($case_sensitive)
		{
			return mb_strrpos($string, $substring, $offset);
		}

		return mb_strripos($string, $substring, $offset);
	}

	/**
	 * @en Find position of nth occurrence of a string
	 * @ru Получение произвольной позиции подстроки в строке
	 *
	 * @param      $string
	 * @param      $substring
	 * @param      $nth
	 * @param null $offset
	 * @param bool $case_sensitive
	 *
	 * @return bool|int
	 */
	public static function find_nth($string, $substring, $nth, $offset = null, $case_sensitive = true)
	{
		$position = static::find($string, $substring, $offset, $case_sensitive);

		if ($position === false)
		{
			return false;
		}

		$substring_length = static::length($substring);

		for ($i = 1; $i < $nth; $i++)
		{
			$position = static::find($string, $substring, $position + $substring_length, $case_sensitive);

			if ($position === false)
			{
				return false;
			}
		}

		return $position;
	}

	/**
	 * @en Find position of first occurrence of a string
	 * @ru Получение первой позиции указанной подстроки в строке
	 *
	 * @param           $string
	 * @param           $substring
	 * @param null      $offset
	 * @param bool|true $case_sensitive
	 *
	 * @return bool|int
	 */
	public static function find_first($string, $substring, $offset = null, $case_sensitive = true)
	{
		return static::find_nth($string, $substring, 1, $offset, $case_sensitive);
	}

	/**
	 * @en Find position of second occurrence of a string
	 * @ru Получение второй позиции указанной подстроки в строке
	 *
	 * @param           $string
	 * @param           $substring
	 * @param null      $offset
	 * @param bool|true $case_sensitive
	 *
	 * @return bool|int
	 */
	public static function find_second($string, $substring, $offset = null, $case_sensitive = true)
	{
		return static::find_nth($string, $substring, 2, $offset, $case_sensitive);
	}

	/**
	 * @en Find position of third occurrence of a string
	 * @ru Получение третьей позиции указанной подстроки в строке
	 *
	 * @param           $string
	 * @param           $substring
	 * @param null      $offset
	 * @param bool|true $case_sensitive
	 *
	 * @return bool|int
	 */
	public static function find_third($string, $substring, $offset = null, $case_sensitive = true)
	{
		return static::find_nth($string, $substring, 3, $offset, $case_sensitive);
	}

	/**
	 * @en Return the string's size (bytes)
	 * @ru Возвращает размер строки (байты)
	 *
	 * @param string $string
	 *
	 * @return int
	 */
	public static function size($string)
	{
		# strlen cannot be trusted anymore because of mbstring.func_overload

		return mb_strlen($string, '8bit');
	}

	/**
	 * @en Return the string's length (chars)
	 * @ru Возвращает длину строки (символы)
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::length($string_ASCII); # int(20)
	 * $result = strings::length($string_UTF8);  # int(22)
	 *
	 * @param string $string
	 *
	 * @return int The length of the string in characters
	 */
	public static function length($string)
	{
		return mb_strlen($string);
	}

	/**
	 * @en Count the number of occurrences of a substring in a string
	 * @ru Считает количество подстрок в строке
	 *
	 * $string_ASCII = 'ASCII string example example';     # string(28) "ASCII string example example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα πράδειγμα'; # string(50) "UTF-8 string πράδειγμα πράδειγμα"
	 *
	 * $result = strings::count($string_ASCII, 'example');  # int(2)
	 * $result = strings::count($string_UTF8, 'πράδειγμα'); # int(2)
	 *
	 * @param string $string Any string
	 * @param string $substring Any substring
	 *
	 * @return int The number of substrings in the string
	 */
	public static function count($string, $substring)
	{
		return mb_substr_count($string, $substring);
	}

	/**
	 * @en Count words in the string
	 * @ru Возвращает количество слов в тексте
	 *
	 * @param string $string Any string
	 * @param string $charlist A list of additional characters which will be considered as 'word'
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $count = strings::count_words($string_ASCII); # int(3) # Array
	 *                                                       # (
	 *                                                       #     [0] => ASCII
	 *                                                       #     [1] => string
	 *                                                       #     [2] => example
	 *                                                       # )
	 *
	 *
	 * # if you want to use this method with UTF-8 strings - you must specify language alphabet for the correct results:
	 *
	 * $alphabet = '1234567890-ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαάβγδεζηθικλμνξοπρστυφχψω';
	 *
	 *
	 * $count = strings::count_words($string_UTF8, $alphabet); # int(3) # Array
	 *                                                                 # (
	 *                                                                 #     [0] => UTF-8
	 *                                                                 #     [1] => string
	 *                                                                 #     [2] => πράδειγμα
	 *                                                                 # )
	 *
	 * @return int The number of words in the text
	 */
	public static function count_words($string, $charlist = null)
	{
		return str_word_count(str_replace("\xC2\xAD", '', $string), 0, $charlist); # "\xC2\xAD" is a "SOFT HYPHEN" character
	}

	/**
	 * @en Count lines in the string
	 * @ru Считает строки в тексте
	 *
	 * @param string $string Any string
	 * @param string $delimiter Line delimiter
	 *
	 * $count = strings::count_lines("Text\nwith\nnewlines!"); # int(3)
	 *
	 * @return int The number of lines in the text
	 */
	public static function count_lines($string, $delimiter = PHP_EOL)
	{
		return static::count($string, $delimiter) + 1;
	}

	/**
	 * @en Get excerpt from string
	 * @ru Получение отрывка текста из строки
	 *
	 * @param        $string
	 * @param        $max_length
	 * @param string $ending
	 *
	 * @return string
	 */
	public static function excerpt($string, $max_length, $ending = '...')
	{
		$max_length = $max_length - static::length($ending);

		if (static::length($string) <= $max_length)
		{
			return $string;
		}

		return static::read($string, $max_length) . $ending;
	}

	/**
	 * @en Replace occurrences of the search string with the replacement string
	 * @ru Заменяет найденные вхождения подстроки в строке
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::replace($string_ASCII, 'example', 'πράδειγμα'); # string(31) "ASCII string πράδειγμα"
	 * $result = strings::replace($string_UTF8, 'πράδειγμα', 'example');  # string(20) "UTF-8 string example"
	 *
	 * @param string       $string         Any string
	 * @param string|array $substring      Any string or array of strings
	 * @param string|array $replace        Any string or array of strings
	 * @param bool         $case_sensetive Case-sensitive/insensitive flag
	 *
	 * @return string
	 */
	public static function replace($string, $substring, $replace, $case_sensetive = true)
	{
		if (is_array($substring))
		{
			foreach ($substring as $item)
			{
				$string = static::replace($string, $item, $replace, $case_sensetive);
			}

			return $string;
		}


		if ($case_sensetive)
		{
			return str_replace($substring, $replace, $string);
		}

		return str_ireplace($substring, $replace, $string);
	}

	/**
	 * @en Replace first occurrence of string
	 * @ru Заменяет первое найденное вхождение подстроки в строке
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::replace_first($string_ASCII, ' ', 'aaa'); # string(22) "ASCIIaaastring example"
	 * $result = strings::replace_first($string_UTF8, ' ', 'άάά');  # string(36) "UTF-8άάάstring πράδειγμα"
	 *
	 * @param string $string    Any string
	 * @param string $substring Any substring
	 * @param string $replace   Any string
	 * @param bool   $case_sensetive
	 * @param null   $offset
	 *
	 * @return string Replaced string
	 */
	public static function replace_first($string, $substring, $replace, $case_sensetive = true, $offset = null)
	{
		if ($substring === '')
		{
			return $string;
		}

		$pos = static::find($string, $substring, $offset, $case_sensetive);

		if ($pos !== false)
		{
			$string = substr_replace($string, $replace, $pos, static::size($substring));
		}

		return $string;
	}

	/**
	 * @en Replace last occurrence of string
	 * @ru Заменяет последнее найденное вхождение подстроки в строке
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::replace_last($string_ASCII, ' ', 'aaa'); # string(22) "ASCII stringaaaexample"
	 * $result = strings::replace_last($string_UTF8, ' ', 'άάά');  # string(36) "UTF-8 stringάάάπράδειγμα"
	 *
	 * @param string $string    Any string
	 * @param string $substring Any substring
	 * @param string $replace   Any string
	 * @param bool   $case_sensetive
	 * @param null   $offset
	 *
	 * @return string
	 */
	public static function replace_last($string, $substring, $replace, $case_sensetive = true, $offset = null)
	{
		if ($substring === '')
		{
			return $string;
		}


		$pos = static::find_last($string, $substring, $offset, $case_sensetive);

		if ($pos !== false)
		{
			$string = substr_replace($string, $replace, $pos, static::size($substring));
		}

		return $string;
	}

	/**
	 * @en Replace occurrence of string between two markers
	 * @ru Заменяет найденное вхождение подстроки в строке между двумя указанными метками в тексте
	 *
	 * @param      $string
	 * @param      $substring_one
	 * @param      $substring_two
	 * @param      $replace
	 * @param bool $include_first_border
	 * @param bool $include_second_border
	 * @param null $offset
	 *
	 * @return string
	 */
	public static function replace_between($string, $substring_one, $substring_two, $replace, $include_first_border = false, $include_second_border = false, $offset = null)
	{
		$substring = static::read_between($string, $substring_one, $substring_two, $include_first_border, $include_second_border, $offset);

		return static::replace($string, $substring, $replace);
	}

	/**
	 * @en Remove last occurrence of string
	 * @ru Удаляет найденное вхождение подстроки в строке
	 *
	 * @param      $string
	 * @param      $substring
	 * @param bool $case_sensetive
	 *
	 * @return string
	 */
	public static function remove($string, $substring, $case_sensetive = true)
	{
		return static::replace($string, $substring, '', $case_sensetive);
	}

	/**
	 * @en Remove first occurrence of string
	 * @ru Удаляет первое найденное вхождение подстроки в строке
	 *
	 * @param      $string
	 * @param      $substring
	 * @param bool $case_sensetive
	 *
	 * @return string
	 */
	public static function remove_first($string, $substring, $case_sensetive = true)
	{
		return static::replace_first($string, $substring, '', $case_sensetive);
	}

	/**
	 * @en Remove first occurrence of string
	 * @ru Удаляет последнее найденное вхождение подстроки в строке
	 *
	 * @param      $string
	 * @param      $substring
	 * @param bool $case_sensetive
	 *
	 * @return string
	 */
	public static function remove_last($string, $substring, $case_sensetive = true)
	{
		return static::replace_last($string, $substring, '', $case_sensetive);
	}

	/**
	 * @en Remove empty lines in a string
	 * @ru Удаляет пустые строки в переданном тексте
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public static function remove_empty_lines($string)
	{
		$lines = static::lines($string);

		foreach ($lines as $index => $line)
		{
			if (!static::trim($line))
			{
				unset ($lines[$index]);
			}
		}

		return items::implode($lines, PHP_EOL);
	}

	/**
	 * @en Return part of a string with a specific length in characters
	 * @ru Возвращает часть строки с указанной длиной и смещением
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::read($string_ASCII, 5, 13); # string(5) "examp"
	 * $result = strings::read($string_UTF8, 5, 13);  # string(10) "πράδε"
	 *
	 * @param string $string Any string
	 * @param int $length Substring length to read
	 * @param int $offset String offset
	 *
	 * @return string
	 */
	public static function read($string, $length = null, $offset = 0)
	{
		return mb_substr($string, $offset, $length);
	}

	/**
	 * @en Get the substring after a specific substring
	 * @ru Получение части строки, которая следует за указанной подстрокой
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::read_after($string_ASCII, 'string '); # string(7) "example"
	 * $result = strings::read_after($string_UTF8, 'string ');  # string(18) "πράδειγμα"
	 *
	 * @param string $string Any string
	 * @param string $substring Any substring
	 *
	 * @return string|null
	 */
	public static function read_after($string, $substring)
	{
		if ($substring === '')
		{
			return '';
		}

		$pos = static::find($string, $substring);

		if ($pos !== false)
		{
			return static::read($string, static::length($string) - $pos, $pos + static::length($substring));
		}

		return '';
	}

	/**
	 * @en Get the substring which starts with a specific substring
	 * @ru Получение части строки, которая начинается с указанной подстроки
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::read_from($string_ASCII, 'string'); # string(14) "string example"
	 * $result = strings::read_from($string_UTF8, 'string');  # string(25) "string πράδειγμα"
	 *
	 * @param string $string Any string
	 * @param string $substring Any substring
	 *
	 * @return string
	 */
	public static function read_from($string, $substring)
	{
		$pos = static::find($string, $substring);

		if ($pos !== false)
		{
			return static::read($string, static::length($string) - $pos, $pos);
		}

		return null;
	}

	/**
	 * @en Get the substring which ends with a specific substring
	 * @ru Читает строку до указанной подстроки
	 *
	 * @param      $string
	 * @param      $substring
	 * @param bool $exclude_substring
	 *
	 * @return null|string
	 */
	public static function read_until($string, $substring, $exclude_substring = true)
	{
		$pos = static::find($string, $substring);

		if ($pos !== false)
		{
			if ($exclude_substring)
			{
				return static::read($string, $pos);
			}

			return static::read($string, $pos + static::length($substring));
		}

		return null;
	}

	/**
	 * @en Get the substring which is located between two specific substrings
	 * @ru Получение части строки, которая расположена между двумя указанными подстроками
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::read_between($string_ASCII, 'ex', 'le'); # string(3) "amp"
	 * $result = strings::read_between($string_UTF8, 'πρ', 'μα');  # string(10) "άδειγ"
	 *
	 * $result = strings::read_between($string_ASCII, 'ex', 'le', true); # string(7) "example"
	 * $result = strings::read_between($string_UTF8, 'πρ', 'μα', true);  # string(18) "πράδειγμα"
	 *
	 * @param string $string
	 * @param string $substring_one
	 * @param string $substring_two
	 * @param bool   $include_first_border
	 * @param bool   $include_second_border
	 * @param null   $offset
	 *
	 * @return string
	 */
	public static function read_between($string, $substring_one, $substring_two, $include_first_border = false, $include_second_border = false, $offset = null)
	{
		$pos_one = static::find($string, $substring_one, $offset);

		if ($pos_one === false)
		{
			return null;
		}

		$pos_two = static::find($string, $substring_two, $pos_one + 1);

		if ($pos_two === false)
		{
			return null;
		}

		$size_one = static::length($substring_one);

		$substring = static::read($string, $pos_two - $pos_one - $size_one, $pos_one + $size_one);

		if ($include_first_border  !== false) { $substring = $substring_one . $substring; }
		if ($include_second_border !== false) { $substring = $substring . $substring_two; }

		return $substring;
	}

	/**
	 * @en Reverse the string
	 * @ru Переворачивает строку задом-наперёд
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public static function reverse($string)
	{
		$chars = static::chars($string);

		$chars = array_reverse($chars);

		return implode($chars);
	}

	/**
	 * @en Strip whitespace characters from the beginning and end of a string
	 * @ru Удаляет нежелательные символы с обеих стороны строки
	 *
	 * @param              $string
	 * @param array|string $trim
	 *
	 * @return string
	 */
	public static function trim($string, $trim = [" ", "\t", "\n", "\r", "\0", "\x0B"])
	{
		$string = static::trim_left($string, $trim);
		$string = static::trim_right($string, $trim);

		return $string;
	}

	/**
	 * @en Strip whitespace characters from the beginning of a string
	 * @ru Удаляет нежелательные символы из начала строки
	 *
	 * @param              $string
	 * @param array|string $trim
	 *
	 * @return string
	 */
	public static function trim_left($string, $trim = [" ", "\t", "\n", "\r", "\0", "\x0B"])
	{
		if (is_string($trim))
		{
			$trim = static::chars($trim);
		}

		$trim = array_flip($trim);


		for ($string_length = static::length($string), $trim_length = 0, $i = 1; $i <= $string_length; $i++)
		{
			$char = char::nth($string, $i);

			     if (isset($trim[$char])) { $trim_length++; }
			else                          { break;          }
		}

		if ($trim_length)
		{
			return char::remove_first($string, $trim_length);
		}

		return $string;
	}

	/**
	 * @en Strip whitespace characters from the end of a string
	 * @ru Удаляет нежелательные символы с конца строки
	 *
	 * @param              $string
	 * @param array|string $trim
	 *
	 * @return string
	 */
	public static function trim_right($string, $trim = [" ", "\t", "\n", "\r", "\0", "\x0B"])
	{
		if (is_string($trim))
		{
			$trim = static::chars($trim);
		}

		$trim = array_flip($trim);


		for ($string_length = static::length($string), $trim_length = 0, $i = $string_length; $i > 0; $i--)
		{
			$char = char::nth($string, $i);

			     if (isset($trim[$char])) { $trim_length++; }
			else                          { break;          }
		}

		if ($trim_length)
		{
			return char::remove_last($string, $trim_length);
		}

		return $string;
	}


	/**
	 * @en Split a string by substring
	 * @ru Разбивает строку с помощью подстрок
	 *
	 * $result = strings::split("First line\nSecond line\nThird line"); # Array
	 *                                                                  # (
	 *                                                                  #     [0] => First line
	 *                                                                  #     [1] => Second line
	 *                                                                  #     [2] => Third line
	 *                                                                  # )
	 *
	 * $result = strings::split('String Array Object', ' '); # Array
	 *                                                       # (
	 *                                                       #     [0] => String
	 *                                                       #     [1] => Array
	 *                                                       #     [2] => Object
	 *                                                       # )
	 *
	 * $result = strings::split('baby,son,mom,dad', ','); # Array
	 *                                                    # (
	 *                                                    #     [0] => baby
	 *                                                    #     [1] => son
	 *                                                    #     [2] => mom
	 *                                                    #     [3] => dad
	 *                                                    # )
	 *
	 * @param string $string Any string
	 * @param string $delimiter String delimiter (newline by default)
	 * @param int $limit Limit elements
	 *
	 * @return array
	 */
	public static function explode($string, $delimiter = PHP_EOL, $limit = null)
	{
		if ($limit === null)
		{
			return explode($delimiter, $string);
		}

		return explode($delimiter, $string, $limit);
	}

	/**
	 * @en Join array elements with a string
	 * @ru Сливает массив элементов в строку с использованием указанного разделителя
	 *
	 * @param        $string
	 * @param string $glue
	 *
	 * @return string
	 */
	public static function implode($string, $glue = ', ')
	{
		return implode($glue, $string);
	}

	/**
	 * @en Check if a string contains the specified substing
	 * @ru Проверяет наличие подстроки в строке
	 *
	 * $string = 'Shit happens';
	 * $substring = 'hit';
	 *
	 * $is_contains = strings::contains($string, $substring); # bool(true)
	 *
	 * @param string $string Any string
	 * @param string|array $substring Any substring or array of substrings
	 * @param bool $case_sensitive `true` for case sensitive search and `false` otherwise
	 * @param null|int $offset offset
	 * @return bool
	 */
	public static function contains($string, $substring, $case_sensitive = true, $offset = null)
	{
		if (is_array($substring))
		{
			foreach ($substring as $item)
			{
				if (static::contains($string, $item, $case_sensitive, $offset))
				{
					return true;
				}
			}

			return false;
		}

		return static::find($string, $substring, $offset, $case_sensitive) !== false;
	}

	public static function contains_any($string, $substrings)
	{
		foreach ($substrings as $substring)
		{
			if (static::contains($string, $substring))
			{
				return true;
			}
		}

		return false;
	}

	public static function contains_all($string, $substrings)
	{
		foreach ($substrings as $substring)
		{
			if (!static::contains($string, $substring))
			{
				return false;
			}
		}

		return true;
	}

	public static function contains_only($string, $chars)
	{
		$chars = array_flip($chars);

		foreach (static::chars($string) as $char)
		{
			if (!isset($chars[$char]))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * @en Check if a string starts with the specified character/string or not
	 * @ru Проверяет, начинается ли строка с указанной подстроки
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::starts_with($string_ASCII, 'example');  # bool(false)
	 * $result = strings::starts_with($string_ASCII, 'ASCII');    # bool(true)
	 *
	 * $result = strings::starts_with($string_UTF8, 'πράδειγμα'); # bool(false)
	 * $result = strings::starts_with($string_UTF8, RUDE_STRING_ENCODING);     # bool(true)
	 *
	 * @param string $string    Any string
	 * @param mixed  $substring Any substring or array of substrings
	 * @param bool   $case_sensitive
	 *
	 * @return bool
	 */
	public static function starts_with($string, $substring, $case_sensitive = true)
	{
		if (is_array($substring))
		{
			foreach ($substring as $sub)
			{
				if (static::starts_with($string, $sub))
				{
					return true;
				}
			}

			return false;
		}

		if ($case_sensitive !== true)
		{
			$string    = static::to_lowercase($string);
			$substring = static::to_lowercase($substring);
		}

		return substr($string, 0, strlen($substring)) === $substring;
	}

	/**
	 * @en Check if a string ends with the specified character/string or not
	 * @ru Проверяет, заканчивается ли строка указанной подстрокой
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::ends_with($string_ASCII, 'example');  # bool(true)
	 * $result = strings::ends_with($string_ASCII, 'ASCII');    # bool(false)
	 *
	 * $result = strings::ends_with($string_UTF8, 'πράδειγμα'); # bool(true)
	 *
	 * @param string $string    Any string
	 * @param mixed  $substring Any substring or array of substrings
	 *
	 * @param bool   $case_sensitive
	 *
	 * @return bool
	 */
	public static function ends_with($string, $substring, $case_sensitive = true)
	{
		if (is_array($substring))
		{
			foreach ($substring as $sub)
			{
				if (static::ends_with($string, $sub))
				{
					return true;
				}
			}

			return false;
		}


		$length = strlen($substring);

		if ($length == 0)
		{
			return true;
		}


		if ($case_sensitive !== true)
		{
			$string    = static::to_lowercase($string);
			$substring = static::to_lowercase($substring);
		}

		return substr($string, -$length) === $substring;
	}

	/**
	 * @en Erase chars in the string after the specific position
	 * @ru Удаляет символы в строке после определённой позиции
	 *
	 * @param string $string Any string
	 * @param int $offset
	 * @param int $length
	 *
	 * @return string
	 */
	public static function erase($string, $offset, $length = null)
	{
		if ($length === null)
		{
			# string: `hello`
			# offset: 2
			# length: null
			# result: `he`

			return substr($string, 0, $offset);
		}

		if ($offset == 0)
		{
			# string: `hello`
			# offset: 0
			# length: 2
			# result: `llo`

			return static::read($string, static::length($string) - $length, $length);
		}


		# string: `hello`
		# offset: 2
		# length: 2
		# result: `heo`

		return static::read($string, $offset) . substr($string, $offset + $length);
	}

	/**
	 * @en Insert string at specified position
	 * @ru Вставка подстроки в определённую позицию в строке
	 *
	 * $string = strings::insert('AAAA BBBB CCCC', 'DDDD', 5); # string(18) "AAAA DDDDBBBB CCCC"
	 *
	 * @param string $string Any string
	 * @param string $substring Any substring
	 * @param int $offset Substring offset for insert
	 *
	 * @return string
	 */
	public static function insert($string, $substring, $offset)
	{
		if ($offset == 0)
		{
			return $string . $substring;
		}

		return substr($string, 0, $offset) . $substring . substr($string, $offset);
	}

	/**
	 * @en Generating all permutations of a given string
	 * @ru Строковая комбинаторика. Получение всех возможны вариантов перестановок элементов строки
	 *
	 *
	 * $result = strings::permutation('AAA BBB CCC DDD'); # Array
	 *                                                   # (
	 *                                                   #     [0] => AAA BBB CCC DDD
	 *                                                   #     [1] => AAA BBB DDD CCC
	 *                                                   #     [2] => AAA CCC DDD BBB
	 *                                                   #     [3] => AAA CCC BBB DDD
	 *                                                   #     [4] => AAA DDD BBB CCC
	 *                                                   #     [5] => AAA DDD CCC BBB
	 *                                                   #     [6] => BBB CCC DDD AAA
	 *                                                   #     [7] => BBB CCC AAA DDD
	 *                                                   #     [8] => BBB DDD AAA CCC
	 *                                                   #     [9] => BBB DDD CCC AAA
	 *                                                   #     [10] => BBB AAA CCC DDD
	 *                                                   #     [11] => BBB AAA DDD CCC
	 *                                                   #     [12] => CCC DDD AAA BBB
	 *                                                   #     [13] => CCC DDD BBB AAA
	 *                                                   #     [14] => CCC AAA BBB DDD
	 *                                                   #     [15] => CCC AAA DDD BBB
	 *                                                   #     [16] => CCC BBB DDD AAA
	 *                                                   #     [17] => CCC BBB AAA DDD
	 *                                                   #     [18] => DDD AAA BBB CCC
	 *                                                   #     [19] => DDD AAA CCC BBB
	 *                                                   #     [20] => DDD BBB CCC AAA
	 *                                                   #     [21] => DDD BBB AAA CCC
	 *                                                   #     [22] => DDD CCC AAA BBB
	 *                                                   #     [23] => DDD CCC BBB AAA
	 *                                                   # )
	 *
	 *
	 * @param string $string Any string
	 * @param string $delimiter
	 *
	 * @return array
	 */
	public static function permutation($string, $delimiter = ' ')
	{
		$array = items::permutation(explode($delimiter, $string));


		$result = null;

		foreach ($array as $item)
		{
			$result[] = implode(' ', $item);
		}

		return $result;
	}

	/**
	 * @en Returns the first line from the string
	 * @ru Возвращает первую строку из текста
	 *
	 * $string = "Hello\nHi\nWow!\n12345";
	 *
	 * $line = strings::first($string); # string(5) "Hello"
	 *
	 * @param string $string Any string
	 * @param string $delimiter String delimiter
	 *
	 * @return string
	 */
	public static function first($string, $delimiter = PHP_EOL)
	{
		return static::line($string, 1, $delimiter);
	}

	/**
	 * @en Returns the last line from the string
	 * @ru Возвращает последнюю строку из текста
	 *
	 * $string = "Hello\nHi\nWow!\n12345";
	 *
	 * $line = strings::last($string); # string(5) "12345"
	 *
	 * @param string $string Any string
	 * @param string $delimiter String delimiter
	 *
	 * @return string
	 */
	public static function last($string, $delimiter = PHP_EOL)
	{
		$count = static::count_lines($string);

		return static::line($string, $count + 1, $delimiter);
	}

	/**
	 * @en Return specific character from the string
	 * @ru Возвращает указанный символ из строки
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $char = strings::char($string_ASCII, 14); # string(1) "e"
	 * $char = strings::char($string_ASCII, 15); # string(1) "x"
	 * $char = strings::char($string_ASCII, 16); # string(1) "a"
	 *
	 * $char = strings::char($string_UTF8, 14); # string(2) "π"
	 * $char = strings::char($string_UTF8, 15); # string(2) "ρ"
	 * $char = strings::char($string_UTF8, 16); # string(2) "ά"
	 *
	 * @param string $string Any string
	 * @param int $number Character number in the range from 1 to n (string length)
	 *
	 * @return string
	 */
	public static function char($string, $number)
	{
		return static::read($string, 1, $number - 1);
	}

	/**
	 * @en Convert a string to an array of chars
	 * @ru Преобразует строку в массив символов
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $chars = strings::chars($string_ASCII); # Array
	 *                                         # (
	 *                                         #     [0] => A
	 *                                         #     [1] => S
	 *                                         #     [2] => C
	 *                                         #     [3] => I
	 *                                         #     [4] => I
	 *                                         #     [5] =>
	 *                                         #     [6] => s
	 *                                         #     [7] => t
	 *                                         #     [8] => r
	 *                                         #     [9] => i
	 *                                         #     [10] => n
	 *                                         #     [11] => g
	 *                                         #     [12] =>
	 *                                         #     [13] => e
	 *                                         #     [14] => x
	 *                                         #     [15] => a
	 *                                         #     [16] => m
	 *                                         #     [17] => p
	 *                                         #     [18] => l
	 *                                         #     [19] => e
	 *                                         # )
	 *
	 * $chars = strings::chars($string_UTF8);  # Array
	 *                                         # (
	 *                                         #     [0] => U
	 *                                         #     [1] => T
	 *                                         #     [2] => F
	 *                                         #     [3] => -
	 *                                         #     [4] => 8
	 *                                         #     [5] =>
	 *                                         #     [6] => s
	 *                                         #     [7] => t
	 *                                         #     [8] => r
	 *                                         #     [9] => i
	 *                                         #     [10] => n
	 *                                         #     [11] => g
	 *                                         #     [12] =>
	 *                                         #     [13] => π
	 *                                         #     [14] => ρ
	 *                                         #     [15] => ά
	 *                                         #     [16] => δ
	 *                                         #     [17] => ε
	 *                                         #     [18] => ι
	 *                                         #     [19] => γ
	 *                                         #     [20] => μ
	 *                                         #     [21] => α
	 *                                         # )
	 *
	 * @param string $string Any string
	 *
	 * @return array
	 */
	public static function chars($string)
	{
		# "u (PCRE8) This modifier turns on additional functionality of PCRE that is incompatible with Perl.
		# Pattern strings are treated as UTF-8. This modifier is available from PHP 4.1.0 or greater on Unix
		# and from PHP 4.2.3 on win32. UTF-8 validity of the pattern is checked since PHP 4.3.5."

		return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
	}

	/**
	 * @en Returns byte representation of string
	 * @ru Разбивает строку на массив байт
	 *
	 * @param $string
	 *
	 * @return array
	 */
	public static function bytes($string)
	{
		return unpack('C*', $string);
	}

	/**
	 * @en Convert a string to an array
	 * @ru Разбивает строку на массив строк указанной длины
	 *
	 * @param     $string
	 * @param int $split_length
	 *
	 * @return array
	 */
	public static function split($string, $split_length = 0)
	{
		if ($split_length == 0)
		{
			return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
		}


		$result = [];

		$length = static::length($string);

		for ($i = 0; $i < $length; $i += $split_length)
		{
			$result[] = static::read($string, $split_length, $i);
		}

		return $result;
	}

	/**
	 * @en Returns the specified line from the string
	 * @ru Возвращает строку по указанному номеру в тексте
	 *
	 * $string = "Hello\nHi\nWow!\n12345";
	 *
	 * $line = strings::line($string, 3); # string(4) "Wow!"
	 *
	 * @param string $string Any string
	 * @param int $number Line number (in the range of 1 to n)
	 * @param string $delimiter Line delimiter
	 *
	 * @return string
	 */
	public static function line($string, $number, $delimiter = PHP_EOL)
	{
		$lines = explode($delimiter, $string);

		if (count($lines) < $number)
		{
			return null;
		}

		return $lines[$number - 1];
	}

	/**
	 * @en Return specific lines from the string (or all lines if range is not provided)
	 * @ru Возвращает определённые строки из строки (или все строки в виде массива, если диапазон не был указан при вызове)
	 *
	 * $string = "First line\nSecond line\nThird line\netc";
	 *
	 * $lines = strings::lines($string); # Array
	 *                                   # (
	 *                                   #     [0] => First line
	 *                                   #     [1] => Second line
	 *                                   #     [2] => Third line
	 *                                   #     [3] => etc
	 *                                   # )
	 *
	 * $lines = strings::lines($string, 2); # Array
	 *                                      # (
	 *                                      #     [0] => Second line
	 *                                      #     [1] => Third line
	 *                                      #     [2] => etc
	 *                                      # )
	 *
	 * $lines = strings::lines($string, 2, 3); # Array
	 *                                         # (
	 *                                         #     [0] => Second line
	 *                                         #     [1] => Third line
	 *                                         # )
	 *
	 *
	 * @param string $string Any string
	 * @param int $from
	 * @param int $to
	 * @param string $delimiter Line delimiter
	 *
	 * @return array
	 */
	public static function lines($string, $from = null, $to = null, $delimiter = PHP_EOL)
	{
		if ($from === null)
		{
			return explode($delimiter, $string);
		}

		if ($to === null)
		{
			$to = static::count($string, $delimiter) + 1;
		}

		if ($from < 1 or $from > $to)
		{
			return [];
		}


		$lines = explode($delimiter, $string);

		if (count($lines) < $to)
		{
			return [];
		}


		$result = [];

		for ($i = $from - 1; $i <= $to - 1; $i++)
		{
			$result[] = $lines[$i];
		}

		return $result;
	}

	/**
	 * @en Return specified word from the string
	 * @ru Возвращает слово из строки по его номеру в ней
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::word($string_ASCII, 1); # string(5) "ASCII"
	 * $result = strings::word($string_ASCII, 2); # string(6) "string"
	 * $result = strings::word($string_ASCII, 3); # string(7) "example"
	 *
	 *
	 * # if you want to use this method with UTF-8 strings - you must specify language alphabet for the correct results:
	 *
	 * $alphabet = '1234567890-ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαάβγδεζηθικλμνξοπρστυφχψω';
	 *
	 *
	 * $result = strings::word($string_UTF8, 1, $alphabet); # string(5) "UTF-8"
	 * $result = strings::word($string_UTF8, 2, $alphabet); # string(6) "string"
	 * $result = strings::word($string_UTF8, 3, $alphabet); # string(18) "πράδειγμα"
	 *
	 * @param string $string
	 * @param int $number
	 * @param string $charlist
	 *
	 * @return string
	 */
	public static function word($string, $number, $charlist = null)
	{
		return str_word_count($string, 1, $charlist)[$number - 1];
	}

	/**
	 * @en Return all words from the string
	 * @ru Возвращает все слова, которые были найдены в строке
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 *
	 * $result = strings::words($string_ASCII);  debug($result, true); # Array
	 *                                                                 # (
	 *                                                                 #     [0] => ASCII
	 *                                                                 #     [1] => string
	 *                                                                 #     [2] => example
	 *                                                                 # )
	 *
	 *
	 * # if you want to use this method with UTF-8 strings - you must specify language alphabet for the correct results:
	 *
	 * $alphabet = '1234567890-ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαάβγδεζηθικλμνξοπρστυφχψω';
	 *
	 *
	 * $result = strings::words($string_UTF8, $alphabet); # Array
	 *                                                    # (
	 *                                                    #     [0] => UTF-8
	 *                                                    #     [1] => string
	 *                                                    #     [2] => πράδειγμα
	 *                                                    # )
	 *
	 * @param string $string
	 * @param string $charlist
	 *
	 * @return array
	 */
	public static function words($string, $charlist = null)
	{
		return str_word_count($string, 1, $charlist);
	}

	public static function digits($string)
	{
		$string = filter_var($string, FILTER_SANITIZE_NUMBER_INT);

		if (static::starts_with($string, '+'))
		{
			$string = char::remove_first($string);
		}

		return $string;
	}

	/**
	 * @en Detect charset of string
	 * @ru Определяет кодировку строки
	 *
	 * $is_utf8 = strings::is_utf8('ABCDEFАБВГДЕ'); # bool(true)
	 *
	 * @param $string
	 * @return bool
	 */
	public static function is_utf8($string)
	{
		########################################################################################
		# 1. Any UTF8 string is a valid 8-bit encoding string (even if it produces gibberish); #
		# 2. On the other hand, most 8-bit encoded strings with extended (128+) characters are #
		#    not valid UTF8, but, as any other random byte sequence, they might happen to be;  #
		# 3. Of course, any ASCII text is valid UTF8;                                          #
		# 4. Native mb_detect_encoding() is slow.                                              #
		########################################################################################

		return preg_match('//u', $string);
	}

	/**
	 * @en Check if a string contains BOM signature
	 * @ru Проверяет, встречается ли в строке сигнатура BOM
	 *
	 * @param string $string Any string
	 *
	 * @return bool
	 */
	public static function is_bom($string)
	{
		if (substr($string, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf))
		{
			return true;
		}

		return false;
	}

	/**
	 * @en Check if string is serialized
	 * @ru Проверяет, возможно ли десериализировать переданную строку
	 *
	 * @param $string
	 *
	 * @return bool
	 */
	public static function is_serialized($string)
	{
		return @unserialize($string) !== false;
	}

	/**
	 * @en Check if a string is empty
	 * @ru Проверяет, пуста ли строка
	 *
	 * @param $string
	 *
	 * @return bool
	 *
	 */
	public static function is_empty($string)
	{
		return empty($string);
	}

	/**
	 * @en Check if a string is an email address
	 * @ru Проверяет, является ли строка почтовым адресом
	 *
	 * @param string $string Any string
	 *
	 * @return bool
	 */
	public static function is_email($string)
	{
		if (filter_var($string, FILTER_VALIDATE_EMAIL))
		{
			return true;
		}

		return false;
	}

	/**
	 * @en Check if a string is an IP address
	 * @ru Проверяет, является ли строка IP адресом
	 *
	 * @param string $string Any string
	 *
	 * @return bool
	 */
	public static function is_ip($string)
	{
		if (filter_var($string, FILTER_VALIDATE_IP))
		{
			return true;
		}

		return false;
	}

	public static function is_uppercase($string)
	{
		return $string === mb_strtoupper($string);
	}

	public static function is_lowercase($string)
	{
		return $string === mb_strtolower($string);
	}

	/**
	 * @en Check if a string is an URL address
	 * @ru Проверяет, является ли строка URL адресом
	 *
	 * @param string $string Any string
	 *
	 * @return bool
	 */
	public static function is_url($string)
	{
		if (filter_var($string, FILTER_VALIDATE_URL))
		{
			return true;
		}

		return false;
	}

	public static function parse_float($string)
	{
		return filter_var($string, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}

	public static function parse_int($string)
	{
		return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
	}

	public static function equals($string_one, $string_two, $case_sensitive = true)
	{
		if (!is_string($string_one)) { $string_one = (string) $string_one; }
		if (!is_string($string_two)) { $string_two = (string) $string_two; }

		if (!$case_sensitive)
		{
			$string_one = static::to_lowercase($string_one);
			$string_two = static::to_lowercase($string_two);
		}

		return $string_one === $string_two;
	}

	/**
	 * @en Unpack data from binary string
	 * @ru Распаковка данных из бинарной строки
	 *
	 * @param string $format
	 * @param string $data
	 *
	 * @return mixed
	 */
	public static function unpack($format, $data)
	{
		$array = unpack($format, $data); # use origin unpack function

		return reset($array); # get the first item from the array
	}

	/**
	 * @en Pad a string with another string
	 * @ru Заполняет строку подстрокой до указанной длины
	 *
	 * @param        $string
	 * @param        $pad_length
	 * @param string $pad_string
	 * @param int    $pad_direction
	 *
	 * @return string
	 */
	public static function pad($string, $pad_length, $pad_string = ' ', $pad_direction = STR_PAD_RIGHT)
	{
		return str_pad($string, $pad_length, $pad_string, $pad_direction);
	}

	/**
	 * @en Pad a string with another string to the left
	 * @ru Заполняет строку подстрокой до указанной длины слева
	 *
	 * @param        $string
	 * @param        $pad_length
	 * @param string $pad_string
	 *
	 * @return string
	 */
	public static function pad_left($string, $pad_length, $pad_string = ' ')
	{
		return static::pad($string, $pad_length, $pad_string, STR_PAD_LEFT);
	}

	/**
	 * @en Pad a string with another string to the right
	 * @ru Заполняет строку подстрокой до указанной длины справа
	 *
	 * @param        $string
	 * @param        $pad_length
	 * @param string $pad_string
	 *
	 * @return string
	 */
	public static function pad_right($string, $pad_length, $pad_string = ' ')
	{
		return static::pad($string, $pad_length, $pad_string, STR_PAD_RIGHT);
	}

	/**
	 * @en Pad a string with another string in both directions
	 * @ru Заполняет строку подстрокой до указанной длины с двух сторон
	 *
	 * @param        $string
	 * @param        $pad_length
	 * @param string $pad_string
	 *
	 * @return string
	 */
	public static function pad_both($string, $pad_length, $pad_string = ' ')
	{
		return static::pad($string, $pad_length, $pad_string, STR_PAD_BOTH);
	}

	/**
	 * @en Repeat a string
	 * @ru Повторяет строку указанное число раз
	 *
	 * @param $string
	 * @param $multiplier
	 *
	 * @return string
	 */
	public static function repeat($string, $multiplier)
	{
		return str_repeat($string, $multiplier);
	}

	/**
	 * @en Convert character encoding to UTF-8
	 * @ru Преобразует строку в UTF-8 кодировку
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function to_utf8($string)
	{
		return mb_convert_encoding($string, 'UTF-8', 'auto');
	}

	/**
	 * @en Make a string uppercase
	 * @ru Переводит строку в верхний регистр
	 *
	 * $string_ASCII = 'ASCII string example';   # string(20) "ASCII string example"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα'; # string(31) "UTF-8 string πράδειγμα"
	 *
	 * $result = strings::to_uppercase($string_ASCII); # string(20) "ASCII STRING EXAMPLE"
	 * $result = strings::to_uppercase($string_UTF8);  # string(31) "UTF-8 STRING ΠΡΆΔΕΙΓΜΑ"
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function to_uppercase($string)
	{
		return mb_strtoupper($string);
	}

	/**
	 * @en Make a string lowercase
	 * @ru Переводит строку в нижний регистр
	 *
	 * $string_ASCII = 'ASCII STRING EXAMPLE';   # string(20) "ASCII STRING EXAMPLE"
	 * $string_UTF8  = 'UTF-8 STRING ΠΡΆΔΕΙΓΜΑ'; # string(31) "UTF-8 STRING ΠΡΆΔΕΙΓΜΑ"
	 *
	 * $result = strings::to_lowercase($string_ASCII); # string(20) "ascii string example"
	 * $result = strings::to_lowercase($string_UTF8);  # string(31) "utf-8 string πράδειγμα"
	 *
	 * @param string $string Any string
	 *
	 * @return string
	 */
	public static function to_lowercase($string)
	{
		return mb_strtolower($string);
	}

	/**
	 * @en Make a string capitalcase
	 * @ru Переводит строку в регистр с заглавной буквой в самом начале
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public static function to_capitalcase($string)
	{
		$char_first = char::first($string);
		$char_rest  = static::read($string, static::length($string) - 1, 1);

		return static::to_uppercase($char_first) . static::to_lowercase($char_rest);
	}

	/**
	 * @en Make a string titlecase
	 * @ru Переводит строку в регистр с заглавной буквой в каждом слове
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public static function to_titlecase($string)
	{
		return mb_convert_case($string, MB_CASE_TITLE);
	}

	public static function to_snakecase($string)
	{
		$string = str_replace([' ', '__', '__'], '_', $string);
		$string = preg_replace('/(?!_)\p{P}/u', '', $string);

		$string_lowercase = static::to_lowercase($string);

		$chars_lowercase = static::chars($string_lowercase);


		$result = '';

		$chars = static::chars($string);

		foreach ($chars as $index => $char)
		{
			if ($char === '_')
			{
				$result .= $char;

				continue;
			}

			if ($char === $chars_lowercase[$index])
			{
				$result .= $char;


				if (isset($chars[$index + 1]))
				{
					$char_next = $chars[$index + 1];

					if ($char_next !== '_' and $chars_lowercase[$index + 1] !== $char_next)
					{
						$result .= '_';
					}
				}
			}
			else
			{
				$result .= $chars_lowercase[$index];
			}
		}

		return trim($result, '_');
	}

	/***
	 * @en Remove duplicates from the string
	 * @ru Удаляет дубликаты из строки
	 *
	 * $string_ASCII = 'ASCII ASCII string example string';       # string(33) "ASCII ASCII string example string"
	 * $string_UTF8  = 'UTF-8 string πράδειγμα string πράδειγμα'; # string(57) "UTF-8 string πράδειγμα string πράδειγμα"
	 *
	 * $result = strings::remove_duplicates($string_ASCII); # string(20) "ASCII string example"
	 * $result = strings::remove_duplicates($string_UTF8);  # string(31) "UTF-8 string πράδειγμα"
	 *
	 * @param string $string Any string
	 * @param string $delimiter String delimiter
	 *
	 * @return string
	 */
	public static function remove_duplicates($string, $delimiter = ' ')
	{
		return implode($delimiter, array_unique(explode($delimiter, $string)));
	}

	/**
	 * @en Remove numbers from the string
	 * @ru Убирает числа из строки
	 *
	 * @param string $string Any string
	 *
	 * @return string
	 */
	public static function remove_numbers($string)
	{
		return preg_replace('/[0-9]+/', '', $string);
	}

	/**
	 * @en Remove BOM from the string
	 * @ru Удаляет сигнатуру BOM из строки
	 *
	 * @param string $string Any string
	 *
	 * @return string
	 */
	public static function remove_bom($string)
	{
		if (substr($string, 0, 3) == pack('CCC', 0xef, 0xbb, 0xbf))
		{
			return substr($string, 3);
		}

		return $string;
	}
}
