<?

namespace rude;

class ascii
{
	public static function is_printable_index($index)
	{
		if ($index >= 32 and $index <= 126)
		{
			return true;
		}

		return false;
	}

	public static function is_printable_char($char)
	{
		$index = static::char_to_index($char);

		return static::is_printable_index($index);
	}

	public static function is_printable_hex($hex)
	{
		$index = static::hex_to_index($hex);

		return static::is_printable_index($index);
	}

	public static function index_to_char($number)
	{
		return chr($number);
	}

	public static function index_to_hex($number)
	{
		return integer::to_hex($number);
	}

	public static function char_to_index($char)
	{
		return ord($char);
	}

	public static function char_to_hex($char)
	{
		return char::to_hex($char);
	}

	public static function hex_to_index($hex)
	{
		return hex::to_int($hex);
	}

	public static function hex_to_char($hex)
	{
		return hex::to_bin($hex);
	}
}