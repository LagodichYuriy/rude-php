<?

namespace rude;

class mime
{
	public static function is_image($string)
	{
		return strings::starts_with($string, 'image/');
	}

	public static function is_audio($string)
	{
		return strings::starts_with($string, 'audio/');
	}
}