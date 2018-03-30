<?

namespace rude;

class guid
{
	public static function to_bin($string)
	{
		return hex2bin(static::to_hex($string));
	}

	public static function to_hex($string)
	{
		return str_replace('-', '', $string);
	}
}