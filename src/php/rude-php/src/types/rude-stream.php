<?

namespace rude;

/**
 * @category types
 */
class stream
{
	/**
	 * @en Return part of a stream
	 * @ru Возвращает часть потока
	 *
	 * @param string $stream
	 * @param int $length
	 * @param int $offset
	 *
	 * @return string
	 */
	public static function read($stream, $length, $offset = 0)
	{
		return substr($stream, $offset, $length);
	}

	/**
	 * @en Converts stream bytes into the array of numeric equivalents (or ASCII if you want so)
	 * @ru Возвращает дамп в виде массива числовых значений (также может подбирать ASCII)
	 *
	 * $stream = "ABC1234\0";
	 *
	 * $dump = stream::dump($stream);             # Array
	 *                                            # (
	 *                                            #     [0] => 65 # equals ASCII dec `A`
	 *                                            #     [1] => 66 # equals ASCII dec `B`
	 *                                            #     [2] => 67 # equals ASCII dec `C`
	 *                                            #     [3] => 49 # equals ASCII dec `1`
	 *                                            #     [4] => 50 # equals ASCII dec `2`
	 *                                            #     [5] => 51 # equals ASCII dec `3`
	 *                                            #     [6] => 52 # equals ASCII dec `4`
	 *                                            #     [7] => 0  # equals ASCII dec `\0`
	 *                                            # )
	 *
	 * $dump = stream::dump($stream, true);       # Array
	 *                                            # (
	 *                                            #     [0] => 41 # equals ASCII hex `A`
	 *                                            #     [1] => 42 # equals ASCII hex `B`
	 *                                            #     [2] => 43 # equals ASCII hex `C`
	 *                                            #     [3] => 31 # equals ASCII hex `1`
	 *                                            #     [4] => 32 # equals ASCII hex `2`
	 *                                            #     [5] => 33 # equals ASCII hex `3`
	 *                                            #     [6] => 34 # equals ASCII hex `4`
	 *                                            #     [7] => 0  # equals ASCII hex `\0`
	 *                                            # )
	 *
	 * $dump = stream::dump($stream, true, true); # Array
	 *                                            # (
	 *                                            #     [0] => A
	 *                                            #     [1] => B
	 *                                            #     [2] => C
	 *                                            #     [3] => 1
	 *                                            #     [4] => 2
	 *                                            #     [5] => 3
	 *                                            #     [6] => 4
	 *                                            #     [7] => 00
	 *                                            # )
	 *
	 * @param string $stream
	 * @param bool $hexdump
	 * @param bool $decode_printable
	 *
	 * @return array
	 */
	public static function dump($stream, $hexdump = false, $decode_printable = false)
	{
		$bytes = strings::bytes($stream);

		$result = null;

		foreach ($bytes as $byte)
		{
			if ($decode_printable !== false)
			{
				if (ctype_graph($byte))
				{
					$result[] = ($byte);

					continue;
				}
			}

			if ($hexdump !== false)
			{
				$result[] = bin2hex($byte);

				continue;
			}

			$result[] = ord($byte);
		}

		return $result;
	}

	/**
	 * @en Converts a string to int32 number
	 * @ru Преобразует строку в число структуры int32
	 *
	 * @param string $string
	 *
	 * @return int
	 */
	public static function to_int32($string)
	{
		return strings::unpack('l', $string); # `l` => signed long (always 32 bit, machine byte order) (4 bytes required)
	}

	/**
	 * @en Converts a string to int16 number
	 * @ru Преобразует строку в число структуры int16
	 *
	 * @param string $string
	 *
	 * @return int
	 */
	public static function to_int16($string)
	{
		return strings::unpack('s', $string); # `s` => signed short (always 16 bit, machine byte order) (2 bytes required)
	}

	/**
	 * @en Converts a string to int8 number
	 * @ru Преобразует строку в число структуры int8
	 *
	 * @param string $string
	 *
	 * @return int
	 */
	public static function to_int8($string)
	{
		return strings::unpack('c', $string); # `c` => signed char (1 byte required)
	}

	/**
	 * @en Converts a string to uint32 number
	 * @ru Преобразует строку в беззнаковое число структуры uint32
	 *
	 * @param string $string
	 *
	 * @return int
	 */
	public static function to_uint32($string)
	{
		return stream::to_int32($string) & 0xFFFFFFFF;
	}

	/**
	 * @en Converts a string to uint16 number
	 * @ru Преобразует строку в беззнаковое число структуры uint16
	 *
	 * @param string $string
	 *
	 * @return int
	 */
	public static function to_uint16($string)
	{
		return stream::to_int16($string) & 0xFFFF;
	}

	/**
	 * @en Converts a string to uint8 number
	 * @ru Преобразует строку в беззнаковое число структуры uint8
	 *
	 * @param string $string
	 *
	 * @return int
	 */
	public static function to_uint8($string)
	{
		return stream::to_int8($string) & 0xFF;
	}

	/**
	 * @en Converts a string to char (int8)
	 * @ru Преобразует строку в символ (int8)
	 *
	 * @param string $string
	 *
	 * @return int
	 */
	public static function to_char($string)
	{
		return stream::to_int8($string); # int8 <=> char (1 byte required)
	}

	/**
	 * @en Converts a string to uchar (uint8)
	 * @ru Преобразует строку в беззнаковый символ (uint8)
	 *
	 * @param string $string
	 *
	 * @return int
	 */
	public static function to_uchar($string)
	{
		return stream::to_uint8($string); # uint8 <=> uchar (1 byte required)
	}
}