<?

namespace rude;

class hex
{
	public static function to_int($hex)
	{
		return hexdec($hex);
	}

	public static function to_bin($hex)
	{
		return hex2bin($hex);
	}

	public static function is_printable($hex)
	{
		$int = static::to_int($hex);

		return ascii::is_printable_hex($int);
	}
}